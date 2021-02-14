<?php
namespace My\OrgMode;

require_once __DIR__.'/Node.php';

use My\OrgMode\Node;


class Query {
  public function logbookQuery(Node $node,QueryLogbookResult $result = null ):QueryLogbookResult{
    if($result == null){
      $result = new QueryLogbookResult();
    }
    foreach ($node->getLogbook()->getEntries() as &$logbookEntry) {
      $result->addLogbookNode($logbookEntry);
    }
    // Scan for children
    foreach ($node->getSubNodes() as &$subnode) {
      $this->logbookQuery($subnode,$result);
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
