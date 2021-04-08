<?php
header('Content-Type: text/html; charset= utf-8');

$data = json_decode(file_get_contents('php://input'), TRUE);
file_put_contents('file.txt', '$data: '.print_r($data,1). "\n", FILE_APPEND);

$data = $data['callback_query'] ? $data['callback_query'] : $data['message'];

define('TOKEN', '1515036922:AAHv_Onjj9NiBfG3YHrm394MTvjEdQR1ubw');

$message = mb_strtolower(($data['text'] ? $data['text'] : $data['data']), 'utf-8');

switch ($message) {
	case 'старт':
		$method = 'sendMessage';
		$send_data = [
			'text' =>'Ну, выбирай',
			'reply_markup' => [
				'resize_keyboard' => true,
				'keyboard' => [
					[
						['text' => 'Жмякни сюда'],
						['text' => 'Или сюда'],
					],
					[
						['text' => 'Нажми-ка сюда'],
						['text' => 'Можешь даже сюда'],
					]
				]
			]
		];
		break;

	case 'нажми-ка сюда':
			$method = 'sendPhoto';
			$send_data = [
				'photo' => 'https://sun9-69.userapi.com/impg/jufIK9GOnfxLKSOmIv5g-sO3xrS27-BN9T3Xqw/PxIo7lRVHMU.jpg?size=960x1280&quality=96&proxy=1&sign=94aa9e8b6630799661d728699744ff69&type=album',
				'caption' => 'Ку-ку'	
		];
		break;

	case 'открой меню':
		$method = 'sendMessage';
		$send_data = [
			'text' =>'Вот меню:',
			'reply_markup' => [
				'resize_keyboard' => true,
				'keyboard' => [
					[
						['text' => 'И тебе привет'],
						['text' => 'Сам ты пёс'],
					],
					[
						['text' => 'Нажми-ка сюда'],
						['text' => 'Ты лошара'],
					]
				]
			]
		];
		break;

		case 'жмякни сюда':
			$method = 'sendPhoto';
			$send_data = [
				'photo' => 'https://sun9-69.userapi.com/impg/jufIK9GOnfxLKSOmIv5g-sO3xrS27-BN9T3Xqw/PxIo7lRVHMU.jpg?size=960x1280&quality=96&proxy=1&sign=94aa9e8b6630799661d728699744ff69&type=album',
				'caption' => 'Ку-ку'	
		];
		break;
		case 'или сюда':
			$method = 'sendPhoto';
			$send_data = [
				'photo' => 'https://sun9-69.userapi.com/impg/jufIK9GOnfxLKSOmIv5g-sO3xrS27-BN9T3Xqw/PxIo7lRVHMU.jpg?size=960x1280&quality=96&proxy=1&sign=94aa9e8b6630799661d728699744ff69&type=album',
				'caption' => 'Ку-ку'	
		];
		break;

		case 'можешь даже сюда':
			$method = 'sendPhoto';
			$send_data = [
				'photo' => 'https://sun9-69.userapi.com/impg/jufIK9GOnfxLKSOmIv5g-sO3xrS27-BN9T3Xqw/PxIo7lRVHMU.jpg?size=960x1280&quality=96&proxy=1&sign=94aa9e8b6630799661d728699744ff69&type=album',
				'caption' => 'Ку-ку'	
		];
		break;
		
	default:
		$method = 'sendMessage';
		$send_data = [
			'text' =>'Что ты несёшь'
		];
}

$send_data['chat_id'] = $data['chat']['id'];
$res = sendTelegram($method, $send_data);

function sendTelegram($method, $data, $headers = []) {
	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_POST => 1,
		CURLOPT_HEADER => 0,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'https://api.telegram.org/bot' . TOKEN . '/' .$method,
		CURLOPT_POSTFIELDS => json_encode($data),
		CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"), $headers)
	]);

	$result = curl_exec($curl);
	curl_close($curl);
	
	return  (json_decode($result, 1) ? json_decode($result, 1) : $result);
}