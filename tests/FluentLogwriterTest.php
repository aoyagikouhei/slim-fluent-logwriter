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

    public function testWriteOption()
    {
        $callable = function($logger, $entity, $error) {
            $this->assertEquals('systemlog', $entity->getTag());
            $data = $entity->getData();
            $this->assertEquals('aaa', $data['m']);
            $this->assertEquals(1, $data['l']);
            $this->assertEquals('y', $data['x']);
        };
        $logger = new \Slim\FluentLogwriter(array('error_handler' => $callable, 'option' => array('x' => 'y')));
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
    
    public function testIsWrite()
    {
        $logger = new \Slim\FluentLogwriter();
        $ref = new ReflectionMethod('\Slim\FluentLogwriter', 'isWrite');
        $ref->setAccessible(true);
        $this->assertEquals(true, $ref->invoke($logger, '', 0, array('level' => 0)));
        $this->assertEquals(false, $ref->invoke($logger, '', 1, array('level' => 0)));
        $this->assertEquals(false, $ref->invoke($logger, '', null, array('level' => 0)));
        $this->assertEquals(true, $ref->invoke($logger, '', 0, array('level' => 1)));
        $this->assertEquals(true, $ref->invoke($logger, '', 1, array('level' => 1)));
        $this->assertEquals(false, $ref->invoke($logger, '', 2, array('level' => 1)));
        $this->assertEquals(false, $ref->invoke($logger, '', null, array('level' => 1)));
        $this->assertEquals(true, $ref->invoke($logger, '', null, array('level' => 4)));
    }
    
    public function testWriteWithLevel()
    {
        $callable = function($logger, $entity, $error) {
            $this->assertEquals('systemlog', $entity->getTag());
            $data = $entity->getData();
            $this->assertEquals('aaa', $data['m']);
            $this->assertEquals(0, $data['l']);
        };
        $logger = new \Slim\FluentLogwriter(array('level' => 0, 'error_handler' => $callable));
        $logger->write('aaa', 0);
        $logger->write('bbb', 1);
    }
    
    public function testWriteWithMulti()
    {
        $callable1 = function($logger, $entity, $error) {
            $this->assertEquals('fatal', $entity->getTag());
            $data = $entity->getData();
            $this->assertEquals('aaa', $data['m']);
            $this->assertEquals(0, $data['l']);
        };
        $callable2 = function($logger, $entity, $error) {
            $this->assertEquals('error', $entity->getTag());
            $data = $entity->getData();
            if (0 == $data['l']) {
                $this->assertEquals('aaa', $data['m']);
            } else if (1 == $data['l']) {
                $this->assertEquals('bbb', $data['m']);
            } else {
                $this->fail("error level");
            }
        };
        $logger = new \Slim\FluentLogwriter(array('tag' => 'fatal', 'level' => 0, 'error_handler' => $callable1));
        $logger = new \Slim\FluentLogwriter(array('tag' => 'error', 'level' => 1, 'error_handler' => $callable2));
        $logger->write('aaa', 0);
        $logger->write('bbb', 1);
        $logger->write('ccc', 2);
    }
}
