<?php
namespace My\OrgMode;

require_once __DIR__.'/Stream.php';


use My\OrgMode\Stream;

class Lexer {
  private Stream $stream;
  private $matches = array();

  function __construct(Stream $stream){
    $this->stream = $stream;
  }
  public function hasNext(){
    return $this->stream->hasNextLine();
  }
  public function getNext():LexerToken{
    $line = $this->stream->getNextLine();
    //print($line);
    if($this->isHeaderToken($line)){
      //print_r($this->matches);
      return new LexerTokenHeader($line,$this->matches[2],strlen($this->matches[1]));
    }
    return new LexerToken($line,LexerToken::TYPE_UNKNOWN);
    //return ["type" => "dummy"];
  }

  // Checks

  protected function isHeaderToken($line){
    return $this->checkLine($line,"/^(\*+)\s+(.*)$/"); // m[1] => level, m[2] => content
  }

  protected function checkLine($line,$regEx){
    if(!preg_match($regEx, $line, $this->matches)){
      return false;
    }
    return true;
  }

}


class LexerToken {
  const TYPE_UNKNOWN = 0;
  const TYPE_HEADER = 1;

  private $type;
  function __construct(string $raw, int $type){
    $this->type = $type;
  }
  public function getType():int {
    return $this->type;
  }
}

class LexerTokenHeader extends LexerToken {
  private $level;
  function __construct(string $raw, string $title, int $level){
     parent::__construct($raw,LexerToken::TYPE_HEADER);
     $this->level = $level;
  }

  function getLevel():int{
    return $this->level;
  }
}

?>
