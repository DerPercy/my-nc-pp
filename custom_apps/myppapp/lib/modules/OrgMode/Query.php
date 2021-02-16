<?php
namespace My\OrgMode;

require_once __DIR__.'/Node.php';

use My\OrgMode\Node;


class Query {
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
      if($query["month"]){
        $month = (int)$query["month"];
        $lbMonth = (int)$logbookEntry->getStartDate("m");
        if($lbMonth != $month){
          continue;
        }
      }
      if($query["year"]){
        $year = (int)$query["year"];
        $lbYear = (int)$logbookEntry->getStartDate("Y");
        if($lbYear != $year){
          continue;
        }
      }
			if($query["properties"]) {
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
