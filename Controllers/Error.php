<?php
session_start();
class Errors extends Controllers
{
	public $views;
	public $model;
	public function __construct()
	{
		parent::__construct();
	}

	public function notFound()
	{
		$this->views->getView($this, "error");
	}
}

$notFound = new Errors();
$notFound->notFound();