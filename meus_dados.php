<?php include('seguranca.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<title>Alteração de dados do usuário</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php

$sucesso = null;
$erro = false;
$nome = $_POST['nome'];
$email = $_POST['email'];
$apelido = $_POST['apelido'];
$novaSenha = $_POST['novaSenha'];

$sql = null;

//Conectando
$cnx = mysql_connect("localhost", "admin123", "admin123")
  or die("Could not connect: " . mysql_error());
mysql_select_db("tracker2", $cnx);

if ($nome != null and ($email != null or $apelido != null)) {

	if ($email != null and strpos($email, "@") === false) 
	{
		$sucesso = "<span id=\"alertaCadastro\" style=\"color:red\">Digite um e-mail.</span>";
		//$acao = "novoUsuario";
		$erro = true;
	}
	
	if (!$erro) {
	
		$nome = mysql_real_escape_string($nome);
		$email = mysql_real_escape_string($email);
		$apelido = mysql_real_escape_string($apelido);
		$novaSenha = mysql_real_escape_string($novaSenha);
	
		if ($novaSenha != null) 
			$sql = "UPDATE cliente set nome = '$nome', email = '$email', apelido = '$apelido', senha = '". md5($novaSenha) ."' WHERE id = $cliente";
		else
			$sql = "UPDATE cliente set nome = '$nome', email = '$email', apelido = '$apelido' WHERE id = $cliente";		

		if (!mysql_query($sql, $cnx))
		{
			// Se der erro, envia alerta que houve falha
			if (mysql_error() == "Duplicate entry '". $email ."' for key 'email_unq'" or mysql_error() == "Duplicate entry '". $email ."' for key 2")
			{
				$sucesso = "<span id=\"alertaCadastro\" style=\"color:red\">E-mail já existe!</span>";
			}
			else 
			{
				if (mysql_error() == "Duplicate entry '". $apelido ."' for key 'apelido_unq'" or mysql_error() == "Duplicate entry '". $apelido ."' for key 3") {
					$sucesso = "<span id=\"alertaCadastro\" style=\"color:red\">Apelido já existe, tente outro!</span>";
				} 
				else 
				{
					$sucesso = "<span id=\"alertaCadastro\" style=\"color:red\">Falha no cadastro.</span>";			
				}
			}
			//die('Error: ' . mysql_error());
		}
		else
		{
			$sucesso = "<span id=\"alertaCadastro\" style=\"color:black\">Dados atualizados com sucesso!</span>";
		}
	}
}

$resUsuario = mysql_query("select email, nome, apelido from cliente where id = '$cliente' limit 1");
for ($k=0; $k < mysql_num_rows($resUsuario); $k++) {
	$rowUsuario = mysql_fetch_assoc($resUsuario);
	$nome = $rowUsuario[nome];
	$email = $rowUsuario[email];
	$apelido = $rowUsuario[apelido];
}

mysql_close($cnx);
?>
<style>

body, table {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #A6A6A6;
}

.menu {
	border-color: #CCCCCC;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 20px;
	font-weight: bold;
	color: #CCCCCC;
	background-color: #FFFFFF;
	border-right: 1px solid #CCCCCC;
	border-top: 1px solid #CCCCCC;
	border-bottom: 1px solid #CCCCCC;
	padding: 5px;
	cursor: hand;
}
.menu-sel {
	border-color: #CCCCCC;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 20px;
	font-weight: bold;
	color: #CCCCCC;
	background-color: #F7F7F7;
	border-right: 1px solid #CCCCCC;
	border-top: 1px solid #CCCCCC;
	padding: 5px;
	cursor: hand;
}
.tb-conteudo {
	border-right: 1px solid #CCCCCC;
	border-bottom: 1px solid #CCCCCC;
	border-right-color: #CCCCCC;
	border-bottom-color: #CCCCCC;
}
.conteudo {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: normal;
	color: #A6A6A6;
	background-color: #F7F7F7;
	padding: 5px;
/*height: 435px;*/	height: 100%;
	width: auto;
	filter:alpha(opacity=90); 
  	-moz-opacity: 0.90; 
   	opacity: 0.90; 
}

.textoEsquerda {
	text-align: right;
	padding-right:5px;
	width:10%;
}

.campoNovoVeiculo {
	border: 1px solid #C0C0C0;
}

.dicaCadastro {
	font-size:xx-small;
}

.btnAcao {
	border: 1px solid #808080;
	background-color: #E0E0E0;
}

</style>
<script type="text/javascript" src="javascript/alterarVeiculo.js"></script>
<script language="JavaScript">

	function stAba(menu,conteudo)
	{
		this.menu = menu;
		this.conteudo = conteudo;
	}

	var arAbas = new Array();
	arAbas[0] = new stAba('td_cadastro','div_cadastro');
	//arAbas[1] = new stAba('td_consulta','div_consulta');
	//arAbas[2] = new stAba('td_manutencao','div_manutencao');

	function AlternarAbas(menu,conteudo)
	{
		for (i=0;i<arAbas.length;i++)
		{
			m = document.getElementById(arAbas[i].menu);
			m.className = 'menu';
			c = document.getElementById(arAbas[i].conteudo)
			c.style.display = 'none';
		}
		m = document.getElementById(menu)
		m.className = 'menu-sel';
		
		c = document.getElementById(conteudo)
		c.style.display = '';
	}
	
	function esconderAlerta() {
		try
		  {
		  	var existeSpan = document.getElementById('alertaCadastro');
		  	existeSpan.style.display='none';
		  }
		catch(err)
		  {
			  //Abafo se o campo não existir
		  }
  	}
	
</script>

</head>

<body onLoad="AlternarAbas('td_cadastro','div_cadastro'); setTimeout('esconderAlerta()', 10000); " 
	style=" background-position: right bottom; height:auto; border-left:thin; border-left-style: solid; 
			border-left-width: 1px; border-left-color: #CCCCCC; margin-left:0px; margin-top:-17px; 
			background-image:url('imagens/fundo_logo_webarch.png'); background-repeat: no-repeat;">

<h2 align="center" style="font-size:20px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; color: #666666;">Meus dados</h2>
<table width="80%" height="80%" cellspacing="0" cellpadding="0" border="0" style="border-left: 1px solid #000000; border-left-color: #CCCCCC;" align="center">

	<tr>
		<td width="100" class="menu" id="td_cadastro"
		onclick="AlternarAbas('td_cadastro','div_cadastro')" style="height: 7px">
			Cadastro
		</td>
		<!--td width="100" class="menu" id="td_consulta"
		onclick="AlternarAbas('td_consulta','div_consulta')" style="height: 7px">
			Consulta
		</td-->
		<!--td width="100" class="menu" id="td_manutencao"
		onclick="AlternarAbas('td_manutencao','div_manutencao')" style="height: 7px">
			Manutenção
		</td-->
		<td style="border-bottom: 1px solid #CCCCCC; height: 7px;">
			&nbsp;</td>
		<td style="height: 7px"></td>
	</tr>
	<tr>
		<td class="tb-conteudo" colspan="4">
			<div id="div_cadastro" class="conteudo" style="display:block;">
				<div>
					Altere seus dados cadastrados <br />

					<form name="alteraDados" method="post" action="meus_dados.php" autocomplete="off">
						<table style="width: 70%" cellspacing="6" cellpadding="0">
							<tr>
								<td colspan="2">
									<?php echo $sucesso ?>
									<br />
								</td>
							</tr>						
							<tr>
								<td class="textoEsquerda" width="30%">Nome:</td>
								<td><input name="nome" maxlength="50" size="40" type="text" class="campoNovoVeiculo" value="<?php echo $nome ?>" readonly />
									<span class="dicaCadastro"></span>
								</td>
							</tr>
							<tr>
								<td class="textoEsquerda">e-mail:</td>
								<td><input name="email" maxlength="50" size="30" type="text" class="campoNovoVeiculo" value="<?php echo $email ?>" />
									<span class="dicaCadastro"></span>
								</td>
							</tr>
							<tr>
								<td class="textoEsquerda">Apelido:</td>
								<td><input name="apelido" maxlength="30" size="20" type="text" class="campoNovoVeiculo" value="<?php echo $apelido ?>" />
									<span class="dicaCadastro">Dica: Apelido ou e-mail servem para login</span>
								</td>
							</tr>
							<tr>
								<td><br/></td>
								<td><br/></td>
							</tr>
							<tr>
								<td>Alterar senha</td>
								<td><br/><br/></td>
							</tr>
							<tr>
								<td nowrap="nowrap" class="textoEsquerda">Nova senha:</td>
								<td><input id="novaSenha" name="novaSenha" maxlength="50" size="20" type="password" class="campoNovoVeiculo" />
									<span class="dicaCadastro">Dica: Altere sua senha atual</span>
								</td>
							</tr>
							<tr>
								<td nowrap="nowrap" class="textoEsquerda">Confirme senha:</td>
								<td><input id="confirmeSenha" name="confirmeSenha" maxlength="50" size="20" type="password" class="campoNovoVeiculo" />
									<span class="dicaCadastro">Dica: Confirme sua senha atual</span>
								</td>
							</tr>
							<tr>
								<td><br/></td>
								<td><br/></td>
							</tr>
							<tr>
								<td colspan="2">
									<input name="btnCadastrar" type="submit" value="Salvar" class="btnAcao" onclick=" if (document.getElementById('novaSenha').value != document.getElementById('confirmeSenha').value) { alert('Confirmação de senha está errada!');return false } else { return true; } " />
									&nbsp;
									<a href="mapa.php" style="color:#0099FF">Cancelar</a>
								</td>
							</tr>
						</table>
					</form>
					
				</div>
			</div>

			<!--div id="div_consulta" class="conteudo" style="display: none;">
				<div>
					Listagem e Alteração de bens <br />
					
					<form name="listaBens" method="post" action="menu_novo_veiculo.php">
					<table cellspacing="6" cellpadding="0">
							<tr>
								<td colspan="5">
									<br />
								</td>
							</tr>
					</table>
					</form>
					<br />
					<a href="mapa.php" style="color:#0099FF">Cancelar</a>
				</div>
			</div-->
			<!--div id="div_manutencao" class="conteudo" style="display: none">
				MANUTENÇÃO
			</div-->
		</td>
	</tr>
</table>
<br /><br /><br />
</body>
</html>

