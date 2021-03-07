<?php
namespace My\OrgMode;

require_once __DIR__.'/Stream.php';


use My\OrgMode\Stream;

class Lexer {
  //private Stream $stream;
	private $stream;
  private $matches = array();
	private $settings;
  function __construct(Stream $stream, array $settings){
    $this->stream = $stream;
		$this->settings = $settings;
  }
  public function hasNext(){
    return $this->stream->hasNextLine();
  }
  public function getNext():LexerToken{
    $line = $this->stream->getNextLine();
    //print($line);
    // ========== HEADER ==========
    if($this->isHeaderToken($line)){
      return new LexerTokenHeader($line,$this->matches[2],strlen($this->matches[1]), $this->settings);
    }
		// ========== TODOFLAG CHANGELOG ==========
		if($this->isTodoChangeLog($line)){
			$lexerTodoChangelogToken = new LexerTodoChangelogToken($line, $this->matches[3], $this->matches[2], $this->matches[4]);
			if($this->matches[5] == "\\\\"){ // Changelog contains comment
				$comment = $this->stream->getNextLine();
				if(preg_match("/^(\s*)(.*)$/", $comment, $matches)){
					$lexerTodoChangelogToken->addCommentLine($matches[2]);
					$commentNext = $this->stream->peekNextLine();
					while(preg_match("/^(".$matches[1].")(.*)$/", $commentNext, $matchesNext)){
						$this->stream->getNextLine();
						$commentNext = $this->stream->peekNextLine();
						$lexerTodoChangelogToken->addCommentLine($matchesNext[2]);

					}
		    }
			}
			return $lexerTodoChangelogToken;
		}
    // ========== PROPERTIES ==========
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
            }
          }
        }
      }
      return $lexerPropToken;
    }
    // ========== LOGBOOK ==========
    if($this->isLogbookStartToken($line)){
      $lexerLogbookToken = new LexerLogbookToken($line);
      $inProps = true;
      while($inProps){
        if(!$this->hasNext()){
          $inProps = false;
        }else{
          $propLine = $this->stream->getNextLine();
          if($this->isHeaderToken($propLine) || $this->isBlockEndToken($propLine)){
            $inProps = false;
          } else {
            if($this->isLogbookLineToken($propLine)){
              $logbookLine = array();
              $logbookLine["start"] = date_create_from_format("Y-m-d * H:i", $this->matches[1]);
              $logbookLine["end"] = date_create_from_format("Y-m-d * H:i", $this->matches[2]);
              $logbookLine["duration"] = ( $this->matches[3] * 60 ) + $this->matches[4];
              $lexerLogbookToken->addLine($logbookLine);
            }
          }
        }
      }
      return $lexerLogbookToken;
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
  protected function isLogbookStartToken($line){
    return $this->checkLine($line,"/^(\s*)(:LOGBOOK:)(\s*)$/"); // m[1] => level, m[2] => content
  }
  protected function isLogbookLineToken($line){
    return $this->checkLine($line,"/^\s*CLOCK: \[(.*)\]--\[(.*)]\s*=>\s*(\d*):(\d*)\s*$/"); // m[1] => level, m[2] => content
  }
  protected function isBlockEndToken($line){
    return $this->checkLine($line,"/^(\s*)(:END:)(\s*)$/"); // m[1] => level, m[2] => content
  }
	protected function isTodoChangeLog($line){
    return $this->checkLine($line,"/^(\s*)- State \"(.*)\"\s*from \"(.*)\"\s*\[(.*)\]\s*([\\\]{2})?\s*$/");
		// m[1] => spaces, m[2] => State from, m[3] => State to, m[4] => date, m[5] => Separator (\\ => Comments in new Line)
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
  const TYPE_LOGBOOK = 3;
	const TYPE_TODOCHANGELOG = 4;

  private $type;
  function __construct(string $raw, int $type){
    $this->type = $type;
  }
  public function getType():int {
    return $this->type;
  }
}

class LexerTodoChangelogToken extends LexerToken {
	public $stateFrom;
	public $stateTo;
	public $date;
	public $comment = [];
	function __construct(string $raw, string $stateFrom, string $stateTo, string $timestamp){
		parent::__construct($raw,LexerToken::TYPE_TODOCHANGELOG);
		$this->stateFrom = $stateFrom;
		$this->stateTo = $stateTo;
		$this->date = date_create_from_format("Y-m-d * H:i", $timestamp);
	}
	function addCommentLine(string $line) {
		array_push($this->comment,trim($line));
  }
}

class LexerTokenHeader extends LexerToken {
  private $level;
  private $title;
  private $todoFlags = ["TODO","DONE"];
  private $data = array();
  function __construct(string $raw, string $title, int $level, array $settings){
     parent::__construct($raw,LexerToken::TYPE_HEADER);
		 if(array_key_exists("todoflags",$settings)){
			 $this->todoFlags = $settings["todoflags"];
		 }

     // Scan title for Todo Flags
     foreach ($this->todoFlags as &$flag) {
       if(substr_compare($title, $flag." ", 0, strlen($flag)+1) === 0){
         $title = substr($title,strlen($flag)+1);
         $this->data["todoflag"] = $flag;
         break;
       }
     }

     // Scan title for priority
     if(preg_match("/^\s*\[#([A-Z])\]\s\s*(.*)\s*$/", $title, $matches)){
       //print("Prio found".$matches[1]);
       $title = trim($matches[2]);
       $this->data["prio"] = $matches[1];
     }

     // Scan title for Tags
     $this->data["tags"] = [];
     $tagregex = "/^\s*(.*)\s*:([^ :]*):(\s*)$/";
     while(preg_match($tagregex, $title, $matches)){
       $title = trim($matches[1]);
       array_splice($this->data["tags"], 0, 0, $matches[2]);
       $tagregex = "/^\s*(.*)\s*:([^ :]*)(\s*)$/";
     }

     $this->level = $level;
     $this->title = $title;
  }

  function getTitle():string {
    return $this->title;
  }
  function getLevel():int{
    return $this->level;
  }
  function getData():array{
    return $this->data;
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

class LexerLogbookToken extends LexerToken {
  public $logbookLines = array();
  function __construct(string $raw){
    parent::__construct($raw,LexerToken::TYPE_LOGBOOK);
  }
  function addLine(array $line){ //["start","end","duration"] => Duration in Minutes
    array_push($this->logbookLines,$line);
  }
}

?>
