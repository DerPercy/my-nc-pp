<?php
namespace OCA\MyPPApp\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class MyEntity extends Entity implements JsonSerializable {

    protected $entitytype;
    protected $jsonData;
    protected $userId;
		protected $computed = [];
		protected $relations = [];

    public function __construct() {
        $this->addType('id','integer');
    }

		public function getEntityID(){
			return $this->id;
		}
		public function getEntityType() {
			return $this->entitytype;
		}
		public function addComputedValue($name,$value){
			$this->computed[$name] = $value;
		}
		public function addRelation($name,$value){
			$this->relations[$name] = $value;
		}

    public function jsonSerialize() {
			$data = json_decode($this->jsonData,true);
			$name = $this->id;
			if(isset($data["name"])){
				$name = $data["name"];
			}
        return [
            'id' => $this->id,
						'name' => $name,
            'entitytype' => $this->entitytype,
            'data' => $data,
						'computed' => $this->computed,
						'relations' => $this->relations
        ];
    }
}
