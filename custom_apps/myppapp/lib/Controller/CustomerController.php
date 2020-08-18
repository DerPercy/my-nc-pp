<?php
namespace OCA\MyPPApp\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\Util;
use OCP\Files\IRootFolder;

use OCA\MyPPApp\Service\CustomerService;

class CustomerController extends \OCP\AppFramework\ApiController {
	private $userId;
	private $service;
	private $rootFolder;

	public function __construct($AppName, IRequest $request, CustomerService $service, IRootFolder $rootFolder,$UserId){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
		$this->service = $service;
		$this->rootFolder = $rootFolder;
	}

  /**
   * @NoAdminRequired
	 * @NoCSRFRequired
   */
  public function index() {
			return new DataResponse($this->service->getCustomerOverview($this->userId,$this->rootFolder));
			/*$nodeArray = $this->rootFolder->get($this->userId.'/files')->getDirectoryListing();
			$fileData = [];
			foreach ($nodeArray as &$node) {
    		array_push($fileData,$node->getName());
			}
			return new DataResponse($fileData);*/
			//return new DataResponse($this->service->findAll($this->userId));
  }
	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function timetracking() {
		$data = $this->service->getTimetracking($this->userId,$this->rootFolder);
		return new DataResponse($data);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function timesheet() {
		$data = $this->service->getTimetracking($this->userId,$this->rootFolder);
		$csvData = [];
		foreach ($data as &$ttEntry) {
			$csvList = [];


			array_push($csvList,date ( "d.m.Y" , $ttEntry["start"] )); // 1. Date
			array_push($csvList,""); // 2. Empty
			array_push($csvList,$ttEntry["activity"]); // 3. Task
			array_push($csvList,date ( "H:i" , $ttEntry["start"] )); // 4. Hour start
			array_push($csvList,date ( "H:i" , $ttEntry["end"] )); // 5. Hour end
			$seconds = round($ttEntry["pause"]);
			$output = sprintf('%02d:%02d', ($seconds/ 3600),($seconds/ 60 % 60));
			array_push($csvList,$output ); // 6. Pause
			array_push($csvList,$ttEntry["customer"] ); // 7. Customer
			array_push($csvList,$ttEntry["project"] ); // 8. Project

			array_push($csvData,$csvList);
		}
		$rootFolder = $this->rootFolder->get($this->userId.'/files/myppapp');
		$file = $rootFolder->newFile("ts.csv");
		$fp = $file->fopen("w");
		/*$list = array (
    	array('aaa', 'bbbneu', 'ccc', 'dddd'),
    	array('123', '456', '789'),
    	array('"aaa"', '"bbb"')
		);*/
		foreach ($csvData as $fields) {
    	fputcsv($fp, $fields);
		}

		fclose($fp);
	}
	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function projectDetails(string $projectpath){
		$data =  $this->service->getProjectDetails($this->userId,$this->rootFolder,$projectpath);
		return new DataResponse($data);
	}

  /**
   * @NoAdminRequired
   *
   * @param int $id
   */
  public function show(int $id) {
      // empty for now
  }

  /**
   * @NoAdminRequired
   *
   * @param string $title
   * @param string $content
   */
  public function create(string $title, string $content) {
      // empty for now
  }

  /**
   * @NoAdminRequired
   *
   * @param int $id
   * @param string $title
   * @param string $content
   */
  public function update(int $id, string $title, string $content) {
      // empty for now
			return new DataResponse([]);
  }

  /**
   * @NoAdminRequired
   *
   * @param int $id
   */
  public function destroy(int $id) {
      // empty for now
  }
}
