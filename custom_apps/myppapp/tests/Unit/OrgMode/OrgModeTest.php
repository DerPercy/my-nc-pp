<?php

namespace OCA\MyPPApp\Tests\Unit\OrgMode;

require_once __DIR__.'/../../../lib/modules/OrgMode/Parser.php';


class OrgModeTest extends \PHPUnit\Framework\TestCase {
  public function setUp():void {
  }
  public function testOrgMode() {
    $parser = new \My\OrgMode\Parser();

    $this->assertTrue($parser->index());
  }
}
