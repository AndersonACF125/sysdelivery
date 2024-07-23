<?php
ob_start();
session_start();
require_once "../funcoes/Conexao.php";
require_once "../funcoes/Key.php";
if(isset($_POST["cpflogin"]))  {
$login_cpf 	  = $_POST['cpflogin'];
$login_sn1 	  = $_POST['senhalogin'];
$login_snh 	  = sha1($login_sn1); 
$buscauser    = $connect->query("SELECT id FROM adm WHERE login='$login_cpf' AND senha='$login_snh'");
$count_user   = $buscauser->rowCount();
$dadoscliente = $buscauser->fetch(PDO::FETCH_OBJ);
if($dadoscliente->id == 1) {
$_SESSION["cod_id"] = $dadoscliente->id; 
header("location: controle.php"); 
exit;
} else {
header("location: ./?erro=login");
}
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Painel Administrativo Delivery.">
    <meta name="author" content="MDINELLY">
    <title>:: PAINEL ADMINISTRATIVO ::</title>
    <link href="../basecard/lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../basecard/lib/Ionicons/css/ionicons.css" rel="stylesheet">
    <link rel="stylesheet" href="../basecard/css/slim.css">

  </head>
  <body>

    <div class="signin-wrapper">

      <div class="signin-box" align="center">
        <h3 class="slim-logo">ADM Master<span></h3>
        <form action="" method="post">
		<div class="form-group">
          <input type="text" class="form-control" name="cpflogin" placeholder="Login" maxlength="14" required>
        </div><!-- form-group -->
        <div class="form-group">
          <input type="password" class="form-control" name="senhalogin" placeholder="Senha" maxlength="8" required>
        </div><!-- form-group -->
		<?php if(isset($_GET["erro"]))  { ?>
		<div class="form-group" style="color:#FF0000">
			<i class="fa fa-certificate"></i> Login ou Senha incorreto.
		</div>
		<?php } ?>
        <button type="submit" class="btn btn-primary btn-block btn-signin">Entrar</button>
		</form>
      </div>
    </div>
  </body>
</html>
<?php
ob_end_flush();
?>