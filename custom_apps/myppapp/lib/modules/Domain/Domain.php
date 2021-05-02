<?php
namespace My\Domain;

//print_r($json);

class Domain {
	private $json;
	function __construct(){
		$str = file_get_contents(__DIR__.'/settings.json', true);
		$this->json = json_decode($str, true);
  }

	public function getEntities() {
		$entities = $this->json["entities"];
		foreach ($entities as $key => &$entity) {
			$entity = $this->convEntityForOutput($entity,$key);
		}
		return $entities;
	}

	public function getEntity($type) {
		$entities = $this->json["entities"];
		foreach ($entities as $key => &$entity) {
			if($key == $type){
				return $this->convEntityForOutput($entity,$key);
			}
		}
		return null;
	}

	public function getFromRelations($type){
		$relations = [];
		foreach ($this->json["relations"] as $rKey => $relation) {
			if($relation["from"] == $key) { // current entity is in from relation
				array_push($relations,$key);
			}
		}
		return $relations;
	}
	// Utils
	private function convEntityForOutput($entity,$key) {
		$entity["key"] = $key;
		$entity["relations"] = [];
		// Name
		if(!isset($entity["name"])) {
			$entity["name"] = $key;
		}
		foreach ($this->json["relations"] as $rKey => $relation) {
			if($relation["from"] == $key) { // current entity is in from relation
				$entity["relations"][$rKey."From"] = [];
				$entity["relations"][$rKey."From"]["destination"] = $relation["to"];
				if(in_array($relation["type"],["1-n","m-n"])){
					$entity["relations"][$rKey."From"]["multiValue"] = true;
				} else {
					$entity["relations"][$rKey."From"]["multiValue"] = false;
				}

			}
			if($relation["to"] == $key) { // current entity is in from relation
				$entity["relations"][$rKey."To"] = [];
				$entity["relations"][$rKey."To"]["destination"] = $relation["from"];
				if(in_array($relation["type"],["m-1","m-n"])){
					$entity["relations"][$rKey."To"]["multiValue"] = true;
				} else {
					$entity["relations"][$rKey."To"]["multiValue"] = false;
				}
			}
		}
		return $entity;
	}
}
?>
