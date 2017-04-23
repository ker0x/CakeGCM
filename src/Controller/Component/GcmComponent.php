<?php

namespace ker0x\CakeGcm\Controller\Component;

use Cake\Controller\Component;
use ker0x\CakeGcm\Webservice\Gcm;
use \Exception;

/**
 * Gcm Component
 *
 */
class GcmComponent extends Component
{

    /**
     * Gcm property
     *
     * @var object
     */
    protected $_gcm = null;

    /**
     * Initialize config data and properties.
     *
     * @param array $config Array of configuration settings
     */
    public function initialize(array $config = [])
    {
        parent::initialize($config);
        $this->_gcm = new Gcm($config);
    }

    /**
     * Send a downstream message to one or more devices
     *
     * @param mixed $ids Devices'ids
     * @param array $payload The notification and/or some datas
     * @param array $parameters Parameters for the GCM request
     * @return bool
     */
    public function send($ids = null, array $payload = [], array $parameters = [])
    {
        return $this->_gcm->send($ids, $payload, $parameters);
    }

    /**
     * Shortcut to send notification
     *
     * @param mixed $ids Devices'ids
     * @param array $notification The notification
     * @param array $parameters Parameters for the GCM request
     * @return bool
     */
    public function sendNotification($ids = null, array $notification = [], array $parameters = [])
    {
        return $this->_gcm->sendNotification($ids, $notification, $parameters);
    }

    /**
     * Shortcut to send datas
     *
     * @param mixed $ids Devices'ids
     * @param array $data Some datas
     * @param array $parameters Parameters for the GCM request
     * @return bool
     */
    public function sendData($ids = null, array $data = [], array $parameters = [])
    {
        return $this->_gcm->sendData($ids, $data, $parameters);
    }

    /**
     * Return the response of the push
     *
     * @return string
     */
    public function response()
    {
        return $this->_gcm->response();
    }
}
