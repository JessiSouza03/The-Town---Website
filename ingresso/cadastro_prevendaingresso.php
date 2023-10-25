<?php
	$erro = null;
	$valido = false;		
	
	if(isset($_REQUEST["validar"]) && $_REQUEST["validar"]==true)
	{
		if(strlen(utf8_decode($_POST["nome_cliente"]))<3)
		{
			$erro = "Nome inválido, preencha o campo corretamente";
		}
		else if(is_numeric($_POST["idade_cliente"])==false)
		{
			$erro = "O campo idade deve ser numérico";
		}
		else if(strlen(utf8_decode($_POST["cpf_cliente"]))<11)
		{
			$erro = "CPF inválido, preencha o campo CPF corretamente";
		}
		else if(strlen(utf8_decode($_POST["email_cliente"]))<3)
		{
			$erro = "E-mail inválido, preencha o campo email corretamente";
		}
		
		else
		{
			$valido = true;

			try
			{
				$connection = new PDO("mysql:host=localhost;dbname=TheTown", "root", "root");
				$connection->exec("set names utf8");
			}
			catch(PDOException $e)
			{
				echo "Falha : " . $e->getMessage();
				exit();
			}
			/*incio do codigo de conexao*/


		$sql = "INSERT INTO prevendaingresso
					(cod_prevenda, nome_cliente, idade_cliente, cpf_cliente, email_cliente)
					VALUES (?, ?, ?, ?, ?)";
					
			$stmt = $connection->prepare($sql);
			
			$stmt->bindParam(1, $_POST["cod_prevenda"]);
			$stmt->bindParam(2, $_POST["nome_cliente"]);
			$stmt->bindParam(3, $_POST["idade_cliente"]);
			$stmt->bindParam(4, $_POST["cpf_cliente"]);
			$stmt->bindParam(5, $_POST["email_cliente"]);
			
			
			$stmt->execute();
			
			if($stmt->errorCode() != "00000")
			{
				$valido = false;
				$erro = "Erro código " . $stmt->errorCode() . ": ";
				$erro = implode(", ", $stmt->errorInfo());
			}
			/*fim do codigo de conexao*/
		}
	}
	
?>

<html>
	<head>
		<meta charset="UTF-8" /> 
		<title>INGRESSO | The Town</title>
		<link rel="stylesheet" href="cssmtlegala.css"/>
		<div id=interface>
	</head>
	<body>
	<fieldset id=tudo>
			<legend id=tituloprincipal>⠀CADASTRO PARA PREVENDA </legend>
			<?php
			if($valido == true)
			{
				echo "Dados enviados com sucesso !!!";
				?>
				 <p><a href="..\index.html">Menu Principal</a></p>
				<?php
			}
			else
			{
			
			if(isset($erro))
			{
				echo $erro . "<br /><br />";
			}
			?>
			<form method=POST action="?validar=true">
			<p>Nome: <input type=text name=nome_cliente
			<?php if(isset($_POST["nome_cliente"])) {echo "value= '" . $_POST["nome_cliente"] . "'";} ?>
			></p>
			<p>Idade: <input type=text name=idade_cliente
			<?php if(isset($_POST["idade_cliente"])) {echo "value= '" . $_POST["idade_cliente"] . "'";} ?>
			></p>
			<p>CPF: <input type=text name=cpf_cliente
			<?php if(isset($_POST["cpf_cliente"])) {echo "value= '" . $_POST["cpf_cliente"] . "'";} ?>
			></p>
			<p>E-mail: <input id=email type=text name=email_cliente
			<?php if(isset($_POST["email_cliente"])) {echo "value= '" . $_POST["email_cliente"] . "'";} ?>
			></p>

			<p> <input type="checkbox" name="termos" required="required" <?php if(isset($_POST["termos"])) {echo "value= '" . $_POST["termos"] . "'";} ?> >
        		<label>Eu li e aceito os <a href="#">Termos de
            		Serviço.</a>
         	    </label>
		  	</p>
	
		
		<p><input type=reset value="Limpar"> <input type=submit value="Enviar"></p>
		</form>
		</fieldset>
		</div>
		<?php
		}
		?>
	</body>
</html>