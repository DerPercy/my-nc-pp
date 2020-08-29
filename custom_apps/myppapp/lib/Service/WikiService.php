<?php
namespace OCA\MyPPApp\Service;

use Exception;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

//use OCA\NotesTutorial\Db\Note;
//use OCA\NotesTutorial\Db\NoteMapper;
use OCP\Files\IRootFolder;
use OCP\Files\File;

class WikiService {
	function createWikiData(File $file){
		$data = [];
		$pageFolder = $file->getParent();

		//$newPageFile = $pageFolder->get($wikipageLink);

		$data["content"] = $file->getContent();
		$data["folder"] = $pageFolder->getInternalPath();
		$data["url"] = $file->getInternalPath();
		$data["hash"] = $file->hash("MD5");

		return $data;
	}
}
