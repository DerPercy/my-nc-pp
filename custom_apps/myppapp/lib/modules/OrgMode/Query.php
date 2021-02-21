<?php
namespace My\OrgMode;

require_once __DIR__.'/Node.php';

use My\OrgMode\Node;


class Query {

	public function propertyTree(Node $rootNode, array $proptree, PropertyTree $result = null):PropertyTree {
		if($result == null) {
			$result = new PropertyTree();
		}
		if(count($proptree) == 0){
			return $result;
		}
		$propName = $proptree[0];

		// Check rootNode
		$propValue = $rootNode->getProperty($propName);
		if($propValue != null){
			$subTree = $result->getOrCreateChild($propValue,$propName);
			$subArray = [];
			for($i = 1; $i<count($proptree);$i++){
				array_push($subArray,$proptree[$i]);
			}
			$this->propertyTree($rootNode,$subArray,$subTree);
		}
		foreach ($rootNode->getSubNodes() as &$subnode) {
			$this->propertyTree($subnode,$proptree,$result);
		}
		return $result;
	}
	public function query(Node $node, array $query = [], array $sort = [], QueryResult $result = null):QueryResult {
		if($result == null){
			$result = new QueryResult();
		}
		// Scan for children
    foreach ($node->getSubNodes() as &$subnode) {
			$result->addNode($subnode);
      $this->query($subnode,$query,$sort,$result);
    }
		return $result;
	}
  public function logbookQuery(Node $node,array $query = [], QueryLogbookResult $result = null ):QueryLogbookResult{
    if($result == null){
      $result = new QueryLogbookResult();
    }
    foreach ($node->getLogbook()->getEntries() as &$logbookEntry) {
      if(array_key_exists("month",$query)){
        $month = (int)$query["month"];
        $lbMonth = (int)$logbookEntry->getStartDate("m");
        if($lbMonth != $month){
          continue;
        }
      }
      if(array_key_exists("year",$query)){
        $year = (int)$query["year"];
        $lbYear = (int)$logbookEntry->getStartDate("Y");
        if($lbYear != $year){
          continue;
        }
      }
			if(array_key_exists("properties",$query)) {
				foreach ($query["properties"] as $key => $value) {
					if($logbookEntry->getNode()->getProperty($key) != $value){
						continue 2;
					}
				}
			}
      $result->addLogbookNode($logbookEntry);
    }
    // Scan for children
    foreach ($node->getSubNodes() as &$subnode) {
      $this->logbookQuery($subnode,$query,$result);
    }
		$result->sortByDate();
    return $result;
  }
}

class PropertyTree {
	private $propName = "";
	private $propValue;
	private $children = [];
	public function setPropertyName($propName){
		$this->propName = $propName;
	}

	public function children(){
		return $this->children;
	}

	public function setPropValue($pv){
		$this->propValue = $pv;
	}
	public function getPropValue():string{
		return $this->propValue;
	}
	public function getPropName():string{
		return $this->propName;
	}

	public function getOrCreateChild(string $propValue, string $propName):PropertyTree {
		foreach ($this->children as &$child) {
			if($child->getPropValue() == $propValue){
				return $child;
			}
		}
		$newProp = new PropertyTree();
		$newProp->setPropValue($propValue);
		$newProp->setPropertyName($propName);
		array_push($this->children,$newProp);
		return $newProp;
	}

	public function serialize(){
		$result = [];
		$result["name"] = mb_convert_encoding($this->propName, 'UTF-8', 'UTF-8');
		$result["value"] = mb_convert_encoding($this->propValue, 'UTF-8', 'UTF-8');
		$result["children"] = [];
		foreach ($this->children as &$child) {
			array_push($result["children"],$child->serialize());
		}
		return $result;

	}
}
class QueryResult {
	private $result = array();
	public function addNode(Node $node) {
		array_push($this->result,$node);
	}
	public function getResult() {
		return $this->result;
	}
}
class QueryLogbookResult {
  private $result = array();
  public function addLogbookNode(NodeLogbookEntry $logbookEntry){
    array_push($this->result,$logbookEntry);
  }
  public function getResult() {
    return $this->result;
  }
	public function dateCompare($a, $b) {
		$sdA = $a->getStartDateObject();
		$sdB = $b->getStartDateObject();
		if ($sdA == $sdB) {
			return 0;
		}
		if($sdA < $sdB) {
			return -1;
		} else {
			return 1;
		}
	}

	public function sortByDate(){
		usort($this->result, array($this, 'dateCompare'));
	}
}
?>
