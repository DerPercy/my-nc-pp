<?php

namespace OCA\MyPPApp\Tests\Unit\Domain;

require_once __DIR__.'/../../../lib/modules/Domain/Domain.php';


class DomainTest extends \PHPUnit\Framework\TestCase {
	private $domain;
	public function setUp():void {
		$this->domain = new \My\Domain\Domain();
	}

	public function testEntities() {
		//print_r($this->domain->getEntities());
		$this->assertEquals(2, count($this->domain->getEntities()), "Wrong number of entities");
    //$this->assertEquals(1, count($this->rootNode->getSubNodes()[0]->getSubNodes()), "Wrong subnodes for Node 1");
	}

	public function testEntityRelations() {
		// Get project entity
		$pEntity = $this->domain->getEntities()["project"];
		// Entity has 1 relation
		$this->assertEquals(1, count($pEntity["relations"]), "Wrong number of entityrelations");
		$this->assertEquals("customer", $pEntity["relations"]["projectcustomerTo"]["destination"]);
		$this->assertEquals(false, $pEntity["relations"]["projectcustomerTo"]["multiValue"]);

		// Get customer entity
		$cEntity = $this->domain->getEntities()["customer"];
		$this->assertEquals("project", $cEntity["relations"]["projectcustomerFrom"]["destination"]);
		$this->assertEquals(true, $cEntity["relations"]["projectcustomerFrom"]["multiValue"]);

		print_r($this->domain->getEntities());
	}

}
