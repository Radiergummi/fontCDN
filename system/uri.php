<?

class Uri {
  /**
   * holds the current uri
   * 
   * @var string
   */
  private $current = '';

  /**
   * holds the named uri segments in an associative array
   * @example array('segment_name' => 'segment_value');
   * 
   * @var array
   */
  private $segments = array();

  public function __construct() {
    $this->current = parse($_SERVER['REQUEST_URI']);
  }

  public function parse(string $uri) {
    if($uri = filter_var($uri, FILTER_SANITIZE_URL)) {
      // remove possible physical subfolder and trim the uri from slashes.
      return trim(str_replace(Config::get('app.relativepath'), '', $uri), '/'));
    }
  }
  
  /**
   * get one (or all) segments of the current uri
   * 
   * @param string $segment (optional) segment to return.
   * 
   * @return string the segment of the uri
   */
  public function get(string $segment = null) {
    if (! empty($segment)) {
      return $this->segments[$segment];
    } else {
      return $this->current;
    }
  }
}
