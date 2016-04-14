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
            ],
            'http' => [
                'ssl_verify_peer' => false,
                'ssl_verify_peer_name' => false,
                'ssl_verify_host' => false
            ]
        ]);
    }

    public function testSendNotification()
    {
        $ids = getenv('TOKEN');

        $result = $this->component->sendNotification(
            $ids,
            [
                'title' => 'Hello World',
                'body' => 'My awesome Hello World!'
            ],
            [
                'dry_run' => true
            ]
        );

        $this->assertTrue($result);
    }

    public function testSendData()
    {
        $ids = getenv('TOKEN');

        $result = $this->component->sendData(
            $ids,
            [
                'data-1' => 'Lorem ipsum',
                'data-2' => 1234,
                'data-3' => true
            ],
            [
                'dry_run' => true
            ]
        );

        $this->assertTrue($result);
    }

    public function tearDown()
    {
        parent::tearDown();
        // Clean up after we're done
        unset($this->component, $this->controller);
    }
}
