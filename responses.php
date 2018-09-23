<?php
function setResponse($code, $num) {
	switch ($code) {
		case 200:
			$msg = 'OK';
			$desc = 'Request successfully returned resource(s).';
			http_response_code($code);
			break;
		case 403:
			$msg = 'Forbidden';
			$desc = 'The request was valid, but the user does not have the necessary permissions.';
			http_response_code($code);
			break;
		case 404:
			$msg = 'Not found';
			$desc = 'The requested resource could not be found.';
			http_response_code($code);
			break;
	}
	return '"response": {
	  "code" : '.$code.',
	  "message" : "'.$msg.'",
	  "description" : "'.$desc.'",
	  "results" : '.$num.'
	}';
}