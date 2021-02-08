<?php
namespace My\OrgMode;

class Node {
  private $subNodes = array();
  private $parentNode = null;
  function __construct(string $raw){

  }
  public function isRootNode() {
    return true;
  }

  public function setParentNode(Node $node){
    $this->parentNode = $node;
  }
  public function getParentNode(){
    return $this->parentNode;
  }
  public function addSubNode(Node $node) {
    array_push($this->subNodes,$node);
    $node->setParentNode($this);
  }
  public function getSubNodes(){
    return $this->subNodes;
  }

  public function getLevel():int {
    if($this->parentNode == null){
      return 0;
    }
    return $this->parentNode->getLevel() + 1;
  }
}
?>
