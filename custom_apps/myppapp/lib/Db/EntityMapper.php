<?php
namespace OCA\MyPPApp\Db;

use OCP\IDBConnection;
use OCP\AppFramework\Db\QBMapper;

class EntityMapper extends QBMapper {

    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'myppapp_entities', MyEntity::class);
    }

		public function findAll(string $userId,string $entitytype) {
        $qb = $this->db->getQueryBuilder();

				$where = $qb->expr()->andx();
				$where->add($qb->expr()->eq('user_id', $qb->createNamedParameter($userId)));
				$where->add($qb->expr()->eq('entitytype', $qb->createNamedParameter($entitytype)));


        $qb->select('*')
           ->from($this->getTableName())
           ->where($where);

        return $this->findEntities($qb);
    }
}
