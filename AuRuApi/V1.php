<?php
namespace INSafonov\AuRuApi;

use INSafonov\AuRuApi\V1\Items;

class V1 extends \INSafonov\AuRuApi
{
	public $items;

	public function __construct($params = [])
	{
		parent::__construct($params);

		$this->items = new Items($this, ['token' => $this->token]);
		$this->baseUrl = 'https://market-api.au.ru/v1';
	}
}
