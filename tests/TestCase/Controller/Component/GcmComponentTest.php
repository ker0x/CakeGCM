<?php
namespace ker0x\CakeGcm\Test\TestCase\Controller\Component;

use Cake\Controller\Controller;
use Cake\Controller\ComponentRegistry;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\TestSuite\TestCase;
use ker0x\CakeGcm\Controller\Component\GcmComponent;

class GcmComponentTest extends TestCase
{
    public $component = null;
    public $controller = null;

    public function setUp()
    {
        parent::setUp();
         // Setup our component and fake test controller
        $request = new Request();
        $response = new Response();
        $this->controller = $this->getMock(
            'Cake\Controller\Controller',
            [],
            [$request, $response]
        );
        $registry = new ComponentRegistry($this->controller);
        $this->component = new GcmComponent($registry);
    }

    public function testSend()
    {
        $this->component->send();
        $this->assertEquals('', $this->component->response());
    }

    public function tearDown()
    {
        parent::tearDown();
        // Clean up after we're done
        unset($this->component, $this->controller);
    }
}
