<?php

namespace OCA\MyPPApp\Tests\Unit\OrgMode;

require_once __DIR__.'/../../../lib/modules/OrgMode/Parser.php';


class OrgModeTest extends \PHPUnit\Framework\TestCase {
  public function setUp():void {
  }
  public function testOrgMode() {
    $filecontent = $file = file_get_contents(__DIR__.'/test.org');
    //print($filecontent);
    $parser = new \My\OrgMode\Parser();

    $rootNode = $parser->parseString($filecontent);

    $this->assertTrue($rootNode->isRootNode());
    $this->assertEquals(2, count($rootNode->getSubNodes()), "Wrong subnodes at Level 1");
    $this->assertEquals(1, count($rootNode->getSubNodes()[0]->getSubNodes()), "Wrong subnodes for Node 1");

  }
}
