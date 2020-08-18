<?php
namespace OCA\MyPPApp\Service;

use Exception;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

//use OCA\NotesTutorial\Db\Note;
//use OCA\NotesTutorial\Db\NoteMapper;


class ProjectService {
  public function __construct(){
  }
  public function findAll(string $userId) {
    $data = [
      ["id" => 1, "name" => "Project 1", "id_customer" => 1, "budget" => 4800, "budget_left" => 2400 ], // 10 PT Budget => 5PT übrig
      ["id" => 2, "name" => "Project 2", "id_customer" => 1],
      ["id" => 3, "name" => "Project 3", "id_customer" => 1],
      ["id" => 4, "name" => "Project 4", "id_customer" => 2],
    ];
    return $data;
  }
 }
