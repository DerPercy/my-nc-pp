<?php

namespace OCA\MyPPApp\Tests\Unit\Controller;

use OCP\AppFramework\Http\TemplateResponse;

use OCA\MyPPApp\Controller\PageController;


class PageControllerTest extends \PHPUnit\Framework\TestCase {
	private $controller;
	private $userId = 'john';

	public function setUp():void {
		$request = $this->getMockBuilder('OCP\IRequest')->getMock();

		$this->controller = new PageController(
			'myppapp', $request, $this->userId
		);
	}

	public function testIndex() {
		$result = $this->controller->index();

		$this->assertEquals('index', $result->getTemplateName());
		$this->assertTrue($result instanceof TemplateResponse);
	}

}
