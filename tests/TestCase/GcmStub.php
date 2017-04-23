<?php
namespace ker0x\CakeGcm\Test\TestCase;

use ker0x\CakeGcm\Webservice\Gcm;

class GcmStub extends Gcm
{

    public function response()
    {
        $statusCode = (string)200;
        if (array_key_exists($statusCode, $this->_errorMessages)) {
            return $this->_errorMessages[$statusCode];
        }
        $response = json_decode(file_get_contents(__DIR__ . '/../Mocks/response.json'), true);

        return $response;
    }

    protected function _executePush($message)
    {
        $options = $this->_getHttpOptions();

        return true;
    }
}