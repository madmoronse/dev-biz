<?php
class ControllerPaymentWalletOne extends Controller {
    protected function index() {
        $this->response->setOutput('
            <h1 style="text-align:center;">
                Операция проведена.
            </h1>
            <h4 style="text-align:center;">
                Вернуться на <a href="http://bizoutmax.ru/">главную страницу OUTMAX</a>
            </h4>
        ');
    }

	public function success() {
        $this->response->setOutput('
            <h1 style="text-align:center;">
                Операция проведена.
            </h1>
            <h4 style="text-align:center;">
                Вернуться на <a href="http://bizoutmax.ru/">главную страницу OUTMAX</a>
            </h4>
        ');
    }
    
    public function fail() {
        $this->response->setOutput('
            <h1 style="text-align:center;">
                Операция не прошла.
            </h1>
            <h4 style="text-align:center;">
                Вернуться на <a href="http://bizoutmax.ru/">главную страницу OUTMAX</a>
            </h4>
        ');
	}
}
?>