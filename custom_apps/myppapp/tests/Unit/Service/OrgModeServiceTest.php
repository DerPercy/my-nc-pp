<?php
namespace OCA\MyPPApp\Tests\Unit\Service;

require_once(__DIR__."/../../../lib/Service/OrgModeService.php");
use PHPUnit_Framework_TestCase;

use OCA\MyPPApp\Service\OrgModeService;


class OrgModeServiceTest extends \PHPUnit\Framework\TestCase  {
	public function testMergeTimesheet() {
		$timesheet = [
			["15.02.2021","","Task 1","08:00","10:00","0:00","2:00","Customer 1","Project 1"],
			["15.02.2021","","Task 2","15:00","17:00","1:00","1:00","Customer 1","Project 1"],
			
			["16.02.2021","","Task 1","08:00","08:30","0:00","0:30","Customer 1","Project 1"],
			["16.02.2021","","Task a","08:30","14:00","1:00","4:30","Customer 1","Project 2"],
			["16.02.2021","","Task b","14:00","15:30","0:30","1:00","Customer 1","Project 2"],
			["16.02.2021","","Task 3","14:30","17:00","1:30","2:00","Customer 1","Project 1"]
		];

		$timesheet = OrgModeService::mergeTimesheet($timesheet);
		$this->assertEquals(3, count($timesheet), "Wrong merged results");
		$this->assertEquals("Task 1,Task 2", $timesheet[0][2], "Wrong merged tasks");
		$this->assertEquals("08:00", $timesheet[0][3], "Wrong starttime");
		$this->assertEquals("17:00", $timesheet[0][4], "Wrong endtime");
		$this->assertEquals("3:00", $timesheet[0][6], "Wrong duration");
		$this->assertEquals("6:00", $timesheet[0][5], "Wrong pause");

		$this->assertEquals("Task 1,Task 3", $timesheet[1][2], "Wrong merged tasks");
		$this->assertEquals("08:00", $timesheet[1][3], "Wrong starttime");
		$this->assertEquals("17:00", $timesheet[1][4], "Wrong endtime");
		$this->assertEquals("2:30", $timesheet[1][6], "Wrong duration");
		$this->assertEquals("6:30", $timesheet[1][5], "Wrong pause");

		$this->assertEquals("Task a,Task b", $timesheet[2][2], "Wrong merged tasks");
		$this->assertEquals("08:30", $timesheet[2][3], "Wrong starttime");
		$this->assertEquals("15:30", $timesheet[2][4], "Wrong endtime");
		$this->assertEquals("5:30", $timesheet[2][6], "Wrong duration");
		$this->assertEquals("1:30", $timesheet[2][5], "Wrong pause");

	}

}
?>
