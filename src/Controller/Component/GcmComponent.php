<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Romain Monteil
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Romain Monteil
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace CakeGcm\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Network\Http\Client;
use Cake\Utility\Hash;

/**
 * Gcm Component
 *
 */
class GcmComponent extends Component
{

    /**
     * Default config
     *
     * @var array
     */
    protected $_defaultConfig = [
        'api' => [
            'key' => null,
            'url' => 'https://gcm-http.googleapis.com/gcm/send'
        ],
        'parameters' => [
            'collapse_key' => null,
            'priority' => 'normal',
            'delay_while_idle' => false,
            'dry_run' => false,
            'time_to_live' => 0,
            'restricted_package_name' => null
        ]
    ];

    /**
     * List of parameters available to use in notification messages.
     *
     * @var array
     */
    protected $_allowedNotificationParameters = [
        'title',
        'body',
        'icon',
        'sound',
        'badge',
        'tag',
        'color',
        'click_action',
        'body_loc_key',
        'body_loc_args',
        'title_loc_key',
        'title_loc_args'
    ];

    /**
     * Error code and message.
     *
     * @var array
     */
    protected $_errorMessages = [];

    /**
     * Response of the request
     *
     * @var object
     */
    protected $_response = null;

    /**
     * Constructor
     *
     * @param ComponentRegistry $registry A ComponentRegistry
     * @param array $config Array of configuration settings
     */
    public function __construct(ComponentRegistry $registry, array $config = [])
    {
        parent::__construct($registry, $config);
        $this->_errorMessages = [
            '400' => __('Error 400. The request could not be parsed as JSON.'),
            '401' => __('Error 401. Unable to authenticating the sender account.'),
            '500' => __('Error 500. Internal Server Error.'),
            '503' => __('Error 503. Service Unavailable.')
        ];
    }

    /**
     * send method
     *
     * @param string|array $ids
     * @param array $payload
     * @param array $parameters
     * @throws Exception
     * @return boolean
     */
    public function send($ids = null, array $payload = [], array $parameters = [])
    {
        if (!is_string($ids) || !is_array($ids) || empty($ids)) {
            throw new Exception(__('Ids must be a string or an array with at least 1 token.'));
        }

        if (is_string($ids)) {
            $ids = (array)$ids;
        }

        if (is_array($ids) && count($ids) > 1000) {
            throw new Exception(__('Ids must contain at least 1 and at most 1000 registration tokens.'));
        }

        if (!is_array($payload)) {
            throw new Exception(__('Payload must be an array.'));
        }

        if (!is_array($parameters)) {
            throw new Exception(__('Parameters must be an array.'));
        }

        if (isset($payload['notification'])) {
            $payload['notification'] = $this->_checkNotification($payload['notification']);
            if (!$payload['notification']) {
                throw new Exception(__("Unable to check notification."));
            }
        }

        if (isset($payload['data'])) {
            $payload['data'] = $this->_checkData($payload['data']);
            if (!$payload['data']) {
                throw new Exception(__("Unable to check data."));
            }
        }

        $parameters = $this->_checkParameters($parameters);
        if (!$parameters) {
            throw new Exception(__('Unable to check parameters.'));
        }

        $message = $this->_buildMessage($ids, $payload, $parameters);

        return $this->_executePush($message);
    }

    /**
     * sendNotification method
     *
     * @param string|array $ids
     * @param array $notification
     * @param array $parameters
     * @return boolean
     */
    public function sendNotification($ids = null, array $notification = [], array $parameters = [])
    {
        return $this->send($ids, ['notification' => $notification], $parameters);
    }

    /**
     * sendData method
     *
     * @param string|array $ids
     * @param array $data
     * @param array $parameters
     * @return boolean
     */
    public function sendData($ids = null, array $data = [], array $parameters = [])
    {
        return $this->send($ids, ['data' => $data], $parameters);
    }

    /**
     * response method
     *
     * @return string
     */
    public function response()
    {
        if (array_key_exists($this->_response->code, $this->_errorMessages)) {
            return $this->_errorMessages[$this->_response->code];
        }

        return json_decode($this->_response->body, true);
    }

    /**
     * _executePush method
     *
     * @param string $message
     * @throws Exception
     * @return boolean
     */
    protected function _executePush($message)
    {
        if ($this->config('api.key') === null) {
            throw new Exception(__('No API key set. Push not triggered'));
        }

        $http = new Client();
        $this->_response = $http->post($this->config('api.url'), $message, [
            'type' => 'json',
            'headers' => [
                'Authorization' => 'key=' . $this->config('api.key'),
                'Content-Type' => 'application/json'
            ]
        ]);

        if ($this->_response->code === '200') {
            return true;
        }

        return false;
    }

    /**
     * _buildMessage method
     *
     * @param array|string $ids
     * @param array $payload
     * @param array $parameters
     * @return string
     */
    protected function _buildMessage($ids, $payload, $parameters)
    {
        $message = (count($ids) > 1) ? ['registration_ids' => $ids] : ['to' => current($ids)];

        if (!empty($payload)) {
            $message += $payload;
        }

        if (!empty($parameters)) {
            $message += $parameters;
        }

        return json_encode($message);
    }

    /**
     * _checkNotification method
     *
     * @param array $notification
     * @throws Exception
     * @return array $notification
     */
    protected function _checkNotification(array $notification = [])
    {
        if (!is_array($notification)) {
            throw new Exception('Notification must be an array.');
        }

        if (empty($notification) || !isset($notification['title'])) {
            throw new Exception('Notification\'s array must contain at least a key title.');
        }

        if (!isset($notification['icon'])) {
            $notification['icon'] = 'myicon';
        }

        foreach ($notification as $key => $value) {
            if (!in_array($key, $this->_allowedNotificationParameters)) {
                throw new Exception("The key {$key} is not allowed in notifications.");
            }
        }

        return $notification;
    }

    /**
     * _checkData method
     *
     * @param array $data
     * @throws Exception
     * @return array $data
     */
    public function _checkData(array $data = [])
    {
        if (!is_array($data)) {
            throw new Exception('Data must ba an array.');
        }

        if (empty($data)) {
            throw new Exception('Data\'s array can\'t be empty.');
        }

        // Convert all data into string
        foreach ($data as $key => $value) {
            $data[$key] = (string)$value;
        }

        return $data;
    }

    /**
     * _checkParameters method
     *
     * @param array $parameters
     * @return array $parameters
     */
    protected function _checkParameters(array $parameters = [])
    {
        $parameters = Hash::merge($this->config('parameters'), $parameters);
        $parameters = array_filter($parameters);

        if (isset($parameters['time_to_live']) && !is_int($parameters['time_to_live'])) {
            $parameters['time_to_live'] = (int)$parameters['time_to_live'];
        }

        if (isset($parameters['delay_while_idle']) && !is_bool($parameters['delay_while_idle'])) {
            $parameters['delay_while_idle'] = (bool)$parameters['delay_while_idle'];
        }

        if (isset($parameters['dry_run']) && !is_bool($parameters['dry_run'])) {
            $parameters['dry_run'] = (bool)$parameters['dry_run'];
        }

        return $parameters;
    }
}
