<?php
App::uses('Component', 'Controller');
App::uses('HttpSocket', 'Network/Http');
App::uses('Hash', 'Utility');

/**
* Apns Exception classes
*/
class GcmException extends CakeException {
}

/**
 * Notif Component
 *
 */
class GcmComponent extends Component {

	/**
	 * Settings for this object
	 *
	 * @var array
	 */
	public $_defaults = array(
		'api' => array(
			'key' => null,
			'url' => 'https://android.googleapis.com/gcm/send'
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
	 * Array of devices's tokens
	 *
	 * @var array
	 */
	public $tokens = array();

	/**
	 * Message to push to user
	 *
	 * @var string
	 */
	public $message = null;

	/**
	 * Informations sent in the notification
	 *
	 * @var array
	 */
	public $params = array();

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
	 * @return void
	 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->Collection = $collection;
		$this->_defaults = Hash::merge($this->_defaults, $settings);
	}

	/**
	 * Start up, gets an instance on the controller class,
	 *
	 * @return void
	 */
	public function initialize(Controller $controller) {
		$this->Controller = $controller;
		foreach ($this->_defaults['model'] as $model) {
			$this->{$model['alias']} = ClassRegistry::init($model['alias']);
		}
	}

	/**
	 *
	 * @param array $tokens
	 * @param string $message
	 * @param array $params
	 * @return void
	 */
	public function sendToAll($tokens = array(), $message = null, $params = array()) {
		if (!is_array($tokens) || empty($tokens)) {
			throw new GcmException(__('No tokens provide'));
		}
		if (is_null($message)) {
			throw new GcmException(__('No message provide'));
		}

		$this->tokens = $tokens;
		$this->message = $message;
		$this->params = $params;

		$this->_formatNotification();
		$this->_executePush();
		$this->_addNotification();
	}

	/**
	 * executePush method
	 *
	 * @param array $data
	 * @return array $response
	 */
	private function _executePush() {
		if (is_null($this->_defaults['api']['key'])) {
			throw new GcmException(__('No API key set. Push not triggered'));
		}

		$httpSocket = new HttpSocket();
		$response = $httpSocket->post($this->_defaults['api']['url'], json_encode($this->fields), array(
			'header' => array(
				'Authorization' => 'key=' . $this->_defaults['api']['key'],
				'Content-Type' => 'application/json'
			)
		));
		return json_decode($response->body(), true);
	}

	private function _addNotification($tokens = array(), ) {

	}

	private function _formatNotification() {
		$fields = array(
			'registration_ids' => $this->tokens,
			'data' => array(
				'message' => $this->message
			)
		);
		if (!empty($this->params)) {
			foreach ($this->params as $param => $value) {
				$fields['data'][$param] = $value;
			}
		}

		$this->fields = json_encode($fields);
	}
}