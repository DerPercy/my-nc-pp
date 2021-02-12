<?php
namespace My\OrgMode;

class Node {
  private $subNodes = array();
  private $parentNode = null;
  private $title = null;
  private $properties = array(); //[["name","value"],...]
  private $logbook;

  function __construct(string $raw, string $title = ""){
    $this->title = $title;
    $this->logbook = new NodeLogbook();
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
  public function getProperty(string $propName,bool $incInherited = False ){
    foreach ($this->properties as &$prop) {
      if($prop["name"] == $propName){
        return $prop["value"];
      }
    }
    if($incInherited && $this->parentNode != null){
      return $this->parentNode->getProperty($propName,$incInherited);
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

  public function getLogbook():NodeLogbook{
    return $this->logbook;
  }
}

class NodeLogbook {
  private $entries = array();
  public function getEntries(){
    return $this->entries;
  }
  public function addEntry(array $entry){
    $nbEntry = new NodeLogbookEntry($entry);
    array_push($this->entries,$nbEntry);
  }
}
class NodeLogbookEntry {
  private $data;
  function __construct(array $data){
    $this->data = $data;
  }

  public function getStartDate(string $format){
    return date_format($this->data["start"], $format);
  }
  public function getEndDate(string $format){
    return date_format($this->data["end"], $format);
  }
  public function getDuration(){
    return $this->data["duration"];
  }
}
?>
