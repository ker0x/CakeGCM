<?php
App::uses('Component', 'Controller');
App::uses('HttpSocket', 'Network/Http');
App::uses('Hash', 'Utility');

/**
* Gcm Exception classes
*/
class GcmException extends CakeException {
}

/**
 * Gcm Component
 *
 */
class GcmComponent extends Component {

	/**
	 * Default options
	 *
	 * @var array
	 */
	protected $_defaults = array(
		'api' => array(
			'key' => '',
			'url' => 'https://android.googleapis.com/gcm/send'
		),
		'parameters' => array(
			'delay_while_idle' 		  => false,
			'dry_run' 				  => false,
			'time_to_live' 			  => 0,
			'collapse_key' 			  => null,
			'restricted_package_name' => null
		),
		'model' => array(
			'Device' => array(
				'alias'     => 'Device',
				'scope'     => array(),
				'recursive' => 0,
				'contain'   => null
			),
			'Notification' => array(
				'alias'     => 'Notification',
				'scope'     => array(),
				'recursive' => 0,
				'contain'   => null
			)
		)
	);

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
	 * @param ComponentCollection $collection
	 * @param array $settings
	 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->Collection = $collection;
		$this->_defaults = Hash::merge($this->_defaults, $settings);

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
		foreach ($this->_defaults['model'] as $model) {
			$this->{$model['alias']} = ClassRegistry::init($model['alias']);
		}
	}

	/**
	 * send method
	 *
	 * @param string|array $ids
	 * @param array $data
	 * @param array $parameters
	 * @return void
	 */
	public function send($ids = false, $data = array(), $parameters = array()) {

		if (is_string($ids)) {
			$ids = (array)$ids;
		}

		if ($ids === false || !is_array($ids) || empty($ids)) {
			throw new GcmException(__('Ids must be a string or an array.'));
		}

		if (!is_array($data)) {
			throw new GcmException(__('Data must be an array.'));
		}

		if (!is_array($parameters)) {
			throw new GcmException(__('Parameters must be an array.'));
		}

		$parameters = $this->_checkParameters($parameters);
		if (!$parameters) {
			throw new GcmException(__('Unable to check parameters.'));
		}

		$notification = $this->_buildNotification($ids, $data, $parameters);
		if ($notification === false) {
			throw new GcmException(__('Unable to build the notification.'));
		}

		return $this->_executePush($notification);
	}

	/**
	 * response method
	 *
	 * @return void
	 */
	public function response() {
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
	protected function _executePush($notification = false) {
		if ($notification === false) {
			return false;
		}

		if ($this->_defaults['api']['key'] === null) {
			throw new GcmException(__('No API key set. Push not triggered'));
		}

		$httpSocket = new HttpSocket();
		$this->_response = $httpSocket->post($this->_defaults['api']['url'], $notification, array(
			'header' => array(
				'Authorization' => 'key=' . $this->_defaults['api']['key'],
				'Content-Type' => 'application/json'
			)
		));

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
	protected function _buildNotification($ids = false, $data = false, $parameters = false) {
		if ($ids === false) {
			return false;
		}

		$notification = array('registration_ids' => $ids);

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
	protected function _checkParameters($parameters = false) {
		if ($parameters === false) {
			return false;
		}

		$parameters = Hash::merge($this->_defaults['parameters'], $parameters);
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
