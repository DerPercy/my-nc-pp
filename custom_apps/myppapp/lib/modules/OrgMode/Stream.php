<?php
namespace My\OrgMode;

class Stream {
  private $separator = "\r\n";
  private $lines;

  function __construct(string $content){
    $this->lines = [];
    $line = strtok($content, $this->separator);
    while ($line !== false) {
        array_push($this->lines, $line);
        $line = strtok( $this->separator );
    }
    //print_r($this->lines);
  }

  function hasNextLine(){
    return count($this->lines) > 0;
  }

  function getNextLine(){
    if(!$this->hasNextLine()){
      return false;
    }
    $line = $this->lines[0];
    $this->lines = array_slice($this->lines, 1);
    return $line;
  }

  function peekNextLine(){
    if(!$this->hasNextLine()){
      return false;
    }
    return $this->lines[0];
  }
}
?>
