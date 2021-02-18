<?php
namespace OCA\MyPPApp\Service;

class OrgModeService {
	public function __construct(){
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
