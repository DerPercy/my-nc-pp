<?php
namespace OCA\MyPPApp\Db;

use OCP\IDBConnection;
use OCP\AppFramework\Db\QBMapper;

class RelationMapper extends QBMapper {
    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'myppapp_relations', Relation::class);
    }

		public function deleteRelation($entityid, $relationName) {
			$result = $this->findRelations($relationName,$entityid);
			if(!isset($result)){
				return;
			}
			for($i=0 ; $i < count($result); $i++){
				$this->delete($result[$i]);
			}
		}


		public function setRelation($entityid, $relationName, $targetID) {
			$dbEntity = new Relation();
			if($this->isFromRelation($relationName) == true){
				$dbEntity->setEntityFrom($entityid);
				$dbEntity->setEntityTo($targetID);
			}
			if($this->isToRelation($relationName) == true){
				$dbEntity->setEntityTo($entityid);
				$dbEntity->setEntityFrom($targetID);
			}
			$dbEntity->setRelationtype($this->getInternalRelationName($relationName));
			$this->insert($dbEntity);
		}

		public function findRelations(string $relationName,$entityId) {
        $qb = $this->db->getQueryBuilder();
				$fieldQuery = "";
				$isToRelation = true;

				if($this->isFromRelation($relationName)){
					$fieldQuery = 'entity_from';
					$isToRelation = false;
				}elseif ($this->isToRelation($relationName)) {
					$fieldQuery = 'entity_to';
				}
				$relationName = $this->getInternalRelationName($relationName);
				$where = $qb->expr()->andx();
				$where->add($qb->expr()->eq($fieldQuery, $qb->createNamedParameter($entityId)));
				$where->add($qb->expr()->eq('relationtype', $qb->createNamedParameter($relationName)));


        $qb->select('*')
           ->from($this->getTableName())
           ->where($where);

				return $this->findEntities($qb);
		}


		public function findRelationIDs(string $relationName,$entityId) {
        $retData = [];
				$result = $this->findRelations($relationName,$entityId);
				if(!isset($result)){
					return $retData;
				}
				for($i=0 ; $i < count($result); $i++){
					if($this->isToRelation($relationName)) {
						array_push($retData, (int)$result[$i]->getEntityFrom());
					} else{
						array_push($retData, (int)$result[$i]->getEntityTo());
					}
				}

				return $retData;
		}

		// ========== Utils ==========
		private function isFromRelation($relationName){
			if(substr($relationName,-4) === 'From'){
				return true;
			}
			return false;
		}

		private function isToRelation($relationName){
			if (substr($relationName,-2) === 'To') {
				return true;
			}
			return false;
		}

		private function getInternalRelationName($relationName) {
			if($this->isFromRelation($relationName) === true){
				return substr($relationName, 0, -4);
			}
			if($this->isToRelation($relationName) === true){
				return substr($relationName, 0, -2);
			}
			return $relationName;
		}
}
