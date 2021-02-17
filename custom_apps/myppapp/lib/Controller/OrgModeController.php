<?php
namespace OCA\MyPPApp\Controller;



use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\Files\IRootFolder;


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
	public function getNodes($file) {

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
		foreach ($results as &$result) {
			$resultSerialized = [
				"title" => mb_convert_encoding($result->getNode()->getTitle(), 'UTF-8', 'UTF-8'),
				"start" => ( $result->getStartDateObject()->getTimestamp() * 1000 ),
				"end" => ( $result->getEndDateObject()->getTimestamp() * 1000 )
			];
			array_push($returnData,$resultSerialized);
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
					array_push($csvLine,strval($result->getNode()->getTitle()));
				}catch(Exception $etwo){
					array_push($csvLine,"???");
				}
				array_push($csvLine,$result->getStartDate( "H:i" ));
				array_push($csvLine,$result->getEndDate( "H:i" ));
				array_push($csvLine,$result->getUIPause( ));
				array_push($csvLine,$result->getUIDuration( ));
				array_push($csvLine,$result->getNode( )->getProperty("CUSTOMER",true));
				array_push($csvLine,$result->getNode( )->getProperty("PROJECT",true));

				array_push($csvData,$csvLine);
			}

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