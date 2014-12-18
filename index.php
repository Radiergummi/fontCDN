<?
$agent = $_SERVER['HTTP_USER_AGENT'];

$request = explode('/',urldecode(ltrim($_SERVER['REQUEST_URI'],'/')));
$type = array_shift($request);


if ($type === 'fonts') {
	header('Content-Type: text/css; charset: utf-8');

	# debug info - user agent data
	# echo '/* Detected browser: ' . $browserdata['browser'] . ' ' . $browserdata['version'] . ' */' . PHP_EOL . PHP_EOL;
	
	# detect the users browser using a custom function
	$browserdata = browserdetect($agent, array('Chrome','Firefox','Opera','Safari','MSIE', 'Trident'));
	
	$fonts = parseFontRequest(explode('&',array_shift($request)));

	foreach($fonts as $font) {
			echo '@font-face {'.PHP_EOL;
			echo '  font-family: "'.$font['name'].'";'.PHP_EOL;
			echo '  font-style: '.$font['fontStyle'].';'.PHP_EOL;
			echo '  font-weight: '.$font['fontWeight'].';'.PHP_EOL;
			echo '  src: local("'.$font['name'].'"), local("'.$font['filename'].'"), url(http://static.9dev.de/files/fonts/'.$font['filename'].'/'.$font['filename'].'-'.$font['fontStyle'].'.'.$browserdata['fileFormat'].') format("'.$browserdata['fileFormat'].'");'.PHP_EOL;
			echo '  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;'.PHP_EOL;
			echo '}'.PHP_EOL;
			echo PHP_EOL . PHP_EOL;
	}
	
} else if ($type === 'svg') {
	// svg requests
	header('Content-Type: image/svg+xml');
	
	// TODO
	
	
} else if ($type === 'files') {
	// Direct file requests
	include('/files/' . array_shift($request));
} else {
	echo 'invalid request.';
	// TODO: error page
	# include('error_page.php');
}




/**
 * @name parseFontRequest
 * @description takes all info from the uri and generates an array containing the sorted information for all requested fonts.
 * 
 * @param fontStringArray the request string
 * @return array containing the sorted information for all requested fonts
 */
function parseFontRequest($fontStringArray) {
	$array_fonts = array();
	
	# this should be solved more elegantly, but using a database seems inefficient to me
	$availableStyles = array('normal', 'italic');
	
	foreach ($fontStringArray as $fontString) {
	
		$requestedFont = explode('|', $fontString);
		$name = ucwords(strtolower($requestedFont[0]));
		$filename = str_replace(' ', '', $name);
		
		
		// if certain styles requested, eg. the second part of the font string is not empty...
		if (!empty($requestedFont[1])) {

			// ...explode the style string to get all style combinations
			foreach(explode(',',$requestedFont[1]) as $requestedStyle) {
			
				// explode every style string to get style and weight
				$styles = explode(':',$requestedStyle);

				// check if requested style is available
				if (!in_array($styles[0], $availableStyles)) {

				// if not, output error as css comment and deliver default value
					echo '/* Requested style ' . $styles[0] . ' not available, falling back to normal */' . PHP_EOL;
					$fontStyle = 'normal';
				} else {
					// set font style
					$fontStyle = $styles[0];
				}
				
				// check if font weight is in ordinary range
				if ($styles[1] > 900) {
					echo '/* Requested weight ' . $styles[1] . ' is too high, falling back to 900 */' . PHP_EOL;
					$fontWeight = 900;
				} else if ($styles[1] < 100 && $styles[1] > 0) {
					echo '/* Requested weight ' . $styles[1] . ' is too low, falling back to 100 */' . PHP_EOL;
					$fontWeight = 100;
				} else if ($styles[1] == 0) {
					// probably no weight given, default to 400
					echo '/* No weight specified, falling back to 400 */' . PHP_EOL;
					$fontWeight = 400;
				} else {
					// set font weight
					$fontWeight = $styles[1];
				}
				
				// push everything into the array
				array_push($array_fonts, array('name' => $name, 'filename' => $filename, 'fontStyle' => $fontStyle, 'fontWeight' => $fontWeight));
			}
		} else {
			echo '/* No certain style requested, falling back to default */' . PHP_EOL;
			array_push($array_fonts, array('name' => $name, 'filename' => $filename, 'fontStyle' => 'normal', 'fontWeight' => '400'));
		}
	}
	return $array_fonts;
}

/**
 * @name browserdetect
 * @description scans the user agent for browser and version and adds custom values as required.
 * 
 * @param fontStringArray the request string
 * @param $browsers array containing a list of browsers to look for. this can be anything requiring a certain modification in the future.
 * @return array containing the browser, its version and the supported font format
 */
function browserdetect($agent,$browsers){
	// create default values
	$array_browser = array('browser' => 'n/a', 'version' => 0, 'fileFormat' => 'woff');
	
	foreach ($browsers as $browser) {
		
		// if a user agent could be determined...
		if(strlen(stristr($agent,$browser)) > 0 ){

			// filter out version using a regex
			preg_match('/'.$browser.'\/([0-9]\.?[0-9])/',$agent,$matchedVersion);
			$array_browser['browser'] = $browser;
			$array_browser['version'] = (string)$matchedVersion[1];

			switch ($array_browser['browser']) {
				case 'Chrome':
					// if Chrome > 35, set file format to woff2
					if ($array_browser['version'] >= 36) {
						$array_browser['fileFormat'] = 'woff2';
					}

					return $array_browser;
				break;

				case 'MSIE':
					// if IE > 8, set file format to woff
					if ($array_browser['version'] < 9) {
						$array_browser['fileFormat'] = 'eot';
					}

					return $array_browser;
				break;

				case 'Trident':
					// Change browser name to MSIE
					$array_browser['browser'] = 'MSIE';

					// Extract version number, since Microsoft specifies only Trident engine number in expected format
					preg_match('/rv:([0-9]{1,2}\.?[0-9]{1,2})/',$agent,$tridentVersion);
					$array_browser['version'] = $tridentVersion[1];
				break;

				default:
					// if the user agent is unknown, default to woff
					$array_browser['fileFormat'] = 'woff';
				break;
			}
		}
	}
	return $array_browser;
}
