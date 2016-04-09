<?php
namespace ker0x\CakeGcm\Test\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\Controller\Controller;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\TestSuite\IntegrationTestCase;
use ker0x\CakeGcm\Controller\Component\GcmComponent;

class GcmComponentTest extends IntegrationTestCase
{
    public $component = null;

    public $controller = null;

    public $ids = null;

    public function setUp()
    {
        parent::setUp();
         // Setup our component and fake test controller
        $request = new Request();
        $response = new Response();
        $this->controller = $this->getMock(
            'Cake\Controller\Controller',
            null,
            [$request, $response]
        );
        $registry = new ComponentRegistry($this->controller);
        $this->component = new GcmComponent($registry, [
            'api' => [
                'key' => getenv('GCM_API_KEY')
            ]
        ]);
    }

    public function testIds()
    {
        $this->ids = getenv('TOKEN');

        $this->component->send($this->ids, [
            'notification' => [
                'title' => 'Hello World',
                'body' => 'My awesome Hello World!'
            ]
        ]);
        $this->assertResponseCode(200);
    }

    public function tearDown()
    {
        parent::tearDown();
        // Clean up after we're done
        unset($this->component, $this->controller);
    }
}
