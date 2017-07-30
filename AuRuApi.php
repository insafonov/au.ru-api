<?php
namespace INSafonov;

class AuRuApi
{
	private $token;
	public $lots;

	public function sendRequest($requestData)
	{
		$method = $requestData['method'] ?: 'GET';

		$headers = [];
		foreach ($requestData['headers'] as $i => $header) {
			$headers[] = "$i: $header";
		}

		$opts = [
			'http' => [
				'method' => $method,
				'header' => join("\r\n", $headers),
			]
		];

		if (array_key_exists('content', $requestData))
			$opts['http']['content'] = $requestData['content'];

		$context = stream_context_create($opts);

		$response = @file_get_contents('https://apidemo.au.ru/v1/' . ltrim($requestData['path'], '/'), false, $context);

		$code = $this->getResponseCode($http_response_header);

		if ($code === false)
			return false;
		else
			return [$code, $response];
	}

	private function getResponseCode($headers)
	{
		for ($i = 0; $i < count($headers); $i++) {
			$matches = [];
			if (preg_match('#^HTTP/\d+\.\d+\s+(\d{3})#', $headers[$i], $matches))
				return (int)$matches[1];
		}

		return false;
	}

	public function __construct($params = [])
	{
		if (array_key_exists('token', $params))
			$this->token = $params['token'];

		$this->lots = new AuRuApi\Lots($this, ['token' => $this->token]);
	}
}
