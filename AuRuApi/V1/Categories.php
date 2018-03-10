<?php
namespace INSafonov\AuRuApi\V1;

use INSafonov\AuRuApi\Exception;

class Categories
{
	private $api;
	private $token;

	public function __construct($api, $params)
	{
		if (array_key_exists('token', $params))
			$this->token = $params['token'];
		$this->api = $api;
	}

	/**
	 *
	 * @return Object
	 * @throws Exception
	 */
	public function get()
	{
		$headers = [
			'X-Auth-Token' => $this->token,
			'Content-type' => 'application/json',
		];

		list($code, $data) = $this->api->sendRequest([
			'path' => '/categories/',
			'method' => 'GET',
			'headers' => $headers,
		]);

		if ($code === 200)
			return json_decode($data);
		elseif ($code === 401)
			throw new Exception('Не был передан авторизационный токен или он просрочен');
		elseif ($code === 500)
			throw new Exception('Внутреняя ошибка сервера');
		elseif ($code === 501)
			throw new Exception('Запрошенный ресурс устарел и больше не используется');
		else
			throw new Exception("Search error/ HTTP status $code, ");
	}
}
