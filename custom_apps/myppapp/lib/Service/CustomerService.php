<?php
namespace OCA\MyPPApp\Service;

use Exception;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

//use OCA\NotesTutorial\Db\Note;
//use OCA\NotesTutorial\Db\NoteMapper;
use OCP\Files\IRootFolder;
use OCP\Files\Folder;

class CustomerService {

    private $mapper;

    /*public function __construct(NoteMapper $mapper){
        $this->mapper = $mapper;
    }*/
    public function __construct(){
    }
		public function getCustomerOverview(string $userId,IRootFolder $rootFolder){
			$customers = [];
			try {
				$customerNodes = $rootFolder->get($userId.'/files/myppapp')->getDirectoryListing();
				foreach ($customerNodes as &$node) {
					if ($node instanceof Folder) {
						array_push($customers,$this->getFolderDetails($node));
					}
				}
			} catch (\OCP\Files\NotFoundException $e) {
				return $customers;
			}
			return $customers;
		}

		public function getFolderDetails(Folder $folder){
			$folderData = [];
			$folderData["name"] = $folder->getName();
			$folderData["mtime"] = $folder->getMTime() * 1000; // Last Modfied Time
			// >> Settings
			try {
				$json = $folder->get('settings.json')->getContent();
				$parsed = json_decode($json);
				$folderData["settings"] = $parsed;
			} catch (\OCP\Files\NotFoundException $e) {
			}
			// << Settings

			// readme
			try {
				$readme = $folder->get('README.md');
				$folderData["readme"] = $readme->getInternalPath();
			} catch (\OCP\Files\NotFoundException $e) {
			}


			// Get Projects
			$projects = [];
			try {
				$projectNodes = $folder->get('projects')->getDirectoryListing();
				foreach ($projectNodes as &$node) {
					$project = [];
					$project["name"] = $node->getName();
					$project["path"] = $node->getInternalPath();

					// Timetracking
					/*$csv = [];
					$rows = str_getcsv ( $node->get('timetracking.csv')->getContent(),"\n");
					foreach ($rows as &$row) {
						$lineData = str_getcsv ($row);
						//array_push($lineData,strtotime($lineData[0]." ".$lineData[1]." -2 hours"));
						array_push($lineData,date_create_from_format ( "d.m.Y H:i" , $lineData[0]." ".$lineData[1])->getTimestamp());
						array_push($lineData,date_create_from_format ( "d.m.Y H:i" , $lineData[0]." ".$lineData[2])->getTimestamp());
						$time = explode(':', $lineData[3]);
						array_push($lineData,$time[0]*3600 + $time[1]*60);
						array_push($csv,$lineData);
					}
					$project["timetracking"] = $csv;*/
					array_push($projects,$project);
				}
			} catch (\OCP\Files\NotFoundException $e) {
			}
			$folderData["projects"] = $projects;

			return $folderData;
		}

