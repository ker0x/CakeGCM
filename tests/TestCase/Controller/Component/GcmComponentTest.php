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

    public $tokens = null;

    public function setUp()
    {
        parent::setUp();
        $request = new Request();
        $response = new Response();
        $this->controller = $this->getMockBuilder('Cake\Controller\Controller')
            ->setConstructorArgs([$request, $response])
            ->setMethods(null)
            ->getMock();
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
        $this->tokens = getenv('TOKEN');
    }

    public function testSendNotification()
    {
        $result = $this->component->sendNotification(
            $this->tokens,
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
        $result = $this->component->sendData(
            $this->tokens,
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

    public function testResponse()
    {
        $this->component->send(
            $this->tokens,
            [
                'notification' => [
                    'title' => 'Hello World',
                    'body' => 'My awesome Hello World!'
                ],
                'data' => [
                    'data-1' => 'Lorem ipsum',
                    'data-2' => 1234,
                    'data-3' => true
                ]
            ],
            [
                'delay_while_idle' => 'true',
                'time_to_live' => '60',
                'dry_run' => 'true'
            ]
        );

        $response = $this->component->response();

        $this->assertEquals(1, $response['success']);
        $this->assertEquals(0, $response['failure']);
    }

    public function tearDown()
    {
        parent::tearDown();
        unset($this->component, $this->controller, $this->tokens);
    }
}
