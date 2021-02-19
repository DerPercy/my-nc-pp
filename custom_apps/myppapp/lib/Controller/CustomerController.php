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
	public function projectDetails(string $projectpath){
		$data =  $this->service->getProjectDetails($this->userId,$this->rootFolder,$projectpath);
		return new DataResponse($data);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function wikipageDetails(string $wikipagePath, string $wikipageLink){
		$data = [];
		$pageFile = $this->rootFolder->get($this->userId."/".$wikipagePath);
		$pageFolder = $pageFile->getParent();

		$newPageFile = $pageFolder->get($wikipageLink);

		$data["content"] = $newPageFile->getContent();
		$data["path"] = $newPageFile->getInternalPath();
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
