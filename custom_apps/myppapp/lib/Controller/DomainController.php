<?php
namespace OCA\MyPPApp\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\Files\IRootFolder;
use OCA\MyPPApp\Service\DomainService;


use OCA\MyPPApp\Db\MyEntity;
use OCA\MyPPApp\Db\EntityMapper;
use OCA\MyPPApp\Db\RelationMapper;



class DomainController extends \OCP\AppFramework\ApiController {
	private $service;
	private $userId;
	private $mapper;

	public function __construct($AppName, IRequest $request, DomainService $service, RelationMapper $relMapper, EntityMapper $mapper, IRootFolder $rootFolder,$UserId){
		parent::__construct($AppName, $request);
		$this->service = $service;
		$this->userId = $UserId;
		$this->mapper = $mapper;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function getEntityDefinitions() {
		return $this->service->getEntities();
	}

	/**
   * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function getEntities($entitytype) {
  	return new DataResponse($this->service->convEntityListToOutput($this->mapper->findAll($this->userId,$entitytype)));
  }

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function createEntity($entityname, $entitydata, $relations) {
		//$retData = [];
		//$retData["sName"] = $entityname;
		//$retData["sData"] = $entitydata;

		$jsonString = json_encode($entitydata);
		$dbEntity = new MyEntity();
		$dbEntity->setEntitytype($entityname);
		$dbEntity->setJsonData($jsonString);
		$dbEntity->setUserId($this->userId);

		return new DataResponse($this->mapper->insert($dbEntity));
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function updateEntity($entityid, $entitydata, $relations) {
		$retData = [];
		$retData["TODO"] = "true";
		$retData["entityid"] = $entityid;
		$retData["entitydata"] = $entitydata;
		$retData["relations"] = $relations;

		$entity = $this->mapper->getEntityByID($entityid);

		foreach ($entitydata as $key => $value) {
			$entity->setData($key,$value);
		}

		$this->service->handleRelations($entityid,$entity->getEntityType(),$relations);

		

		//$retData["sName"] = $entityname;
		//$retData["sData"] = $entitydata;

		//$jsonString = json_encode($entitydata);
		//$dbEntity = new MyEntity();
		//$dbEntity->setEntitytype($entityname);
		//$dbEntity->setJsonData($jsonString);
		//$dbEntity->setUserId($this->userId);

		return new DataResponse($this->mapper->update($entity));
		//return $retData;
	}


}
