<?php
namespace ker0x\CakeGcm\Test\TestCase;

use ker0x\CakeGcm\Controller\Component\GcmComponent;

class GcmComponentStub extends GcmComponent
{

    public function initialize(array $config = [])
    {
        parent::initialize($config);
        $this->_gcm = new GcmStub($config);
    }
}