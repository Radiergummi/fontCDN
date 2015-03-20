<?

class Uri {
  /**
   * holds the current uri
   * 
   * @var array
   */
  private $current = array();

  public function __construct() {
    $this->current = parse($_SERVER['REQUEST_URI']);
  }

  public function parse(string $uri) {
    if($uri = filter_var($uri, FILTER_SANITIZE_URL)) {
      // remove possible subfolder, trim the uri from slashes and explode the segments into an array.
      return explode('/', trim(str_replace(Config::get('app.relativepath'), '', $uri), '/'));
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
      
    } else {
      return $this->current;
    }
  }
}
