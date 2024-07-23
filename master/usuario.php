<?php
ob_start();
session_start();
if((!isset ($_SESSION['cod_id']) == true)) { unset($_SESSION['cod_id']); header('location: ./'); }
require_once "../funcoes/Conexao.php";
require_once "../funcoes/Key.php";

$config    = $connect->query("SELECT * FROM adm");
$config    = $config->fetch(PDO::FETCH_OBJ);

if(isset($_POST["idcpes"]))  {
$idc 			= $_POST['idcpes'];
$nome 			= $_POST['nome'];
$cpf     		= $_POST['cpf'];
$celular 		= $_POST['celular'];
$senha 			= $_POST['senha']; 
if($senha) { 
$senha 			= sha1($_POST['senha']); 
$editarcad = $connect->query("UPDATE adm SET nome='$nome', login='$cpf', celular='$celular', senha='$senha' WHERE Id='1'");
} else {
$editarcad = $connect->query("UPDATE adm SET nome='$nome', login='$cpf', celular='$celular' WHERE Id='1'");
}
if ( $editarcad ) { header("location: sair.php"); exit; } else { header("location: sair.php"); exit; }
} 
?>
<!DOCTYPE html>
<html lang="en">
  <head><meta charset="windows-1252">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema de PDV.">
    <meta name="author" content="MDINELLY">
    <title>PAINEL ADMINISTRATIVO</title>
    <link href="../basecard/lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../basecard/lib/Ionicons/css/ionicons.css" rel="stylesheet">
	<link href="../basecard/lib/datatables/css/jquery.dataTables.css" rel="stylesheet">
    <link href="../basecard/lib/select2/css/select2.min.css" rel="stylesheet">
	<link href="../basecard/lib/SpinKit/css/spinkit.css" rel="stylesheet">
    <link rel="stylesheet" href="../basecard/css/slim.css">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
  <body>
    
	<div class="slim-navbar">
      <div class="container">
        <ul class="nav">
		  <li class="nav-item">
            <a class="nav-link" href="#">
              <i class="icon ion-ios-home-outline"></i>
              <span>PAINEL ADMINISTRATIVO</span>
            </a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" href="#">
              <span>
                <progress value="0" max="120" id="progressBar"></progress>
			  </span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="sair.php">
              <i class="icon ion-ios-analytics-outline"></i>
              <span>SAIR</span>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="slim-mainpanel">
      <div class="container">
	  <?php if(isset($_GET["ok"])){ ?>
				<div class="alert alert-success mg-t-20" role="alert">
            	<i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Alterado com sucesso.
          		</div>
				<?php }?>

		 <div class="card card-dash-one mg-t-20">
          <div class="row no-gutters">
              
			<?php
			$todia = $connect->query("SELECT id FROM config");
			?>
			<div class="col-lg-3">
              <i class="icon ion-ios-pie-outline"></i>
              <div class="dash-content">
                <label class="tx-success">Empresas Cadastradas</label>
                <h2><?=$todia 	= $todia->rowCount(); ?></h2>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
			
			<?php
			$dtat = date("Y-m-d");
			$todiav = $connect->query("SELECT id FROM config WHERE expiracao > '$dtat' AND status ='1'");
			?> 
            <div class="col-lg-3">
			  <i class="icon ion-ios-stopwatch-outline"></i>
              <div class="dash-content">
                <label class="tx-purple">Empresas Ativas</label>
                <h2><?=$todiav 	= $todiav->rowCount(); ?></h2>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
			
			<?php
			$dtat = date("Y-m-d");
			$todiav = $connect->query("SELECT id FROM config WHERE expiracao < '$dtat'");
			?> 
            <div class="col-lg-3">
			  <i class="icon ion-ios-stopwatch-outline"></i>
              <div class="dash-content">
                <label class="tx-warning">Empresas Vencidas</label>
                <h2><?=$todiav 	= $todiav->rowCount(); ?></h2>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
			
			<?php
			$todiab = $connect->query("SELECT id FROM config WHERE status='3'");
			?> 
            <div class="col-lg-3">
			  <i class="icon ion-ios-analytics-outline"></i>
              <div class="dash-content">
                <label class="tx-danger">Empresas Bloqueadas</label>
                <h2><?=$todiab 	= $todiab->rowCount(); ?></h2>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
			
          </div><!-- row -->
        </div><!-- card -->
		  
		<div class="section-wrapper mg-t-20">
          <label class="section-title">
		  <a href="controle.php" class="btn btn-success btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> -  
		  Dados do Administrador
		  </label>
		  <hr>
		  
		  <form action="" method="post">
		  <input type="hidden" name="idcpes" value="<?php print $config->id; ?>">
          <div class="row">
		  
		  		<div class="col-md-3">
					<div class="form-group">
						<label>Nome</label>
						<input type="text" class="form-control" name="nome" value="<?php print $config->nome; ?>">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Login</label>
						 <input type="text" class="form-control" name="cpf" value="<?php print $config->login; ?>" maxlength="11">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Celular Comercial</label>
						 <input type="text" class="form-control" name="celular" value="<?php print $config->celular; ?>" maxlength="11">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<label>Senha</label>
						 <input type="password" class="form-control" name="senha" maxlength="8">
					</div>
				</div> 
		  
		  </div>
				
				
		  <div class="row">
				
				<div class="col-md-12">
					<div class="form-group">
						<center><button type="submit" class="btn btn-info">SALVAR</button></center>
						
						<br>
					</div>
				</div>
		  
		  	</div>
		  
		  </form>
		  
        </div> 
		
		<br>
		<br>

      </div><!-- container -->
    </div><!-- slim-mainpanel -->
	
	 

    <script src="../basecard/lib/jquery/js/jquery.js"></script>
     <script src="../basecard/lib/datatables/js/jquery.dataTables.js"></script>
    <script src="../basecard/lib/datatables-responsive/js/dataTables.responsive.js"></script>
    <script src="../basecard/lib/select2/js/select2.min.js"></script>
<script>
var timeleft = 120;
var downloadTimer = setInterval(function(){
  document.getElementById("progressBar").value = 120 - timeleft;
  timeleft -= 1;
  if(timeleft <= 0){
    clearInterval(downloadTimer);
  }
}, 1000);
</script>

  </body>
</html>