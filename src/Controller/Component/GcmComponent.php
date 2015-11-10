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
use Cake\Core\Exception\Exception;
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
			'url' => 'https://android.googleapis.com/gcm/send'
		],
		'parameters' => [
			'delay_while_idle' 		  => false,
			'dry_run' 				  => false,
			'time_to_live' 			  => 0,
			'collapse_key' 			  => null,
			'restricted_package_name' => null
		],
		'model' => [
			'Device' => [
				'alias'     => 'Device',
				'scope'     => array(),
				'recursive' => 0,
				'contain'   => null
			],
			'Notification' => [
				'alias'     => 'Notification',
				'scope'     => array(),
				'recursive' => 0,
				'contain'   => null
			]
		]
	];

	/**
	 * Error code and message.
	 *
	 * @var array
	 */
	protected $_errorMessages = array();

	/**
	 * Response of the request
	 *
	 * @var object
	 */
	protected $_response = null;

	/**
	 * Controller reference
	 */
	protected $Controller = null;

	/**
	 * A Component collection, used to get more components.
	 *
	 * @var ComponentCollection
	 */
	protected $Collection;

	/**
	 * Constructor
	 *
	 * @param ComponentRegistry $collection A ComponentRegistry
	 * @param array $config Array of configuration settings
	 */
	public function __construct(ComponentRegistry $collection, array $config = [])
	{
		$this->_errorMessages = array(
			'400' => __('Error 400. The request could not be parsed as JSON.'),
			'401' => __('Error 401. Unable to authenticating the sender account.')
		);
	}

	/**
	 * Called before the Controller::beforeFilter().
	 *
	 * @param Controller $controller Controller with components to initialize
	 * @return void
	 */
	public function initialize(Controller $controller) {
		$this->Controller = $controller;

	}

	/**
	 * send method
	 *
	 * @param string|array $ids
	 * @param array $data
	 * @param array $parameters
	 * @return void
	 */
	public function send($ids = false, array $data = [], array $parameters = [])
	{
		if (is_string($ids)) {
			$ids = (array)$ids;
		}

		if ($ids === false || !is_array($ids) || empty($ids)) {
			throw new \LogicException(__('Ids must be a string or an array.'));
		}

		if (!is_array($data)) {
			throw new \LogicException(__('Data must be an array.'));
		}

		if (!is_array($parameters)) {
			throw new \LogicException(__('Parameters must be an array.'));
		}

		$parameters = $this->_checkParameters($parameters);
		if (!$parameters) {
			throw new \ErrorException(__('Unable to check parameters.'));
		}

		$notification = $this->_buildNotification($ids, $data, $parameters);
		if (!$notification) {
			throw new \ErrorException(__('Unable to build the notification.'));
		}

		return $this->_executePush($notification);
	}

	/**
	 * response method
	 *
	 * @return void
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
	 * @param json $notification
	 * @return bool
	 */
	protected function _executePush($notification = false)
	{
		if ($notification === false) {
			return false;
		}

		if (is_null($this->_config['api']['key'])) {
			throw new \ErrorException(__('No API key set. Push not triggered'));
		}

		$http = new Client();
		$this->_response = $http->post($this->_config['api']['url'], $notification, [
			'type' => 'json',
			'header' => [
				'Authorization' => 'key=' . $this->config['api']['key'],
				'Content-Type' => 'application/json'
			]
		]);

		if ($this->_response->code === '200') {
			return true;
		}

		return false;
	}

	/**
	 * _buildNotification method
	 *
	 * @param array $ids
	 * @param array $data
	 * @param array $parameters
	 * @return json
	 */
	protected function _buildNotification($ids = false, $data = false, $parameters = false)
	{
		if ($ids === false) {
			return false;
		}

		$notification = ['registration_ids' => $ids];

		if (!empty($data)) {
			$notification['data'] = $data;
		}

		if (!empty($parameters)) {
			$notification += $parameters;
		}

		return json_encode($notification);
	}

	/**
	 * _checkParameters method
	 *
	 * @param array $parameters
	 * @return array $parameters
	 */
	protected function _checkParameters($parameters = false)
	{
		if ($parameters === false) {
			return false;
		}

		$parameters = Hash::merge($this->_config['parameters'], $parameters);
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
