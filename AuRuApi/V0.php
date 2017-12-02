<?php
namespace INSafonov\AuRuApi;

use INSafonov\AuRuApi\V0\Lots;

class V0 extends \INSafonov\AuRuApi
{
	public $lots;

	public function __construct($params = [])
	{
		parent::__construct($params);

		$this->lots = new Lots($this, ['token' => $this->token]);
		$this->baseUrl = 'https://apidemo.au.ru/v1';
	}
}
