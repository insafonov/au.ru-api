<?php
namespace INSafonov\AuRuApi;

use INSafonov\AuRuApi\V1\Items;
use INSafonov\AuRuApi\V1\Categories;

class V1 extends \INSafonov\AuRuApi
{
	public $items;
	public $categories;

	public function __construct($params = [])
	{
		parent::__construct($params);

		$this->items = new Items($this, ['token' => $this->token]);
		$this->categories = new Categories($this, ['token' => $this->token]);
		$this->baseUrl = 'https://market-api.au.ru/v1';
	}
}
