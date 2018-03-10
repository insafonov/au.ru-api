<?php
namespace INSafonov\AuRuApi\V1;

use INSafonov\AuRuApi\Exception;

class Items
{
	const TYPE_STANDAR = 'standart'; // стандартные торги с ставками
	const TYPE_FIXED = 'fixed'; // торги с фиксированной ценой
	const TYPE_REVERSE = 'reverse'; // торги с автоматическим понижением цены до первой ставки

	const STATUS_ACTIVE = 'active';
	const STATUS_STOPPED = 'stopped';

	private $api;
	private $token;

	private function validate_items($items)
	{
		return $items;
	}

	public function __construct($api, $params)
	{
		if (array_key_exists('token', $params))
			$this->token = $params['token'];
		$this->api = $api;
	}

	public function get($params)
	{
		$headers = [
			'X-Auth-Token' => $this->token,
			'Content-type' => 'application/json',
		];

		$query = $params;

		list($code, $data) = $this->api->sendRequest([
			'path' => '/items/' . ($query ? '?' . http_build_query($query) : ''),
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
			throw new Exception("Search error/ HTTP status $code, " . var_export($params, true));
	}

	/**
	 *
	 * @param array $lot
	 * @return type
	 * @throws Exception
	 */
	public function create($lot)
	{
		$headers = [
			'X-Auth-Token' => $this->token,
			'Content-type' => 'application/json',
		];

		list($code, $data) = $this->api->sendRequest([
			'path' => '/items/',
			'method' => 'POST',
			'headers' => $headers,
			'content' => json_encode($lot, JSON_UNESCAPED_UNICODE),
		]);

		if ($code === 200)
			return json_decode($data);
		elseif ($code === 400)
			throw new Exception('Ошибка валидации входных параметров: ' . $data);
		elseif ($code === 401)
			throw new Exception('Не был передан авторизационный токен или он просрочен');
		elseif ($code === 500)
			throw new Exception('Внутреняя ошибка сервера');
		elseif ($code === 501)
			throw new Exception('Запрошенный ресурс устарел и больше не используется');
		else
			throw new Exception("Create error/ HTTP status $code, " . var_export($lot, true));
	}

	/**
	 *
	 * @param array $items
	 * @return type
	 * @throws Exception
	 */
	public function merge($items)
	{
		$headers = [
			'X-Auth-Token' => $this->token,
			'Content-type' => 'application/json',
		];

		list($code, $data) = $this->api->sendRequest([
			'path' => '/items/merge/batch/',
			'method' => 'POST',
			'headers' => $headers,
			'content' => json_encode(['list' => $items], JSON_UNESCAPED_UNICODE),
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
			throw new Exception("Search error/ HTTP status $code, " . var_export($items, true));
	}
}
