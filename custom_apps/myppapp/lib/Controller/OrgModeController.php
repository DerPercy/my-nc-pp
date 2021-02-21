<?php
namespace OCA\MyPPApp\Controller;



use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\Files\IRootFolder;

use OCA\MyPPApp\Service\OrgModeService;


require_once __DIR__."/../modules/OrgMode/Parser.php";
require_once __DIR__."/../modules/OrgMode/Query.php";
use My\OrgMode\Parser;
use My\OrgMode\Query;

class OrgModeController extends \OCP\AppFramework\ApiController {
	private $userId;
	private $rootFolder;

	public function __construct($AppName, IRequest $request, IRootFolder $rootFolder,$UserId){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
		$this->rootFolder = $rootFolder;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function getHash($file) {
		$response = [];
		try {
			$file = $this->rootFolder->get($this->userId.'/files/'.$file);
			$response["hash"] = $file->hash("MD5");
		} catch (\OCP\Files\NotFoundException $e) {
		}
		return $response;
	}
	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function getDetails($file) {
		$response = [];
		$response["nodes"] = [];
		try {
			$file = $this->rootFolder->get($this->userId.'/files/'.$file);
			$orgContent = $file->getContent();
			$response["hash"] = $file->hash("MD5"); // To check if file changed

			$parser = new \My\OrgMode\Parser();
			$rootNode = $parser->parseString($orgContent);

			$query = new \My\OrgMode\Query();
			$results = $query->query($rootNode)->getResult();

			foreach ($results as &$result) {
				$node = [];
				// >> Parse todo.txt
				// Done
				if($result->getTodoFlag() == null) {
					$node["isTodo"] = false;
				} else {
					$node["isTodo"] = true;
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


				array_push($response["nodes"],$node);
			}

			// ProjectTree
			$pTree = $query->propertyTree($rootNode,["CUSTOMER","PROJECT"]);
			$response["ptree"] = $pTree->serialize();


		} catch (\OCP\Files\NotFoundException $e) {
		}
		return $response;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function getTasks($file) {
		$tasks = [];

		$tasks["path"] = $file;
		$taskdata = [];
		try {
			$orgContent = $this->rootFolder->get($this->userId.'/files/'.$file)->getContent();
			$parser = new \My\OrgMode\Parser();
			$rootNode = $parser->parseString($orgContent);

			$query = new \My\OrgMode\Query();
			$results = $query->query($rootNode)->getResult();

			foreach ($results as &$result) {
				$task = [];
				// >> Parse todo.txt
				// Done
				if($result->getTodoFlag() == null) {
					continue;
				}
				if($result->getTodoFlag() == 'DONE'){
					$task["done"] = true;
				}else{
					$task["done"] = false;
				}
				// Priority
				$task["prio"] = $result->getPriority();

				// << Parse todo.txt
				$task["name"] = mb_convert_encoding($result->getTitle(), 'UTF-8', 'UTF-8');
				$task["customer"] = mb_convert_encoding($result->getProperty("CUSTOMER",true), 'UTF-8', 'UTF-8');
				$task["project"] = mb_convert_encoding($result->getProperty("PROJECT",true), 'UTF-8', 'UTF-8');


				array_push($taskdata,$task);
			}
		} catch (\OCP\Files\NotFoundException $e) {
		}
		$tasks["tasks"] = $taskdata;
		return $tasks;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function getLogbook($file) {
		$orgContent = $this->rootFolder->get($this->userId.'/files/'.$file)->getContent();
		$parser = new \My\OrgMode\Parser();
		$rootNode = $parser->parseString($orgContent);

		$query = new \My\OrgMode\Query();
		$results = $query->logbookQuery($rootNode,[])->getResult();
		$returnData = [];
		$dayEvents = [];
		foreach ($results as &$result) {
			$resultSerialized = [
				"title" => mb_convert_encoding($result->getNode()->getTitle(), 'UTF-8', 'UTF-8'),
				"start" => ( $result->getStartDateObject()->getTimestamp() * 1000 ),
				"end" => ( $result->getEndDateObject()->getTimestamp() * 1000 )
			];
			$dayEventKey = $result->getStartDate("Ymd");
			$durationInMin = $result->getDuration();
			if(array_key_exists($dayEventKey, $dayEvents)){
				$dayEvents[$dayEventKey]["duration"] += $durationInMin;
			}else {
				$dayEvents[$dayEventKey] = [
					"duration" => $durationInMin,
					"start" => ( $result->getStartDateObject()->getTimestamp() * 1000 ),
					"end" => ( $result->getEndDateObject()->getTimestamp() * 1000 )
				];
			}
			array_push($returnData,$resultSerialized);
		}
		foreach ($dayEvents as $key => $dayEvent){
			$dayEventSerialized = [
				"title" => OrgModeService::minutesToUI($dayEvent["duration"]),
				"start" => $dayEvent["start"],
				"end" => $dayEvent["end"],
				"allDay" => True
			];
			array_push($returnData,$dayEventSerialized);
		}
		return new DataResponse($returnData);
	}

  /**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function createTimesheet($file, $ort, $export, $jahr, $monat) {
		try {
			$orgContent = $this->rootFolder->get($this->userId.'/files/'.$file)->getContent();
			$parser = new \My\OrgMode\Parser();
	    $rootNode = $parser->parseString($orgContent);

			$query = new \My\OrgMode\Query();
			$results = $query->logbookQuery($rootNode,["month" => $monat, "year" => $jahr])->getResult();
			$csvData = [];
			foreach ($results as &$result) {
				$csvLine = [];
				array_push($csvLine,$result->getStartDate( "d.m.Y" )); // 1. Date
				array_push($csvLine,$ort); // 2. Ort
				try {
					array_push($csvLine,strval($result->getNode()->getTitle())); // 3. Task
				}catch(Exception $etwo){
					array_push($csvLine,"???");
				}
				array_push($csvLine,$result->getStartDate( "H:i" )); // 4. Start time
				array_push($csvLine,$result->getEndDate( "H:i" )); // 5. End time
				array_push($csvLine,$result->getUIPause( ));	// 6. pause
				array_push($csvLine,$result->getUIDuration( )); // 7. duration
				array_push($csvLine,$result->getNode( )->getProperty("CUSTOMER",true)); // 8. customer
				array_push($csvLine,$result->getNode( )->getProperty("PROJECT",true)); // 9. project

				array_push($csvData,$csvLine);
			}

			$csvData = OrgModeService::mergeTimesheet($csvData);

			$file = $this->rootFolder->newFile($this->userId.'/files/'.$export);
			$fp = $file->fopen("w");
			foreach ($csvData as $fields) {
				fputcsv($fp, $fields);
			}
			fclose($fp);


		} catch (\OCP\Files\NotFoundException $e) {
			return new DataResponse([ "File not found"]);
		}
		return new DataResponse(["count" => count($results)]);
	}
}
