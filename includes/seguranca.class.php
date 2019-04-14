<?php

use Firebase\JWT\JWT;

class Seguranca {

	private static $secret_key = '3d524a53c110e4c22463b10ed32cef9d';
	private static $encrypt = ['HS256'];
	private static $aud = null;

 	public function autentificarSession(){
		$sessionHash = $this->newSessionHash();

		if($_SESSION['sessionHash'] == $sessionHash){
			return true;
		}else{
			return false;
		}
	}

	public function newSessionHash(){
		$ip = $_SERVER['REMOTE_ADDR'];
		$cookie = '';//$_SERVER['HTTP_COOKIE'];
		$browser = $_SERVER['HTTP_USER_AGENT'];

		$sessionHash = md5($ip.$cookie.$browser); 

		return $sessionHash; 
	}

	// public function newToken(){
	// 	$token = password_hash(rand(99,999), PASSWORD_DEFAULT);
	// 	$_SESSION['token']['CSRF'] = $token;
	// 	return $token;
	// }

	public function newToken($data){
		$time = time();
		$token = array(
			'exp' => $time + (60*60),
			'aud' => self::Aud(),
			'data' => $data
		);
		return JWT::encode($token, self::$secret_key);
	}

	private static function Aud(){
		$aud = '';

		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$aud = $_SERVER['HTTP_CLIENT_IP'];
		}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$aud = $_SERVER['REMOTE_ADDR'];
		}

		$aud .= @$_SERVER['HTTP_USER_AGENT'];
		$aud .= gethostname();

		return sha1($aud);
	}

	public function destinoHttp(){
		$httpReferer = "http://localhost:3000/";
		if(!empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == $httpReferer){
			return true;
		}else{
			return false;
		}
	}	

	public function wornings(){

		$worning = filter_input(INPUT_GET, 'worning', FILTER_SANITIZE_STRING);

		switch($worning){
			case "login": echo "Login ou senha inválido"; break;
			case "token": echo "Token inválido"; break;
			case "request": echo "Requisição inválida"; break;
		}
	}

	public function criptografarPg($idPg, $valor){
		
		switch($valor){
			case 1: $resultado = base64_encode($idPg); break;
			case 2: $resultado = base64_decode($idPg); break;
		}
		return $resultado;
	}

	public function criptografarSenha($password){

		$senhaCript = password_hash($password, PASSWORD_BCRYPT);
		return $senhaCript;
	}

	public function validarSenha($password, $hash){

		if(password_verify($password, $hash)){
			return true;
		}else{
			return false;
		}
	}
}
