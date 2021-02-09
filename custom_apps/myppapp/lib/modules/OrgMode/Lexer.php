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
      return new LexerTokenHeader($line,$this->matches[2],strlen($this->matches[1]));
    }
    if($this->isPropertyStartToken($line)){
      $lexerPropToken = new LexerPropertiesToken($line);
      $inProps = true;
      while($inProps){
        if(!$this->hasNext()){
          $inProps = false;
        }else{
          $propLine = $this->stream->getNextLine();
          if($this->isHeaderToken($propLine) || $this->isBlockEndToken($propLine)){
            $inProps = false;
          } else {
            if($this->isPropertyLineToken($propLine)){
              $lexerPropToken->addProperty($this->matches[1],$this->matches[2]);
              //print($this->matches[1]);
              //print($this->matches[2]);
            }
            //print($propLine);
          }
        }
      }
      return $lexerPropToken;
    }
    return new LexerToken($line,LexerToken::TYPE_UNKNOWN);
    //return ["type" => "dummy"];
  }

  // Checks

  protected function isHeaderToken($line){
    return $this->checkLine($line,"/^(\*+)\s+(.*)$/"); // m[1] => level, m[2] => content
  }
  protected function isPropertyStartToken($line){
    return $this->checkLine($line,"/^(\s*)(:PROPERTIES:)(\s*)$/"); // m[1] => level, m[2] => content
  }
  protected function isPropertyLineToken($line){
    return $this->checkLine($line,"/^\s*:([^:]*):\s*(.*)\s*$/"); // m[1] => level, m[2] => content
  }
  protected function isBlockEndToken($line){
    return $this->checkLine($line,"/^(\s*)(:END:)(\s*)$/"); // m[1] => level, m[2] => content
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
  const TYPE_PROPERTIES = 2;

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
  private $title;
  function __construct(string $raw, string $title, int $level){
     parent::__construct($raw,LexerToken::TYPE_HEADER);
     $this->level = $level;
     $this->title = $title;
  }

  function getTitle():string {
    return $this->title;
  }
  function getLevel():int{
    return $this->level;
  }
}
class LexerPropertiesToken extends LexerToken {
  public $properties = array();
  function __construct(string $raw){
    parent::__construct($raw,LexerToken::TYPE_PROPERTIES);
  }

  function addProperty(string $name, string $value){
    array_push($this->properties,["name" => $name, "value" => $value]);
  }

}

?>
