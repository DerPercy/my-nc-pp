<?php

namespace OCA\MyPPApp\Tests\Unit\OrgMode;

require_once __DIR__.'/../../../lib/modules/OrgMode/Parser.php';


class OrgModeTest extends \PHPUnit\Framework\TestCase {
  private $rootNode = null;
  public function setUp():void {
    $filecontent = $file = file_get_contents(__DIR__.'/test.org');
    //print($filecontent);
    $parser = new \My\OrgMode\Parser();
    $this->rootNode = $parser->parseString($filecontent);
  }
  public function testSubNodes() {
    $this->assertEquals(2, count($this->rootNode->getSubNodes()), "Wrong subnodes at Level 1");
    $this->assertEquals(1, count($this->rootNode->getSubNodes()[0]->getSubNodes()), "Wrong subnodes for Node 1");
  }

  public function testTitle() {
    $this->assertEquals("Header 1", $this->rootNode->getSubNodes()[0]->getTitle(), "Wrong determination of title");
  }

  public function testProperties() {
    $this->assertEquals("Value 2", $this->rootNode->getSubNodes()[0]->getProperty("PROP2"), "Wrong determination of property");
  }

}
