<?php
namespace My\OrgMode;

class Node {
  private $subNodes = array();
  private $parentNode = null;
  private $title = null;
  private $properties = array(); //[["name","value"],...]
  function __construct(string $raw, string $title = ""){
    $this->title = $title;
  }
  public function isRootNode() {
    return true;
  }

  public function getTitle():string{
    return $this->title;
  }

  public function setProperties(array $props) {
    $this->properties = $props;
  }
  public function getProperty(string $propName):string{
    foreach ($this->properties as &$prop) {
      if($prop["name"] == $propName){
        return $prop["value"];
      }
    }
    return null;
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
