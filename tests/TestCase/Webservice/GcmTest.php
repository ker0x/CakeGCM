<?php
namespace ker0x\CakeGcm\Test\TestCase\Webservice;

use Cake\TestSuite\IntegrationTestCase;
use ker0x\CakeGcm\Webservice\Gcm;

class GcmTest extends IntegrationTestCase
{
    /**
     * @var \ker0x\CakeGcm\Webservice\Gcm;
     */
    public $gcm = null;

    public $tokens = null;

    public function setUp()
    {
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
        $response = json_decode(file_get_contents(__DIR__ . '/../../Mocks/response.json'), true);

        $this->gcm = $this->getMockBuilder(Gcm::class)
            ->setConstructorArgs([$config])
            ->getMock();

        $this->gcm->method('response')->willReturn($response);
        $this->gcm->method('sendNotification')->willReturn(true);
        $this->gcm->method('sendData')->willReturn(true);

        $this->tokens = getenv('TOKEN');
    }

    public function testSendNotification()
    {
        $result = $this->gcm->sendNotification(
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
        $result = $this->gcm->sendData(
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
        $this->gcm->send(
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

        $response = $this->gcm->response();

        $this->assertEquals(1, $response['success']);
        $this->assertEquals(0, $response['failure']);
    }

    public function tearDown()
    {
        unset($this->gcm, $this->tokens);
    }
}
