<?php
namespace OCA\MyPPApp\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\Util;

use OCA\MyPPApp\Service\ProjectService;

class ProjectController extends \OCP\AppFramework\ApiController {
	private $userId;
	private $service;

	public function __construct($AppName, IRequest $request, ProjectService $service,$UserId){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
		$this->service = $service;
	}

  /**
   * @NoAdminRequired
	 * @NoCSRFRequired
   */
  public function index() {
			return new DataResponse($this->service->findAll($this->userId));
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
