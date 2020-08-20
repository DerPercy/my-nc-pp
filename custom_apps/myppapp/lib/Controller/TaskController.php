<?php
namespace OCA\MyPPApp\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\Util;
use OCP\Files\IRootFolder;

use OCA\MyPPApp\Service\TaskService;

class TaskController extends \OCP\AppFramework\ApiController {
	private $userId;
	private $service;
	private $rootFolder;

	public function __construct($AppName, IRequest $request, TaskService $service, IRootFolder $rootFolder,$UserId){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
		$this->service = $service;
		$this->rootFolder = $rootFolder;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function taskList(string $folderPath){
		$tasks = [];
		try {
			$taskFile = $this->rootFolder->get($this->userId.'/'.$folderPath.'/todo.txt');
			$taskContent = $taskFile->getContent();
			$separator = "\r\n";
			$line = strtok($taskContent, $separator);
			$linecounter = 1;
			while ($line !== false) {
				$task = [];
				$task["name"] = $line;
				$task["file"] = $taskFile->getInternalPath();
				$task["line"] = $linecounter;
				$task["hash"] = $taskFile->hash("MD5");
				array_push($tasks,$task);
    		$line = strtok( $separator );
			}
		} catch (\OCP\Files\NotFoundException $e) {
		}
		return $tasks;
	}

}