		public function getTimetracking(string $userId,IRootFolder $rootFolder){
		/*	set_error_handler(function($errno, $errstr, $errfile, $errline ){
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
});*/
			$data = $this->getCustomerOverview($userId,$rootFolder);
			$timeTracking = [];
			foreach ($data as &$customer) {
				foreach ($customer["projects"] as &$project) {
					// Parsing Timetracking
					$csv = [];
					try {
						$rows = str_getcsv ( $rootFolder->get($userId.'/'.$project["path"].'/timetracking.csv')->getContent(),"\n");
						foreach ($rows as &$row) {
							$ttEntry = [];
							$lineData = str_getcsv ($row);
							// Getting Line Data
							if (false === $timestamp = date_create_from_format ( "d.m.Y H:i" , $lineData[0]." ".$lineData[1])) {
								$data = [];
								$data["message"] = "Error at ".$project["path"]." - row: ".$row;
								return $data;
    					}
							$ttEntry["start"] = $timestamp->getTimestamp();
							if (false === $timestamp = date_create_from_format ( "d.m.Y H:i" , $lineData[0]." ".$lineData[2])) {
								$data = [];
								$data["message"] = "Error at ".$project["path"]." - row: ".$row;
								return $data;
    					}

							$ttEntry["end"] = $timestamp->getTimestamp();
							$time = explode(':', $lineData[3]);
							$ttEntry["pause"] = $time[0]*3600 + $time[1]*60;
							$ttEntry["activity"] = $lineData[4];
							$ttEntry["project"] = $project["name"];
							$ttEntry["customer"] = $customer["name"];
							$ttEntry["path"] = $project["path"];
							array_push($timeTracking,$ttEntry);
						}
					} catch (\OCP\Files\NotFoundException $e) {
					/*} catch (\Exception $e) {
    				$data = [];
						$data["message"] = "Error at ".$project["path"];
						return $data;*/
					}
				}
			}

			// Sort Timetracking

			function build_sorter($key) {
    		return function ($a, $b) use ($key) {
        	return strnatcmp($a[$key], $b[$key]);
    		};
			}
			usort($timeTracking, build_sorter('start'));
			return $timeTracking;
		}

		public function getWikiPageData(string $userId,IRootFolder $rootFolder,string $projectPath, string $wikiPage = ""){
			$data = [];
			if($wikiPage == ""){
				$wikiPage = 'home.md';
			}
			try {

			} catch (\OCP\Files\NotFoundException $e) {
			}
			return $data;
		}

		// ========== PROJECTS ==========
		public function getProjectDetails(string $userId,IRootFolder $rootFolder,string $projectPath){
			$data = [];
			try {
				$projectFolder = $rootFolder->get($userId.'/'.$projectPath);
				$data["path"] = $projectPath;
				$data["name"] = $projectFolder->getName();
				// Wiki
				$wikiPage = $projectFolder->get("wiki/home.md");
				$data["wiki"] = [];
				$data["wiki"]["content"] = $wikiPage->getContent();
				$data["wiki"]["path"] = $wikiPage->getInternalPath();
			} catch (\OCP\Files\NotFoundException $e) {
			}
			return $data;
		}
		// => Not used

    public function findAll(string $userId) {
        //return $this->mapper->findAll($userId);
        $data = [
          ["id" => 1, "name" => "Customer 1"],
          ["id" => 2, "name" => $userId ],
          ["id" => 3, "name" => "Customer 3"],
          ["id" => 4, "name" => "Customer 4"],
        ];
        return $data;
    }

    private function handleException ($e) {
        if ($e instanceof DoesNotExistException ||
            $e instanceof MultipleObjectsReturnedException) {
            throw new NotFoundException($e->getMessage());
        } else {
            throw $e;
        }
    }

    public function find(int $id, string $userId) {
    /*    try {
            return $this->mapper->find($id, $userId);

        // in order to be able to plug in different storage backends like files
        // for instance it is a good idea to turn storage related exceptions
        // into service related exceptions so controllers and service users
        // have to deal with only one type of exception
        } catch(Exception $e) {
            $this->handleException($e);
        }*/
    }

    public function create(string $title, string $content, string $userId) {
  /*      $note = new Note();
        $note->setTitle($title);
        $note->setContent($content);
        $note->setUserId($userId);
        return $this->mapper->insert($note);*/
    }

    public function update(int $id, string $title, string $content, string $userId) {
    /*    try {
            $note = $this->mapper->find($id, $userId);
            $note->setTitle($t
            $note->setContent($content);
             return $this->mapper->update($note);
         } catch(Exception $e) {
             $this->handleException($e);
         }*/
     }

     public function delete(int $id, string $userId) {
      /*   try {
             $note = $this->mapper->find($id, $userId);
             $this->mapper->delete($note);
             return $note;
         } catch(Exception $e) {
             $this->handleException($e);
         }*/
     }

 }
