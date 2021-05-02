<?php
namespace OCA\MyPPApp\Service;

require_once __DIR__."/../modules/Domain/Domain.php";

use OCA\MyPPApp\Db\Relation;
use OCA\MyPPApp\Db\RelationMapper;


class DomainService {
	private $domain;
	private $relationMapper;
	public function __construct(RelationMapper $relationMapper){
		$this->domain = new \My\Domain\Domain();
		$this->relationMapper = $relationMapper;
	}

	public function getEntities(){
		return $this->domain->getEntities();
	}

	public function convEntityListToOutput($entityList){
		for ($i = 0; $i < count($entityList); $i++) {
			$this->convEntityToOutput($entityList[$i]);
		}
		return $entityList;
	}
	public function convEntityToOutput($entity){
		// Get relations
		$entitytype = $entity->getEntityType();
		$entityDef = $this->domain->getEntity($entitytype);
		foreach ($entityDef["relations"] as $Key => $relation) {
			$entity->addRelation($Key, $this->relationMapper->findRelationIDs($Key,$entity->getEntityID()));
		}
		$entity->addComputedValue("test","value");
	}

}
