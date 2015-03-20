<?
	
 
/**
 * General purpose config class
 *
 */
class Config {
	
	/**
	 * @var data
	 * 
	 * holds the configuration
	 */
	private $data = array();
	
	/**
	 * Constructor
	 * 
	 * Populates the data array with the values injected at runtime
	 *
	 * @param array $data The values to store
	 */
	public function __construct($data) {
		$this->data = json_decode($data, true);
	}
	
	/**
	 * gets a value from the config array
	 *
	 * @param string $key the config key in question
	 * @param mixed $fallback (optional) a fallback value in case the config is empty
	 * @return the value of $data[$key]
	 */
	public function get($key, $fallback = null) {
		$keys = explode('.', $key);
		$values = $this->data;

		if (count($keys) == 1) {
			$value = $values[$keys[0]];
			return (!empty($value) ? $value : $fallback);
		} else {
			// search the array using the dot character to access nested array values
			foreach($keys as $key) {
				// when a key is not found or we didnt get an array to search return a fallback value
				if(! array_key_exists($key, $values)) {
					return $fallback;
				}
				$values =& $values[$key];
			}
			return $values;
		}
	}
	
	/**
	 * sets a value in the config array
	 * 
	 * @param string $key the config key in question
	 * @param mixed $value the value to set 
	 * @return void;
	 */
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
}
