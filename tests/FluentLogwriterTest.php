<?php
class FluentLogwriterTest extends PHPUnit_Framework_TestCase
{
    public function testWrite()
    {
        $callable = function($logger, $entity, $error) {
            $this->assertEquals('systemlog', $entity->getTag());
            $data = $entity->getData();
            $this->assertEquals('aaa', $data['m']);
            $this->assertEquals(1, $data['l']);
        };
        $logger = new \Slim\FluentLogwriter(array('error_handler' => $callable));
        $logger->write('aaa', 1);
    }

    public function testWriteDate()
    {
        $callable = function($logger, $entity, $error) {
            $dt = new \DateTime();
            $this->assertEquals('systemlog' . $dt->format('Ym'), $entity->getTag());
            $data = $entity->getData();
            $this->assertEquals('bbb', $data['m']);
            $this->assertEquals(2, $data['l']);
        };
        $logger = new \Slim\FluentLogwriter(array('error_handler' => $callable, 'tag_with_date' => ('Ym')));
        $logger->write('bbb', 2);
    }
}
