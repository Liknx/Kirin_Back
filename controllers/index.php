<?php

	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	header('Content-Type: text/html; charset=utf-8');

	require_once '../includes/vendor/autoload.php';
	include_once '../database/database.php';
	include_once '../database/conexion.php';
	include '../models/login.php';

	$method = $_SERVER['REQUEST_METHOD'];

	switch ($method) {
		case 'POST': $x = postMethod(); echo $x; break;
	}

	function postMethod(){
		$JSONData = file_get_contents("php://input");
		$dataObject = json_decode($JSONData);
		$response = array();
		$param['usuario']  = $dataObject->Usuario;
		$param['contrasena'] = $dataObject->Contrasena;

		if ( is_array($param) ) {
			$response["error"] = true;
			$response["message"] = "Login Fail";
			$response["signin"] = $param;
			$login = new Login();
			$res = $login->validarLogin($param);
			// var_dump($res);
			if($res['status']){
				$response = $res;
			}
		} else {
			$response["error"] = true;
			$response["message"] = "Login fail";
		}
		echo json_encode($response);
	}

?>