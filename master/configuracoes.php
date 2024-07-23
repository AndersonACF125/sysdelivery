<?php
ob_start();
session_start();
if((!isset ($_SESSION['cod_id']) == true)) { unset($_SESSION['cod_id']); header('location: ./'); }
require_once "../funcoes/Conexao.php";
require_once "../funcoes/Key.php";
$config    = $connect->query("SELECT * FROM adm");
$config    = $config->fetch(PDO::FETCH_OBJ);

if(isset($_POST["alterc"]))  {
$statusf 	= $_POST['status'];
$diasxx 	= $_POST['diasx'];
$nsite 		= $_POST['nsite'];
$nurl 		= $_POST['nurl'];
$bloqueioautomatico = $_POST['bloqueioautomatico'];
$bloqueiolink = $_POST['bloqueiolink'];
$link = $_POST['link'];
$update = $connect->query("UPDATE adm SET novocliente='$statusf', dias='$diasxx', bloquear='$bloqueioautomatico', statuslink='$bloqueiolink', linkpgmto='$link', nomedosite='$nsite', urlsite='$nurl' WHERE Id='1'");
if($update){ header("location: configuracoes.php?ok=ok"); exit;}
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
		  CONFIGURAÇÕES
		  </label>
		  <hr>
		  <form method="post" action="">
		  <input type="hidden" name="alterc" value="ok">
          <div class="row">
		  <?php
				$sts = $config->novocliente;
				if($sts == 1){$statusxx = "Liberar Automático";}
				if($sts == 2){$statusxx = "Liberação Manual";}
				
				$stsa = $config->bloquear;
				if($stsa == 1){$statusxxa = "Ativo";}
				if($stsa == 2){$statusxxa = "Bloqueado";}
				
				$stsab = $config->statuslink;
				if($stsab == 1){$statusxxab = "Liberado no Painel Cliente";}
				if($stsab == 2){$statusxxab = "Bloqueado no Painel Cliente";}
				
				?>
				
		  		<div class="col-md-3">
					<div class="form-group">
						<label>Novo Cadastro?</label>
						<select name="status" required class="form-control status">
						  <option value="<?php print $sts;?>" selected><b><?php print $statusxx;?></b></option>
						  <option></option>
						  <option value="1">Liberar Automático</option>
						  <option value="2">Liberação Manual</option>
						</select>
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="form-group">
						<label>Dias de Teste?</label>
						<input type="text" name="diasx" class="form-control" value="<?php print $config->dias;?>" required >
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="form-group">
						<label>Bloqueio Automático?</label>
						<select name="bloqueioautomatico" required class="form-control status">
						  <option value="<?php print $stsa;?>" selected><b><?php print $statusxxa;?></b></option>
						  <option></option>
						  <option value="1">Sim</option>
						  <option value="2">Não</option>
						</select>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="form-group">
						<label>Link de Pagamento?</label>
						<select name="bloqueiolink" required class="form-control status">
						  <option value="<?php print $stsab;?>" selected><b><?php print $statusxxab;?></b></option>
						  <option></option>
						  <option value="1">Liberar no Painel Cliente</option>
						  <option value="2">Bloquear no Painel Cliente</option>
						</select>
					</div>
				</div>
				
				
			</div>
			<hr>
			<div class="row">
		 		
				<div class="col-md-6">
					<div class="form-group">
						<label>Nome do Site</label>
						 <input type="text" class="form-control" name="nsite" value="<?php print $config->nomedosite; ?>" maxlength="60">
						 <p>Ex: SUPER DELIVERY</p>
					</div>
				</div> 
				<div class="col-md-6">
					<div class="form-group">
						<label>URL do Site</label>
						 <input type="text" class="form-control" name="nurl" value="<?php print $config->urlsite; ?>" maxlength="120">
						 <p>Ex: https://seusite.com.br</p>
					</div>
				</div> 
				
		    </div>
			<hr>
			<div class="row">
				
				<div class="col-md-12">
					<div class="form-group">
						<label>Código do Link de Pagamento</label>
						<textarea name="link" rows="5" cols="33" class="form-control" requiered><?php print $config->linkpgmto;?></textarea>
						<br>
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