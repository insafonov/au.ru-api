<?php
namespace INSafonov;

class AuRuApi
{
	protected $token;
	public $baseUrl;

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
				'header' => implode("\r\n", $headers),
			]
		];

		if (array_key_exists('content', $requestData))
			$opts['http']['content'] = $requestData['content'];

		$context = stream_context_create($opts);

		$response = @file_get_contents("$this->baseUrl/" . ltrim($requestData['path'], '/'), false, $context);

		$code = $this->getResponseCode($http_response_header);

		if ($code === false)
			return false;
		else
			return [$code, $response];
	}

	protected function getResponseCode($headers)
	{
		for ($i = count($headers) - 1; $i >= 0; $i--) {
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
	}
}
