<?php
namespace OCA\MyPPApp\Tests\Unit\Service;

use PHPUnit_Framework_TestCase;

use OCP\AppFramework\Db\DoesNotExistException;

#use OCA\NotesTutorial\Db\Note;
use OCA\MyPPApp\Service\CustomerService;


class CustomerServiceTest extends PHPUnit_Framework_TestCase {

    private $service;
    private $mapper;
    private $userId = 'john';

    public function setUp() {
        /*$this->mapper = $this->getMockBuilder('OCA\NotesTutorial\Db\NoteMapper')
            ->disableOriginalConstructor()
            ->getMock();
        $this->service = new CustomerService($this->mapper);*/
    }

    public function testUpdate() {
      /*  // the existing note
        $note = Note::fromRow([
            'id' => 3,
            'title' => 'yo',
            'content' => 'nope'
        ]);
        $this->mapper->expects($this->once())
            ->method('find')
            ->with($this->equalTo(3))
            ->will($this->returnValue($note));

        // the note when updated
        $updatedNote = Note::fromRow(['id' => 3]);
        $updatedNote->setTitle('title');
        $updatedNote->setContent('content');
        $this->mapper->expects($this->once())
            ->method('update')
            ->with($this->equalTo($updatedNote))
            ->will($this->returnValue($updatedNote));

        $result = $this->service->update(3, 'title', 'content', $this->userId);

        $this->assertEquals($updatedNote, $result);*/
    }


    /**
     * @   expectedException OCA\NotesTutorial\Service\NotFoundException
     */
    public function testUpdateNotFound() {
        // test the correct status code if no note is found
        /*$this->mapper->expects($this->once())
            ->method('find')
            ->with($this->equalTo(3))
            ->will($this->throwException(new DoesNotExistException('')));

        $this->service->update(3, 'title', 'content', $this->userId);*/
    }

}
