<?php

namespace App\Services;

use GuzzleHttp\Client;

class DingtalkService
{
	protected $client;

	public function __construct()
	{
		$this->client = new Client();
	}

	public function sendNotification($message)
	{
		$secret = 'SECac847961cae2d0da11d5300259cb9a6526634d60bbf3675c70444ddd2faddc31';
		$timestamp = time() * 1000;
		$stringToSign = $timestamp . "\n" . $secret;
		$sign = hash_hmac('sha256', $stringToSign, $secret, true);
		$sign = urlencode(base64_encode($sign));

		$client = new Client();

		$payload = [
			'msgtype' => 'text',
			'text' => [
				'content' => 'CMS Notification: ' . $message,
			],
		];

		$response = $client->post('https://oapi.dingtalk.com/robot/send?access_token=8ee4917ad0442d0d49e7293320fde236f4a310557458632ef81dde3db2f2d9e8&timestamp=' . $timestamp . '&sign=' . $sign, [
			'json' => $payload,
		]);

		if ($response->getStatusCode() == 200) {
			return 'Notification sent successfully';
		} else {
			return 'Failed to send notification';
		}
	}
}
