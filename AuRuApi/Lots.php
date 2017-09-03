<?php
namespace INSafonov\AuRuApi;

class Lots
{
	private $api;
	private $token;

	public function __construct($api, $params)
	{
		if (array_key_exists('token', $params))
			$this->token = $params['token'];
		$this->api = $api;
	}

	public function find($searchParams)
	{
		$searchParams = filter_var_array($searchParams, [
			'Id' => FILTER_VALIDATE_INT,
			'ExtId' => FILTER_DEFAULT,
			'Name' => FILTER_DEFAULT,
		], false);

		if (!$searchParams)
			throw new Exception('Some of Id, ExtId or Name required.');

		$invalidParams = array_keys(array_filter($searchParams, function ($p) {
			return $p === false;
		}));

		if ($invalidParams)
			throw new Exception('Invalid input values: ' . join(', ', $invalidParams) . '.');

		$headers = [
			'Authorization' => $this->token,
			'Content-type' => 'application/json',
		];

		list($code, $data) = $this->api->sendRequest([
			'path' => '/items/find',
			'method' => 'POST',
			'headers' => $headers,
			'content' => json_encode((object)$searchParams, JSON_UNESCAPED_UNICODE),
		]);

		if ($code === 200)
			return json_decode($data);
		elseif ($code === 404)
			return null;
		elseif ($code === 401)
			throw new Exception('You have not valid authentication credentials.');
		elseif ($code)
			throw new Exception("Search error/ HTTP status $code, " . print_r($searchParams, true));
		else
			throw new Exception('Network error');
	}

	public function update($id, $params)
	{
		if (!array_key_exists('Price', $params))
			throw new Exception('Parameter required: Price.');

		$id = filter_var($id, FILTER_VALIDATE_INT);

		if ($id === false)
			throw new Exception('Invalid id parameter passed');

		$params = filter_var_array($params, [
			'Price' => FILTER_VALIDATE_INT,
			'Count' => FILTER_VALIDATE_INT,
		], false);

		$invalidParams = array_keys(array_filter($params, function ($p) {
			return $p === false;
		}));

		if ($invalidParams)
			throw new Exception('Invalid input values: ' . join(', ', $invalidParams) . '.');

		$headers = [
			'Authorization' => $this->token,
			'Content-type' => 'application/json',
		];

		list($code) = $this->api->sendRequest([
			'path' => "/items/$id/update",
			'method' => 'POST',
			'headers' => $headers,
			'content' => json_encode((object)$params, JSON_UNESCAPED_UNICODE),
		]);

		if ($code === 200)
			return true;
		elseif ($code === 401)
			throw new Exception('You have not valid authentication credentials.');
		elseif ($code)
			throw new Exception("Update error. HTTP status: $code, problem lot id: $id, params: " . print_r($params, true));
		else
			throw new Exception('Network error');
	}

	public function close($id, $params = [])
	{
		$id = filter_var($id, FILTER_VALIDATE_INT);

		if ($id === false)
			throw new Exception('Invalid id parameter passed');

		$params = filter_var_array($params, [
			'Reason' => FILTER_DEFAULT,
		], false);

		$invalidParams = array_keys(array_filter($params, function ($p) {
			return $p === false;
		}));

		if ($invalidParams)
			throw new Exception('Invalid input values: ' . join(', ', $invalidParams) . '.');

		$headers = [
			'Authorization' => $this->token,
			'Content-type' => 'application/json',
		];

		list($code) = $this->api->sendRequest([
			'path' => "/items/$id/close",
			'method' => 'POST',
			'headers' => $headers,
			'content' => json_encode((object)$params, JSON_UNESCAPED_UNICODE),
		]);

		if ($code === 200)
			return true;
		elseif ($code === 401)
			throw new Exception('You have not valid authentication credentials.');
		elseif ($code)
			throw new Exception("Close error. HTTP status: $code, problem lot id: $id, params: " . print_r($params, true));
		else
			throw new Exception('Network error');
	}

	public function repeat($id)
	{
		$id = filter_var($id, FILTER_VALIDATE_INT);

		if ($id === false)
			throw new Exception('Invalid id parameter passed');

		$headers = [
			'Authorization' => $this->token,
			'Content-type' => 'application/json',
		];

		list($code) = $this->api->sendRequest([
			'path' => "/items/$id/repeat",
			'method' => 'POST',
			'headers' => $headers,
		]);

		if ($code === 200)
			return true;
		elseif ($code === 401)
			throw new Exception('You have not valid authentication credentials.');
		elseif ($code)
			throw new Exception("Repeat error. HTTP status: $code, lot id: $id");
		else
			throw new Exception('Network error');
	}
}
