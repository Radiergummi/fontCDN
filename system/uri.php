<?

class Uri {
  private uri;

  public function __construct() {
    $this->uri = parse($_SERVER['REQUEST_URI']);
  }

  public function parse($uri) {
    if($uri = filter_var($uri, FILTER_SANITIZE_URL)) {
      // remove possible subfolder, trim the uri from slashes and explode the segments into an array.
      return explode('/', trim(str_replace(Config::get('app.relativepath'), '', $uri), '/'));
    }
  }
  
  public function get($segment = null) {
    if (! empty($segment)) {
      
    }
  }
}
