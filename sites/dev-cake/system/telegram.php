<?php
Class Telegram
{
	public $token = '1597848789:AAHFO8wmND7Cia6DS9UvcEnFsPd9I_8W9Wo';
	public $chat_id = '-554802612';
	public $headers = [];
	public $message;
	public function send($data)
	{
		foreach ($data as $key => $value) {
    		$txt .= "<b>" . $key . "</b>" . $value . "%0A";
		}

		$sendToTelegram = fopen("https://api.telegram.org/bot{$this->token}/sendMessage?chat_id={$this->chat_id}&parse_mode=html&text={$txt}", "r");

		return true;
	}
}