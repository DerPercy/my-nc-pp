<?php

namespace OCA\MyPPApp\Tests\Unit\OrgMode;

require_once __DIR__.'/../../../lib/modules/OrgMode/Parser.php';
require_once __DIR__.'/../../../lib/modules/OrgMode/Query.php';


class QueryTest extends \PHPUnit\Framework\TestCase {
  private $rootNode = null;
  private $query = null;
  public function setUp():void {
    $filecontent = $file = file_get_contents(__DIR__.'/queryTest.org');
    //print($filecontent);
    $parser = new \My\OrgMode\Parser();
    $this->rootNode = $parser->parseString($filecontent);
    $this->query = new \My\OrgMode\Query();
  }

	public function testQuery() {
		$results = $this->query->Query($this->rootNode)->getResult();
		$this->assertEquals(6, count($results), "Wrong results");

	}
  public function testLogbookQuery() {
    $results = $this->query->logbookQuery($this->rootNode,["properties" => ["PROP2" => "Value 2"] ])->getResult();
    $this->assertEquals(5, count($results), "Wrong results");
    $this->assertEquals("Header 1.1", $results[0]->getNode()->getTitle(), "Wrong assigned node");

    $results = $this->query->logbookQuery($this->rootNode,["month" => 2, "year" => 2021, "properties" => ["PROP2" => "Value 2"]])->getResult();
    $this->assertEquals(2, count($results), "Wrong filtered results");

  }

	public function testLogbookDateSort() {
		// Sort logbook by startdate ascending by default
		$results = $this->query->logbookQuery($this->rootNode,["properties" => ["type" => "Timesorter"] ])->getResult();
		$this->assertEquals(4, count($results), "Wrong results TestDateSort");
		$this->assertEquals("Task 3", $results[0]->getNode()->getTitle(), "Wrong assigned testDateSort[0]");
		$this->assertEquals("Task 1", $results[1]->getNode()->getTitle(), "Wrong assigned testDateSort[1]");
		$this->assertEquals("Task 2", $results[2]->getNode()->getTitle(), "Wrong assigned testDateSort[2]");
		$this->assertEquals("Task 1", $results[3]->getNode()->getTitle(), "Wrong assigned testDateSort[3]");

	}

}
?>
