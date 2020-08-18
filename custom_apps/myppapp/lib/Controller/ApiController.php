<?php
namespace OCA\MyPPApp\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\Util;

class ApiController extends \OCP\AppFramework\ApiController {
	private $userId;

	public function __construct($AppName, IRequest $request){
		parent::__construct($AppName, $request);
		//$this->userId = $UserId;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
   * (at)CORS <= deactivated
	 */
	public function dummy() {
    $data = [
      ["id" => 1, "name" => "Customer 1"],
      ["id" => 2, "name" => "Customer 2"],
      ["id" => 3, "name" => "Customer 3"],
      ["id" => 4, "name" => "Customer 4"],
    ];
    return new DataResponse($data);
	}

}
