<?php
namespace My\OrgMode;

class Node {
  private $subNodes = array();
  private $parentNode = null;
  private $title = null;
  private $properties = array(); //[["name","value"],...]
  private $logbook;
  private $data;

  function __construct(string $raw, string $title = "", array $data = []){
    $this->title = $title;
    $this->logbook = new NodeLogbook($this);
    $this->data = $data;
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

  public function getTodoFlag(){
		if(!array_key_exists("todoflag",$this->data)){
			return null;
		}
    return $this->data["todoflag"];
  }
  public function getTags() {
    return $this->data["tags"];
  }
  public function getPriority() {
		if(!array_key_exists("prio",$this->data)){
			return null;
		}
    return $this->data["prio"];
  }
}

class NodeLogbook {
  private $entries = array();
  private $node = null;
  function __construct(Node $node){
    $this->node = $node;
  }
  public function getEntries(){
    return $this->entries;
  }
  public function addEntry(array $entry){
    $nbEntry = new NodeLogbookEntry($this->node, $entry);
    array_push($this->entries,$nbEntry);
  }
}
class NodeLogbookEntry {
  private $data;
  private $node;
  function __construct(Node $node, array $data){
    $this->data = $data;
    $this->node = $node;
  }

  public function getStartDate(string $format){
    return date_format($this->data["start"], $format);
  }
	public function getStartDateObject(){
    return $this->data["start"];
  }
  public function getEndDate(string $format){
    return date_format($this->data["end"], $format);
  }
	public function getEndDateObject(){
    return $this->data["end"];
  }
  public function getNode(){
    return $this->node;
  }

  private function minutesToUI(int $time, $format = '%01d:%02d'):string {
    if ($time < 1) {
        return '00:00';
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
  }
  public function getDuration(){
    return $this->data["duration"];
  }
  public function getUIDuration(){
    return $this->minutesToUI($this->getDuration());
  }
  public function getPause():int{
    $dateInterval = $this->data["end"]->diff($this->data["start"]);
    $daysDiff = (int)$dateInterval->format("%a");
    $hoursDiff = (int)$dateInterval->format("%h");
    $minDiff = (int)$dateInterval->format("%i");
    $diff = $mindiff + ($hoursDiff * 60) + ($daysDiff * 24 * 60 ) - $this->getDuration();;
    return $diff;
  }
  public function getUIPause(){
    return $this->minutesToUI($this->getPause());
  }


}
?>
