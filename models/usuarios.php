<?php

	class Usuario{

		private $bd;
		private $seguranca;

		public function __construct(){
		}

		public function traerUsuarios(){
			try{
				global $db;
				$sql = "
					SELECT 
					U.ID,
					U.CEDULA,
					U.NOMBRE,
					U.CORREO,
					R.NOMBRE ROL,
					U.USUARIO,
					U.CONTRASENA,
					U.IP,
					U.F_CREATE,
					U.U_CREATE,
					U.F_UPDATE,
					U.U_UPDATE,
					U.ACTIVO
					FROM USUARIOS U, ROLES R
					WHERE 1=1
					AND U.ID_ROL = R.ID
				";
				$res = $db->query($sql);
				return $res;
			}catch(Exception $e){
				die($e->getMessage());
				return 'Error en la consulta de los usuarios';
			}

		}

		public function crearUsuario($datos){

			try{
				global $db;
				$sql="
					INSERT INTO USUARIOS 
					(ID, CEDULA, NOMBRE, CORREO, USUARIO, CONTRASENA, ID_ROL, IP, F_CREATE, U_CREATE, F_UPDATE, U_UPDATE, ACTIVO) 
					VALUES 
					(NULL, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, NOW(), ?, ?)
				";
				$db->query($sql,array(
					$datos['CEDULA'],
					$datos['NOMBRE'],
					$datos['CORREO'],
					$datos['ID_ROL'],
					$datos['USUARIO'],
					$datos['CONTRASENA'],
					$datos['IP'],
					$datos['ID_USUARIO'],
					$datos['ID_USUARIO'],
					$datos['ACTIVO']
				));
				return 'Usuario Creado';
			}catch(Exception $e){
				die($e->getMessage());
				return 'Falla al crear el usuario';
			}

		}

		public function modificarUsuario($datos){

			try{
				global $db;
				$sql="
					UPDATE USUARIOS SET 
					CEDULA = ?, 
					NOMBRE = ?, 
					CORREO = ?, 
					ID_ROL = ?, 
					USUARIO = ?, 
					CONTRASENA = ?, 
					IP = ?, 
					F_UPDATE = NOW(), 
					U_UPDATE = ?, 
					ACTIVO = ?
				";
				$db->query($sql,array(
					$datos['CEDULA'],
					$datos['NOMBRE'],
					$datos['CORREO'],
					$datos['ID_ROL'],
					$datos['USUARIO'],
					$datos['CONTRASENA'],
					$datos['IP'],
					$datos['ID_USUARIO'],
					$datos['ACTIVO']
				));
				return 'Usuario Modificado';
			}catch(Exception $e){
				die($e->getMessage());
				return 'Falla al modificar el usuario';
			}
		}

		public function eliminacionUsuario($datos){

			try{
				global $db;
				$sql="
					UPDATE USUARIOS SET 
					ACTIVO = ?
					WHERE 1=1
					AND USUARIO = ?
				";
				$db->query($sql,array(
					$datos['ACTIVO'],
					$datos['ID_USUARIO']
				));
			}catch(Exception $e){
				die($e->getMessage());
				return 'Falla al desactivar el usuario';
			}

		}

	}

?>
