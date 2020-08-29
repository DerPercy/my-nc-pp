<?php
namespace OCA\MyPPApp\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\Util;
use OCP\Files\IRootFolder;

use OCA\MyPPApp\Service\WikiService;

class WikiController extends \OCP\AppFramework\ApiController {
	private $userId;
	private $service;
	private $rootFolder;

	public function __construct($AppName, IRequest $request, WikiService $service, IRootFolder $rootFolder,$UserId){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
		$this->service = $service;
		$this->rootFolder = $rootFolder;
	}


	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function wikipageDetails(string $wikipageUrl, string $wikipageLink = ""){
		$data = [];
		$pageFile = $this->rootFolder->get($this->userId."/".$wikipageUrl);

		if($wikipageLink != ""){
			$pageFolder = $pageFile->getParent();
			$newPageFile = $pageFolder->get($wikipageLink);
			$pageFile = $newPageFile;
		}
		return new DataResponse($this->service->createWikiData($pageFile));
	}
	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function updateWikipage(string $wikipageUrl, string $wikipageContent, string $wikipageHash){
		$data = [];
		$data["status"] = "OK";
		$pageFile = $this->rootFolder->get($this->userId."/".$wikipageUrl);
		$pageFile->putContent($wikipageContent);

		$data["data"] = $this->service->createWikiData($pageFile);

		return new DataResponse($data);
	}

}
