<?php
namespace OCA\MyPPApp\Controller;



use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\Files\IRootFolder;

require_once __DIR__."/../modules/OrgMode/Stream.php";
require_once __DIR__."/../modules/OrgMode/Parser.php";
use My\OrgMode\Parser;

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
	public function createTimesheet($file, $ort, $export, $jahr, $monat) {
		try {
			$this->rootFolder->get($this->userId.'/files/'.$file)->getContent();

		} catch (\OCP\Files\NotFoundException $e) {
			return new DataResponse([ "File not found"]);
		}
		return new DataResponse([]);
	}
}
