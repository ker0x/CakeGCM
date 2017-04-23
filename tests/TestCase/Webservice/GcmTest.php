<?php
namespace ker0x\CakeGcm\Test\TestCase\Webservice;

use Cake\TestSuite\IntegrationTestCase;
use ker0x\CakeGcm\Test\TestCase\GcmStub;

class GcmTest extends IntegrationTestCase
{
    /**
     * @var \ker0x\CakeGcm\Webservice\Gcm;
     */
    public $gcm;

    public $tokens;

    public function setUp()
    {
        $this->gcm = new GcmStub([
            'api' => [
                'key' => '1234567890'
            ],
            'http' => [
                'ssl_verify_peer' => false,
                'ssl_verify_peer_name' => false,
                'ssl_verify_host' => false
            ]
        ]);

        $this->tokens = 'token';
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

    public function testInvalidIds()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Ids must be a string or an array with at least 1 token.');
        $result = $this->gcm->sendNotification(
            1,
            [
                'title' => 'Hello World',
                'body' => 'My awesome Hello World!'
            ],
            [
                'dry_run' => true
            ]
        );
    }

    public function testToManyIds()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Ids must contain at least 1 and at most 1000 registration tokens.');

        $ids = [];
        for ($i = 0; $i < 1002; $i++) {
            $ids[] = $i;
        }
        $result = $this->gcm->sendNotification(
            $ids,
            [
                'title' => 'Hello World',
                'body' => 'My awesome Hello World!'
            ],
            [
                'dry_run' => true
            ]
        );
    }

    public function testInvalidNotification()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Notification's array must contain at least a key title.");

        $result = $this->gcm->sendNotification(
            $this->tokens,
            [
                'body' => 'My awesome Hello World!',
            ],
            [
                'dry_run' => true
            ]
        );
    }

    public function testInvalidNotificationKey()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("The key foo is not allowed in notifications.");

        $result = $this->gcm->sendNotification(
            $this->tokens,
            [
                'title' => 'Hello World',
                'foo' => 'My awesome Hello World!',
            ],
            [
                'dry_run' => true
            ]
        );
    }

    public function testInvalidData()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Data's array can't be empty.");

        $result = $this->gcm->sendData(
            $this->tokens,
            []
        );
    }

    public function testInvalidApiKey()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("No API key set. Push not triggered");

        $gcm = new GcmStub([
            'api',
            'http' => [
                'ssl_verify_peer' => false,
                'ssl_verify_peer_name' => false,
                'ssl_verify_host' => false
            ]
        ]);
        $result = $gcm->sendNotification(
            $this->tokens,
            [
                'title' => 'Hello World',
                'body' => 'My awesome Hello World!'
            ],
            [
                'dry_run' => true
            ]
        );
    }

    public function tearDown()
    {
        unset($this->gcm, $this->tokens);
    }
}
