<?php

class ControllerDropProxy extends Controller
{

    /**
     * @var array
     */
    protected $methodToId = array(
        'track' => 1,
        'calculateShipping' => 2
    );

    /**
     * Token info cache
     * @var array
     */
    protected $tokenInfo = array();

    public function index()
    {
        if (!$this->isCustomerAllowed()) {
            return $this->redirect('/');
        }
        $this->template = $this->config->get('config_template') . '/template/drop/proxy.tpl';
        $this->children = array(
            'drop/header',
            'drop/footer'
        );
        $this->response->setOutput($this->render());
    }

    /**
     * @api
     * @return void
     */
    public function token()
    {
        $grant_type = $this->request->post['grant_type'];
        if ($grant_type !== 'password') {
            return $this->unprocessableEntityResponse();
        }
        $username = $this->request->post['username'];
        $password = $this->request->post['password'];
        if (!$this->customer->login($username, $password)) {
            $this->language->load('account/login');
            $this->unauthorizedResponse($this->language->get('error_login'));
            return;
        }
        if (!$this->isCustomerAllowed()) {
            $this->forbiddenResponse();
            return;
        }
        $token = $this->generateToken();
        // delete previous tokens
        $this->db->query(
            sprintf(
                "DELETE FROM `proxy_access_tokens` WHERE `customer_id` = %u",
                $this->customer->getId()
            )
        );
        // after 2 weeks
        $expires_in = 24 * 3600 * 14;
        $expires_at = date('Y-m-d H:i:s', time() + $expires_in);
        $created_at = date('Y-m-d H:i:s');
        $query = $this->db->query(
            sprintf(
                "INSERT INTO `proxy_access_tokens` SET `token` = '%s', `customer_id` = %u, `created_at` = '%s', `expires_at` = '%s'",
                $token,
                $this->customer->getId(),
                $created_at,
                $expires_at
            )
        );
        if ($this->db->getLastId()) {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(array(
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $expires_in,
            )));
        } else {
            $this->internalServerErrorResponse();
        }
        $this->customer->logout();
    }

    /**
     * Track order
     * @api
     * @return void
     */
    public function track()
    {
        if (!$this->isRequestAllowed('track')) {
            return;
        }
        list($xml, $account, $date, $secure) = $this->prepareCredentialsForXmlRequestBody();
        if (!$xml) {
            return $this->unprocessableEntityResponse();
        }
        $xml['Account'] = $account;
        $xml['Date'] = $date;
        $xml['Secure'] = $secure;
        list($response, $response_info) = $this->makeRequest(
            'https://integration.cdek.ru/status_report_h.php',
            $xml->asXML()
        );
        $this->proxyResponseHeaders($response_info);
        $this->response->setOutput($response);
    }

    /**
     * @api
     * @return void
     */
    public function calculateShipping()
    {
        if (!$this->isRequestAllowed('calculateShipping')) {
            return;
        }
        $request = json_decode(file_get_contents('php://input'), true);
        if (!$request) {
            return $this->badRequestResponse('Request body must be a JSON');
        }
        if (!isset($request['postcode']) || strlen($request['postcode']) !== 6) {
            return $this->unprocessableEntityResponse('Postcode must be 6 digit number');
        }
        if (!isset($request['products']) || !is_array($request['products'])) {
            return $this->unprocessableEntityResponse('Products must be an array');
        }
        $products = array();
        foreach ($request['products'] as $product) {
            if (!isset($product['product_id']) || !is_numeric($product['product_id'])) {
                return $this->unprocessableEntityResponse('Product id must be numeric');
            }
            if (!isset($product['quantity']) || !is_numeric($product['quantity'])) {
                return $this->unprocessableEntityResponse('Product quantity must be numeric');
            }
            $products[] = array(
                'product_id' => (int) $product['product_id'],
                'quantity' => (int) $product['quantity'],
            );
        }
        $this->load->model('drop/proxy');
        $this->load->model('checkout/shipping');
        $token_info = $this->getTokenInfo($this->getAccessToken());
        $customer_group_id = $this->getCustomerGroupId($token_info['customer_id']);
        $fallback = array(
            'products' => $this->model_drop_proxy->getProductsFromRequest($products, $customer_group_id),
            'ignore_krsk' => true
        );
        if (count($fallback['products']) === 0) {
            return $this->unprocessableEntityResponse('Products not found');
        }
        $order_sum = array_reduce($fallback['products'], function ($total, $product) {
            $total += $product['total'];
            return $total;
        }, 0);
        $is_calculator = true;
        $shipping_methods = $this->model_checkout_shipping->getCustomDelivery(
            $order_sum,
            $request['postcode'],
            $fallback,
            $is_calculator
        );
        $result = array();
        foreach ($shipping_methods as $method) {
            $result[] = array(
                'name' => $method['place'] . '. ' . $method['payment'],
                'delivery_cost' => $method['dcost'],
                'order_cost' => $method['fullcost'],
                'order_prepayment' => $method['prepayment']
            );
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($result));
    }

    /**
     * @return string
     */
    protected function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }

    /**
     * @param string $token
     * @return array|null
     */
    protected function getTokenInfo($token)
    {
        if (!isset($this->tokenInfo[$token])) {
            $query = $this->db->query(
                sprintf("SELECT * FROM `proxy_access_tokens` WHERE `token` = '%s' LIMIT 1", $this->db->escape($token))
            );
            if ($query->num_rows === 0) {
                $this->tokenInfo[$token] = false;
            } else {
                $this->tokenInfo[$token] = $query->row;
            }
        }
        return $this->tokenInfo[$token] ? $this->tokenInfo[$token] : null;
    }

    /**
     * @return boolean
     */
    protected function isCustomerAllowed()
    {
        if (in_array($this->customer->getCustomerGroupId(), array(3, 4))) {
            return true;
        }
        return false;
    }

    /**
     * @return boolean
     */
    protected function isRequestAllowed($method_name = null)
    {
        $token = $this->getAccessToken();
        if (!$token) {
            $this->unauthorizedResponse('No token');
            return false;
        }
        $token_info = $this->getTokenInfo($token);
        if ($token_info === null) {
            $this->unauthorizedResponse('Invalid token');
            return false;
        }
        if (\DateTime::createFromFormat('Y-m-d H:i:s', $token_info['expires_at']) < new DateTime()) {
            $this->unauthorizedResponse('Expired token');
            return false;
        }
        $token_id = $token_info['id'];
        $customer_id = $token_info['customer_id'];
        $limits = $this->getCustomerLimits($customer_id);
        $this->countRequest($token_id, $customer_id, $method_name);
        // If limit is zero - it's unlimited
        if ($method_name !== null && !empty($limits->$method_name)) {
            $requests_for_method = $this->getRequestCountForMethod($customer_id, $method_name);
            // Substract one because we already counted it
            if ($requests_for_method - 1 >= $limits->$method_name) {
                $this->tooManyRequestsResponse(
                    "Exceeded request limit '{$limits->$method_name}' for method '$method_name'"
                );
                return false;
            }
        }
        $requests_per_minute = $this->getRequestCountPerMinute($customer_id);
        // Substract one because we already counted it
        if ($requests_per_minute - 1 >= $limits->perMinute) {
            $this->tooManyRequestsResponse();
            return false;
        }
        return true;
    }

    /**
     * @param integer $customer_id
     * @return \stdClass
     */
    protected function getCustomerLimits($customer_id)
    {
        $query = $this->db->query(
            sprintf(
                "SELECT * FROM `proxy_customer_limits` WHERE `customer_id` = %u",
                (int) $customer_id
            )
        );
        $limits = $query->num_rows > 0 ? $query->row : array();
        return (object) array(
            'perMinute' => isset($limits['per_minute']) ? $limits['per_minute'] : 30,
            'track' => isset($limits['track']) ? $limits['track'] : 0,
            'calculateShipping' => isset($limits['calculate_shipping']) ? $limits['calculate_shipping'] : 250
        );
    }


    /**
     * @param integer $customer_id
     * @return integer
     */
    protected function getCustomerGroupId($customer_id)
    {
        $query = $this->db->query(
            sprintf(
                "SELECT `customer_group_id` FROM " . DB_PREFIX . "customer WHERE customer_id = %u",
                $customer_id
            )
        );
        return $query->num_rows > 0 ? $query->row['customer_group_id'] : $this->config->get('config_customer_group_id');
    }

    /**
     * @param integer $customer_id
     * @param string $method_name
     * @return integer
     */
    protected function getRequestCountForMethod($customer_id, $method_name)
    {
        $date = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $where = array(sprintf("`date` = '%s'", $date));
        switch ($method_name) {
            case 'track':
                $where[] = sprintf(
                    "HOUR(`datetime`) = HOUR('%s') AND MINUTE(`datetime`) = MINUTE('%s')",
                    $datetime,
                    $datetime
                );
                break;
        }
        $method_id = isset($this->methodToId[$method_name]) ? $this->methodToId[$method_name] : 0;
        $query = $this->db->query(
            sprintf(
                "SELECT count(*) as `count` FROM `proxy_requests`
                WHERE `customer_id` = %u AND `method` = %u AND %s",
                (int) $customer_id,
                $method_id,
                implode('AND', $where)
            )
        );
        return $query->num_rows > 0 ? $query->row['count'] : 0;
    }

    /**
     * @param integer $customer_id
     * @return integer
     */
    protected function getRequestCountPerMinute($customer_id)
    {
        $date = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');
        $query = $this->db->query(
            sprintf(
                "SELECT count(*) as `count` FROM `proxy_requests`
                WHERE `customer_id` = %u AND `date` = '%s'
                AND HOUR(`datetime`) = HOUR('%s') AND MINUTE(`datetime`) = MINUTE('%s')",
                (int) $customer_id,
                $date,
                $datetime,
                $datetime
            )
        );
        return $query->num_rows > 0 ? $query->row['count'] : 0;
    }

    /**
     * @return string|null
     */
    protected function getAccessToken()
    {
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['HTTP_X_AUTHORIZATION'])) {
            $auth = $_SERVER['HTTP_X_AUTHORIZATION'];
        }
        if (isset($auth) && preg_match('/Bearer (.*)/', $auth, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * @param string $url
     * @param string $body
     * @param array $params
     * @return array
     */
    protected function makeRequest($url, $body, $params = array())
    {
        $ch = curl_init($url);
        $header[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if (strlen($body) > 0) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "xml_request=$body");
        }
        $response = curl_exec($ch);
        $response_info = curl_getinfo($ch);
        curl_close($ch);
        return array($response, $response_info);
    }

    /**
     * @param integer $token_id
     * @param integer $customer_id
     * @param string|null $method_name
     * @return void
     */
    protected function countRequest($token_id, $customer_id, $method_name = null)
    {
        $this->db->query(
            sprintf(
                "INSERT INTO `proxy_requests`
                SET `token_id` = %u, `customer_id` = %u, `date` = '%s', `datetime` = '%s', `method` = %u",
                $token_id,
                $customer_id,
                date('Y-m-d'),
                date('Y-m-d H:i:s'),
                isset($this->methodToId[$method_name]) ? $this->methodToId[$method_name] : 0
            )
        );
    }

    /**
     * @param string $body
     * @return array ($xml, $account, $date, $secure)
     */
    protected function prepareCredentialsForXmlRequestBody()
    {
        $xml = simplexml_load_string(file_get_contents('php://input'));
        $account = $this->config->get('customcdek_login');
        $password = $this->config->get('customcdek_password');
        $date = date('Y-m-d');
        $secure = md5($date . '&' . $password);

        return array($xml, $account, $date, $secure);
    }

    /**
     * @param array $response_info
     * @param array $headers
     * @return void
     */
    protected function proxyResponseHeaders($response_info, $headers = array())
    {
        $this->response->addHeader("Content-Type: {$response_info['content_type']}");
        $this->response->addHeader("HTTP/1.1 {$response_info['http_code']}");
    }
    
    /**
     * @param string $message
     * @return void
     */
    protected function unprocessableEntityResponse($message = 'Wrong request body')
    {
        $this->response->addHeader("HTTP/1.1 422 Unprocessable Entity");
        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode(array(
            'error' => array(
                'message' => $message
            )
        )));
    }

    /**
     * @param string $message
     * @return void
     */
    protected function badRequestResponse($message = 'Bad request')
    {
        $this->response->addHeader("HTTP/1.1 400 Bad Request");
        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode(array(
            'error' => array(
                'message' => $message
            )
        )));
    }

    /**
     * @return void
     */
    protected function unauthorizedResponse($message = 'Authorization required')
    {
        $this->response->addHeader("HTTP/1.1 401 Unauthorized");
        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode(array(
            'error' => array(
                'message' => $message
            )
        )));
    }

    /**
     * @return void
     */
    protected function tooManyRequestsResponse($message = 'Too Many Requests')
    {
        $this->response->addHeader("HTTP/1.1 429 Too Many Requests");
        $this->response->addHeader("Content-Type: application/json");
        $this->response->addHeader("Retry-After: " . (60 - (int) date('s')));

        $this->response->setOutput(json_encode(array(
            'error' => array(
                'message' => $message
            )
        )));
    }

    /**
     * @return void
     */
    protected function forbiddenResponse()
    {
        $this->response->addHeader("HTTP/1.1 403 Forbidden");
        $this->response->addHeader("Content-Type: application/json");

        $this->response->setOutput(json_encode(array(
            'error' => array(
                'message' => 'Forbidden'
            )
        )));
    }

    /**
     * @return void
     */
    protected function internalServerErrorResponse()
    {
        $this->response->addHeader("HTTP/1.1 500 Internal Server Error");
        $this->response->addHeader("Content-Type: application/json");

        $this->response->setOutput(json_encode(array(
            'error' => array(
                'message' => 'Internal Server Error'
            )
        )));
    }
}
