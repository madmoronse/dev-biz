<?php

class ControllerModuleOneClickBuy extends Controller
{
    /**
     * @inheritDoc
     */
    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->language->load('module/oneclickbuy');
    }

    /**
     * @inheritDoc
     */
    protected function index()
    {
        $config = $this->config->get('oneclickbuy');
        $timezone = !empty($config['timezone']) ? $config['timezone'] : 'Asia\Krasnoyarsk';
        $working_hours = array(
            'from' => !empty($config['working_hours']['from']) ? (int) $config['working_hours']['from'] : 10,
            'to' => !empty($config['working_hours']['to']) ? (int) $config['working_hours']['to'] : 17,
        );
        $date = new DateTime('now', new DateTimeZone($timezone));
        $hour = (int) $date->format('G');
        if ($hour >= $working_hours['from'] && $hour < $working_hours['to']) {
            $this->data['display'] = 'inline-block';
        } else {
            $this->data['display'] = 'none';
        }
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/oneclickbuy.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/oneclickbuy.tpl';
        } else {
            $this->template = 'default/template/module/oneclickbuy.tpl';
        }
        
        $this->render();
    }
    
    /**
     * Submit order
     *
     * @return void
     */
    public function submit()
    {
        $data = $this->request->post;
        if ($error = $this->isInvalidSubmitData($data)) {
            return $this->response->setOutput(json_encode(array(
                'error' => $error
            )));
        }
        $subject = html_entity_decode($this->language->get('email_subject'), ENT_QUOTES, 'UTF-8');
        $date = new DateTime('now', new DateTimeZone('Asia/Krasnoyarsk'));
        $text = html_entity_decode(sprintf(
            $this->language->get('email_body'),
            $date->format('Y-m-d H:i:s'),
            $this->xssafe(strip_tags($data['name'])),
            $data['tel'],
            $this->xssafe(strip_tags($data['city'])),
            $this->xssafe(strip_tags($data['comment'])),
            $data['page']
        ), ENT_QUOTES, 'UTF-8');

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->hostname = $this->config->get('config_smtp_host');
        $mail->username = $this->config->get('config_smtp_username');
        $mail->password = $this->config->get('config_smtp_password');
        $mail->port = $this->config->get('config_smtp_port');
        $mail->timeout = $this->config->get('config_smtp_timeout');
        $mail->setTo($this->config->get('config_email'));
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->language->get('email_sender'));
        $mail->setSubject($subject);
        $mail->setHtml($text);
        // It has no return value :(
        $mail->send();
        $this->response->setOutput(json_encode(array(
            'message' => $this->language->get('success')
        )));
    }

    /**
     * Make data xss safe
     *
     * @param string $data
     * @param string $encoding
     * @return string
     */
    protected function xssafe($data, $encoding = 'UTF-8')
    {
        return htmlspecialchars($data, ENT_QUOTES, $encoding);
    }

    /**
     * @param array $data
     * @return boolean
     */
    protected function isInvalidSubmitData($data)
    {
        $required = array('name', 'city', 'tel');
        $error = '';
        // Validate required fields
        foreach ($required as $key) {
            if (empty($data[$key])) {
                $missing[] = $key;
            }
        }
        if (isset($missing)) {
            foreach ($missing as $item) {
                $missing_names[] = $this->language->get('form_field_' . $item);
            }
            $error = sprintf(
                $this->language->get('error_missing_required'),
                implode(', ', $missing_names)
            );
            return $error;
        }
        // Validate each field
        $allowed = array_merge($required, array('comment', 'page'));
        foreach ($allowed as $key) {
            if (isset($data[$key])) {
                if (false === $this->validateFormField($key, $data[$key])) {
                    $field_error = $this->language->get('error_form_field_' . $key);
                    $field_errors = (empty($field_errors)) ? $field_error : $field_errors . ', ' . $field_error;
                }
            }
        }
        if (isset($field_errors)) {
            $error = sprintf(
                $this->language->get('error_field_errors'),
                $field_errors
            );
        }
        return $error;
    }

    /**
     * Validate form field
     *
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    protected function validateFormField($key, $value)
    {
        switch ($key) {
            case 'name':
            case 'city':
                return !preg_match('/[^\da-zа-яё_\-., ]/iu', $value);
            case 'tel':
                return !preg_match('/[^\d()+\- ]/', $value);
            case 'comment':
                return true;
            case 'page':
                return filter_var($value, FILTER_VALIDATE_URL);
            default:
                return false;
        }
    }
}
