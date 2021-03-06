<?php
namespace My\OrgMode;

require_once __DIR__.'/Node.php';
require_once __DIR__.'/Stream.php';
require_once __DIR__.'/Lexer.php';

/**
* Based on Javascript-Implementation: https://github.com/mooz/org-js
*/

use My\OrgMode\Node;
use My\OrgMode\Stream;
use My\OrgMode\Lexer;
use My\OrgMode\LexerToken;

class Parser {
  private $currentNode = null;
	private $settings;
	function __construct(array $settings = []){
    $this->settings = $settings;
  }

  public function parseString($string){
    $stream = new Stream($string);
    $lexer = new Lexer($stream,$this->settings);

    $rootNode = new Node("");
    $this->currentNode = $rootNode;
    while($lexer->hasNext()){
      $this->parseLexerElement($lexer);
    }

    return $rootNode;
  }
  protected function parseLexerElement(Lexer $lexer){
    $token = $lexer->getNext();
    switch($token->getType()){
      case LexerToken::TYPE_UNKNOWN:
				$this->currentNode->addContent($token->getRaw());
        break;
      case LexerToken::TYPE_HEADER:
        while($this->currentNode->getLevel() >= $token->getLevel()){
          $this->currentNode = $this->currentNode->getParentNode();
        }
        $newNode = new Node("",$token->getTitle(),$token->getData());
        $this->currentNode->addSubNode($newNode);
        $this->currentNode = $newNode;
        break;
      case LexerToken::TYPE_PROPERTIES:
        $this->currentNode->setProperties($token->properties);
        break;
      case LexerToken::TYPE_LOGBOOK:
        foreach ($token->logbookLines as &$logbookLine) {
          $this->currentNode->getLogbook()->addEntry($logbookLine);
        }
        break;
			case LexerToken::TYPE_TODOCHANGELOG:
				$this->currentNode->addTodoChangelog($token->stateFrom, $token->stateTo, $token->date, $token->comment);
				break;
    }
  }
}
?>
