<?
class RouterMatcher {
	private $matcher = null;
	private $ids = null;

	function __construct($matcher, $ids)
	{
		$this->matcher = $matcher;
		$this->ids = $ids;
	}

	public function match($path) {
		if ( preg_match_all($this->matcher, $path, $matches) ) {
			$ids = array_slice($matches, 1);
			$results = [];

			for ( $i = 0; $i < count($this->ids); $i++ ) {
				$results[$this->ids[$i]] = $ids[$i][0];
			}

			return $results;
		}

		return false;
	}
}

class Router
{
	private $id_matcher = '([a-zA-Z0-9-_]+)';
	private $methods = [
		'GET' => [],
		'POST' => []
	];
	private $not_found_custom = null;
	private $unknown_custom = null;

	function __construct()
	{
		# code...
	}

	private function _not_found() {
		header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 500);
		die();
	}

	private function _unknown() {
		header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		die();
	}

	public function route($method, $path, $handler) {
		if ( !isset($this->methods[$method]) ) {
			throw new Exception("Unknown method", 1);
		}

		$id_matcher = $this->id_matcher;
		$matcher = '/:'.$id_matcher.'/';

		if ( preg_match_all($matcher, $path, $matches) ) {
			$match_url = preg_replace($matcher, $id_matcher, $path);
			$match_url = preg_replace('/\//', '\/', $match_url);
			$match_url = '/^'.$match_url.'$/';
			$router_matcher = new RouterMatcher($match_url, $matches[1]); 

	 		$this->methods[$method][] = [
	 			'type' => 'regex',
	 			'handler' => $handler,
	 			'matcher' => $router_matcher
	 		];
	 	}
	 	else {
	 		$this->methods[$method][] = [
	 			'type' => 'string',
	 			'path' => $path,
	 			'handler' => $handler
	 		];
	 	}
	}

	public function get($path, $handler) {
		$this->route('GET', $path, $handler);
	}

	public function post($path, $handler) {
		$this->route('POST', $path, $handler);
	}

	public function not_found($handler) {
		$this->not_found_custom = $handler;
	}

	public function unknown($handler) {
		$this->unknown_custom = $handler;
	}

	public function start() {
		$method = $_SERVER['REQUEST_METHOD'];
		$request_uri = $_SERVER['REQUEST_URI'];

		if ( $index = strpos($request_uri, '?') ) {
            $path = substr($request_uri, 0, $index);
        }
        else {
            $path = $request_uri;
        }

		if ( !isset($this->methods[$method]) ) {
			$this->_unknown();
		}

		foreach ( $this->methods[$method] as $handler ) {
			if ( $handler['type'] == 'string' && $handler['path'] == $path ) {
				$handler['handler']([
					'path' => $path,
				]);
				return;
			}
			if ( $handler['type'] == 'regex' && $result = $handler['matcher']->match($path) ) {
				$handler['handler']([
					'path' => $path,
					'params' => $result,
				]);
				return;
			}
		}

		$this->_not_found();
	}
}