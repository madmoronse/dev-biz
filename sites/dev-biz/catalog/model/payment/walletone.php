<?php 

class ModelPaymentWalletOne extends Model
{
    public function getMethod($address, $total)
    {
        return array();
    }
      
    /**
     * @param array $order_info
     * @return string
     */
    public function getForm(array $order_info)
    {
        // Секретный ключ интернет-магазина
        $sig = $this->config->get('walletone_secret_key');
        $method = $this->config->get('walletone_sig_method');
        if (!in_array($method, array('md5', 'sha1'))) {
            $method = 'md5';
        }
        $fields = array();
        $query = $this->db->query("SELECT prepayment, cash_on_delivery FROM " . DB_PREFIX . "order WHERE order_id = " . (int) $order_info['order_id']);
        $order_info = array_merge($order_info, $query->row);
        if ($order_info['prepayment'] > 0) {
            $amount = $order_info['prepayment'];
        } else {
            $amount = $order_info['total'] - $order_info['cash_on_delivery'];
        }
        // Добавление полей формы в ассоциативный массив
        $fields["WMI_MERCHANT_ID"]    = $this->config->get('walletone_merchant_id');
        $fields["WMI_PAYMENT_AMOUNT"] = number_format($amount, 2, '.', '');
        $fields["WMI_CURRENCY_ID"]    = "643";
        $fields["WMI_DESCRIPTION"]    = "BASE64:".base64_encode("Заказ #" . $order_info['order_id'] . ". " . $order_info['shipping_method']);
        $fields["WMI_PAYMENT_NO"]     = $order_info['order_id'];
        $fields["WMI_EXPIRED_DATE"]   = date('Y-m-d\TH:i:s', time() + 30 * 24 * 60 * 60);
        $fields["WMI_SUCCESS_URL"]    = $this->url->link('index.php?route=payment/walletone/success', '', 'SSL');
        $fields["WMI_FAIL_URL"]       = $this->url->link('index.php?route=payment/walletone/fail', '', 'SSL');
        $fields = $this->prepareFields($fields);
        // Добавление параметра WMI_SIGNATURE в словарь параметров формы
        $fields["WMI_SIGNATURE"] = $this->createSignature($fields, $sig, $method);
        // Формирование HTML-кода платежной формы
        return $this->getFormHtml($fields);
    }

    /**
     * @param array $fields
     * @return string
     */
    protected function getFormHtml(array $fields)
    {
        $html = '<form action="https://wl.walletone.com/checkout/checkout/Index" id="walletone_payment" method="POST" target="_blank">';
        foreach ($fields as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $value) {
                    $html .= sprintf(
                        '<input type="hidden" name="%s" value="%s"/>',
                        htmlspecialchars($key),
                        htmlspecialchars($value)
                    );
                }
            } else {
                $html .= sprintf(
                    '<input type="hidden" name="%s" value="%s"/>',
                    htmlspecialchars($key),
                    htmlspecialchars($val)
                );
            }
        }
        $html .= '</form>';
        return $html;
    }

    /**
     * @param array $fields
     * @return array
     */
    protected function prepareFields(array $fields)
    {
        //Сортировка значений внутри полей
        foreach ($fields as $name => $val) {
            if (is_array($val)) {
                usort($val, "strcasecmp");
                $fields[$name] = $val;
            }
        }
        uksort($fields, "strcasecmp");
        return $fields;
    }

    /**
     * @param array $fields
     * @param string $sig
     * @param string $method
     * @return string
     */
    protected function createSignature(array $fields, $sig, $method)
    {
        $fieldValues = "";
        foreach ($fields as $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $v = iconv("utf-8", "windows-1251", $v);
                    $fieldValues .= $v;
                }
            } else {
                $value = iconv("utf-8", "windows-1251", $value);
                $fieldValues .= $value;
            }
        }
        return base64_encode(pack("H*", $method($fieldValues . $sig)));
    }
}
