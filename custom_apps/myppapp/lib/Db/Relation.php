<?php
namespace OCA\MyPPApp\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class Relation extends Entity implements JsonSerializable {

    public function __construct() {
        $this->addType('id','integer');
    }

    public function jsonSerialize() {
        return [];
    }
}
