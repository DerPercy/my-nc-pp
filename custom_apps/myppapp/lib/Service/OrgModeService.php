<?php
namespace OCA\MyPPApp\Service;

class OrgModeService {
	public function __construct(){
	}

	public function nodeToOutput($result, $recursive = false) { // OrgMode Node
		$node = [];
		// >> Parse todo.txt
		// Done
		if($result->getTodoFlag() == null) {
			$node["isTodo"] = false;
			$node["todoFlag"] = "";
		} else {
			$node["isTodo"] = true;
			$node["todoFlag"] = $result->getTodoFlag();
		}
		if($result->getTodoFlag() == 'DONE'){
			$node["done"] = true;

		}else{
			$node["done"] = false;
		}
		// Priority
		$node["prio"] = $result->getPriority();

		$node["name"] = mb_convert_encoding($result->getTitle(), 'UTF-8', 'UTF-8');
		$node["customer"] = mb_convert_encoding($result->getProperty("CUSTOMER",true), 'UTF-8', 'UTF-8');
		$node["project"] = mb_convert_encoding($result->getProperty("PROJECT",true), 'UTF-8', 'UTF-8');

		// TodoChangelog
		$tdChangelog = $result->getTodoChangelog();
		$node["todoChangelog"] = array();
		foreach ($tdChangelog as $td) {
			$tdcl = [];
			$tdcl["stateTo"] = $td->stateTo;
			$tdcl["comments"] = array();
			foreach ($td->comments as $comment) {
				array_push($tdcl["comments"],mb_convert_encoding($comment, 'UTF-8', 'UTF-8'));
			}
			array_push($node["todoChangelog"],$tdcl);
		}

		// Content
		$node["content"] = [];
		foreach ($result->getContent() as &$contentLine) {
			array_push($node["content"],mb_convert_encoding($contentLine, 'UTF-8', 'UTF-8'));
		}

		// Recursive
		if($recursive == true){
			$node["children"] = [];
			foreach ($result->getSubNodes() as &$resSubnode) {
				$subResult = $this->nodeToOutput($resSubnode,$recursive);
				array_push($node["children"],$subResult);
			}
		}
		return $node;
	}

	public static function mergeTimesheet(array $timesheet) { // Merge entries with the same date, project and customer
		for ($i = 0; $i < count($timesheet); $i++) {
			for ($k = $i+1; $k < count($timesheet); $k++) {
				// Check if lines matches
				if($timesheet[$i][0] == $timesheet[$k][0] && $timesheet[$i][7] == $timesheet[$k][7] && $timesheet[$i][8] == $timesheet[$k][8]){
					// Merge Tasks
					$timesheet[$i][2] .= ','.$timesheet[$k][2];

					$stMin1 = OrgModeService::hhmmToMinutes($timesheet[$i][3]);
					$stMin2 = OrgModeService::hhmmToMinutes($timesheet[$k][3]);
					$etMin1 = OrgModeService::hhmmToMinutes($timesheet[$i][4]);
					$etMin2 = OrgModeService::hhmmToMinutes($timesheet[$k][4]);
					$durMin1 = OrgModeService::hhmmToMinutes($timesheet[$i][6]);
					$durMin2 = OrgModeService::hhmmToMinutes($timesheet[$k][6]);

					// check lowest starttime
					if($stMin2 < $stMin1) {
						$stMin = $stMin2;
						$timesheet[$i][3] = $timesheet[$k][3];
					}else {
						$stMin = $stMin1;
					}

					// Check highest endtime
					if($etMin2 > $etMin1) {
						$etMin = $etMin2;
						$timesheet[$i][4] = $timesheet[$k][4];
					}else {
						$etMin = $etMin1;
					}

					// Calcualte duration
					$duration = $durMin1 + $durMin2;
					$timesheet[$i][6] = OrgModeService::minutesToUI($duration);

					// Calculate pause
					$pause = $etMin - $stMin - $duration;
					$timesheet[$i][5] = OrgModeService::minutesToUI($pause);

					array_splice($timesheet, $k, 1);
					$k--;
				}
			}
		}
		return $timesheet;
	}

	private static function hhmmToMinutes($hhmm) {
		sscanf($hhmm, "%d:%d", $hours, $minutes);
		return ($hours * 60) + $minutes;
	}

	static public function minutesToUI(int $time, $format = '%01d:%02d'):string {
    if ($time < 1) {
        return '00:00';
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
  }
}
?>
