<?php
namespace OCA\MyPPApp\Db;

use OCP\IDBConnection;
use OCP\AppFramework\Db\QBMapper;

class RelationMapper extends QBMapper {
    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'myppapp_relations', Relation::class);
    }

		public function findRelationIDs(string $relationName,$entityId) {
        $qb = $this->db->getQueryBuilder();
				$fieldQuery = "";
				if(substr($relationName, 0, -4) === 'From'){
					$relationName = substr($relationName, 0, -4);
					$fieldQuery = 'entity_from';
				}elseif (substr($relationName, 0, -2) === 'To') {
					$relationName = substr($relationName, 0, -2);
					$fieldQuery = 'entity_to';
				}
				$where = $qb->expr()->andx();
				$where->add($qb->expr()->eq($fieldQuery, $qb->createNamedParameter($entityId)));
				$where->add($qb->expr()->eq('relationtype', $qb->createNamedParameter($relationName)));


        $qb->select('*')
           ->from($this->getTableName())
           ->where($where);

        return $this->findEntities($qb);
    }
}
