<?php
session_start();

require('../includes/seguranca.class.php');

class Login{

	private $seguranca;
	
	public function __construct(){
		$this->seguranca = new Seguranca();
	}

	public function validarLogin($param){

		$seguranca = new Seguranca();
		$resposta = array("status" => false, "alerta" => "", "nombre" => "", "cedula" => "", "rol" => "", "novoToken" => "");

		// if($seguranca->destinoHttp()){
		if(1==1){
			$login = $param['usuario'];
			$contrasena = $param['contrasena'];
			if($this->consultar($login, $contrasena)){
				$sessionHash = $seguranca->newSessionHash();
				$_SESSION['login'] = true;
				$_SESSION['sessionHash'] = $sessionHash;
				$resposta["status"] = true;
				$resposta["alerta"] = 0;
				$resposta["nombre"] = $_SESSION['usuario']['nombre'];
				$resposta["cedula"] = $_SESSION['usuario']['cedula'];
				$resposta["rol"] = $_SESSION['usuario']['rol'];
				$resposta['novoToken'] = $seguranca->newToken($_SESSION['usuario']);
			}else{
				$resposta["alerta"] = 1;
			}
		}
		return $resposta;
	}

	function consultar($login, $contrasena){
		global $db;
		try {
			$sql = "SELECT ID, NOMBRE, CEDULA, USUARIO, CONTRASENA, ID_ROL FROM USUARIOS WHERE USUARIO = ?";
			$res = $db->query($sql,array($login));
			if($res != null){
				foreach ($res as $user){
					if($this->seguranca->validarSenha($contrasena, $user['CONTRASENA'])){
						if($this->addIp($user['ID'])){
							$_SESSION['usuario'] = array("id" => $user["ID"], "login" => $user["USUARIO"], "nombre" => $user["NOMBRE"], "cedula" => $user["CEDULA"], "rol" => $user["ID_ROL"]);
							return true;
						}
						return false;
					}else{
						return false;
					}
				}
			}else{
				return false;
			}
		} catch (PDOException $ex){
			echo "Error generado".$ex->getMessage();
		}
	}

	function addIp($id){
		global $db;
		try{
			$sessionHash = $this->seguranca->newSessionHash();
			$sql = "UPDATE USUARIOS SET IP = ? WHERE ID = ?";
		 	$res = $db->query($sql,array($sessionHash,$id));
		 	if($res == 0 || $res == 1){
				return true;
				echo 'addIp true';
		 	}else{
				return false;
				echo 'addIp false';
			}

		} catch (PDOException $ex){
			echo "Erro gerado ".$ex->getMessage();
		}
	}

	function consultarIp(){
		global $db;
		try{
			$sessionHash = $this->seguranca->newSessionHash();
			$id = $_SESSION['usuario']['id'];
			$sql = "SELECT IP FROM USUARIOS WHERE ID = ? AND IP = ?";
			$res = $db->query($sql,array($id,$sessionHash));
			if($res){
				return false;
			}else{
				return true;
			}
		} catch (PDOException $ex){
			echo "Erro gerado ".$ex->getMessage();
		} 
	}

	function statusLogado(){
		if(!empty($_SESSION['usuario']['login']) && $_SESSION['usuario']['login'] == true && $this->seguranca->autentificarSession()){
			header('Location: restrito.php');
			exit;
		}
	}

	function statusDeslogado(){
		if(empty($_SESSION['usuario']['login']) || !$this->seguranca->autentificarSession()){
			header('Location: http://localhost:3000/');
			exit;
		} elseif($this->consultarIp()){
			session_destroy();
			unset($_SESSION);
			header('Location: http://localhost:3000/');
			exit;
		}
	}

	function deslogar(){
		if(filter_input(INPUT_GET, 'deslogar', FILTER_SANITIZE_STRING) == 'sim'){
			session_destroy();
			unset($_SESSION);
			header('Location: http://localhost:3000/');
			exit;
		}
	}

}