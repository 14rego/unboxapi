<?php
// API HEADERS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');//POST
header('Access-Control-Allow-Credentials: true');
$input = json_decode(file_get_contents('php://input'),true);
require_once '../config.php';
$GLOBALS['user'] = [];
require_once '../responses.php';

$headers = apache_request_headers();
foreach ($headers as $header => $value) {
	if ($header == 'X-Api-Key'){
		$api_key = $value;
	}
}
if ($api_key == API_KEY) {
	// HTTP METHOD
	$method = $_SERVER['REQUEST_METHOD'];
	//echo $method.'<br/>';
	// PARAMETERS
	$request = explode('/', trim($_SERVER['ORIG_PATH_INFO'],'/'));
	// TABLE 1
	if ($request[0]) {
		$table1 = preg_replace('/[^a-z0-9_]+/i','',$request[0]);
		//echo $table1.'<br/>';
	}
	// ID 1
	if ($request[1]) {
		$id1 = preg_replace('/[^a-z0-9_]+/i','',$request[1]);
		//echo $id1.'<br/>';
	}
	// TABLE 2
	if ($request[2]) {
		$table2 = preg_replace('/[^a-z0-9_]+/i','',$request[2]);
		//echo $table2.'<br/>';
	}
	// ID 1
	if ($request[3]) {
		$id2 = preg_replace('/[^a-z0-9_]+/i','',$request[3]);
		//echo $id2.'<br/>';
	}
	
	require_once '../dbconn.php';
	// DB_HOST DB_USER DB_PASS DB_NAME
	
	// BUILT STATEMENT BASED ON METHOD AND PARAMETERS
	switch ($method) {
		case 'GET':
			$sql = "SELECT * FROM `$table1`".($id1?" WHERE id=$id1":""); 
			break;
	}
	// EXECUTE
	$result = $conn->query($sql);
	// OUTPUT
	$resultPrefix = ',"resource": [';
	echo '{';
	switch ($method) {
		case 'GET':
			if ($result->num_rows > 0) {
				$response = setResponse(200, $result->num_rows);
				echo $response.$resultPrefix;
			    for ($i = 0; $i < $result->num_rows; $i++) {
			    	if ($i > 0) {
						echo ',';
			    	}
			    	$entireRow = mysqli_fetch_object($result);
			    	if ($table1 == 'users'){
				    	$GLOBALS['user'][$entireRow->id]['name'] = $entireRow->username;
				    	$entireRow->username = NULL;
						$GLOBALS['user'][$entireRow->id]['role'] = $entireRow->role;
						$entireRow->role = NULL;
						$GLOBALS['user'][$entireRow->id]['pass'] = $entireRow->password;
						$entireRow->password = NULL;
			    	}
					if ($table1 == 'comments'){
						$GLOBALS['visitor'][$entireRow->id]['ip'] = $entireRow->ip_add;
						$entireRow->ip_add = NULL;
					}
					echo json_encode($entireRow);
			    }
			} else {
				$response = setResponse(404, 0);
				echo $response;
			}
			break;
	}
	echo ']'.'}';
	$conn->close();
} else {
	$response = setResponse(403, 0);
	echo $response;
}