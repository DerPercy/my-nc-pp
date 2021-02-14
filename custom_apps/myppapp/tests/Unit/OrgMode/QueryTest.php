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
    $results = $this->query->logbookQuery($this->rootNode)->getResult();
    $this->assertEquals(5, count($results), "Wrong results");
    $this->assertEquals("Header 1.1", $results[0]->getNode()->getTitle(), "Wrong assigned node");

    $results = $this->query->logbookQuery($this->rootNode,["month" => 2, "year" => 2021])->getResult();
    $this->assertEquals(2, count($results), "Wrong filtered results");

  }

}
?>
