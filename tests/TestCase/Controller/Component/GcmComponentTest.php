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

        $this->controller = $this->getMockBuilder(Controller::class)
            ->setConstructorArgs([$request, $response])
            ->setMethods(null)
            ->getMock();

        $registry = new ComponentRegistry($this->controller);
        $config = [
            'api' => [
                'key' => getenv('GCM_API_KEY')
            ],
            'http' => [
                'ssl_verify_peer' => false,
                'ssl_verify_peer_name' => false,
                'ssl_verify_host' => false
            ]
        ];
        $response = json_decode(file_get_contents(__DIR__ . '/../../../Mocks/response.json'), true);

        $this->component = $this->getMockBuilder(GcmComponent::class)
            ->setConstructorArgs([$registry, $config])
            ->getMock();

        $this->component->method('response')->willReturn($response);
        $this->component->method('sendNotification')->willReturn(true);
        $this->component->method('sendData')->willReturn(true);
        $this->component->method('send')->willReturn(true);

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
