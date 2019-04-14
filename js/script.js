$(document).ready(function () {

	$('#form').submit(function(){

		var login = $('#login');
		var senha = $('#senha');
		var controle = true;
		var caixaLogin = $('#avisoLogin');

		if(login.val() === ""){
			caixaLogin.html('El campo login es Obligatorio');
			controle = false;
		}else{
			caixaLogin.empty();
		}

		var caixaSenha = $('#avisoSenha');

		if(senha.val() === ""){
			caixaSenha.html("El campo contraseña es obligatorio");
			controle = false;
		}else if(senha.val().length < 6){
			caixaSenha.html("la contraseña debe tener 6 caracteres como minimo");
			controle = false;
		}else{
			caixaSenha.empty();
		}

		if(controle){
			logar();
		}
		return false;

	});	

	function logar(){
		var url = "includes/validar_login.php";
		var dadosForm = $('#form').serialize();
		var btn = $('#btnLogar');
		var txtAviso = $('#txtAviso');
		var token = $('#token');

		$.ajax({
			url: url,
			type: 'POST',
			data: dadosForm,
			cache: false,
			dataType: 'JSON',

			beforeSend: function(){
				btn.val('Logueando...');
				btn.attr('disabled', 'disabled');
				txtAviso.empty();
			},

			success: function(resultado){

				if(resultado.status){
					btn.val('Redirecionando...');
					window.location = "restrito.php";
				}else{
					switch(resultado.alerta){
						case 0: txtAviso.html('Login ok'); break;
						case 1: txtAviso.html('Login o Contrasena inválido'); break;
						case 2: txtAviso.html('Token inválido'); break;
						case 3: txtAviso.html('Origen de solicitud no valido');
					}

					// Novo Token
					token.val(resultado.novoToken);
					btn.val('Entrar');
				}

				btn.removeAttr('disabled');				
			},

			error: function(){
				txtAviso.html('Error en envio de solicitud');
				btn.removeAttr('disabled');
				btn.val('Entrar');
			}

		});
	}
});