<?php
namespace My\OrgMode;

require_once __DIR__.'/Node.php';

use My\OrgMode\Node;


class Query {
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
      $result->addLogbookNode($logbookEntry);
    }
    // Scan for children
    foreach ($node->getSubNodes() as &$subnode) {
      $this->logbookQuery($subnode,$query,$result);
    }
    return $result;
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
}
?>
