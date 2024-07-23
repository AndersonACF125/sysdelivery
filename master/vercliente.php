<?php
ob_start();
session_start();
if((!isset ($_SESSION['cod_id']) == true)) { unset($_SESSION['cod_id']); header('location: ./'); }
require_once "../funcoes/Conexao.php";
require_once "../funcoes/Key.php";

$config    = $connect->query("SELECT * FROM adm");
$config    = $config->fetch(PDO::FETCH_OBJ);

$codigop  = $_POST['codigop'];

$cliente    = $connect->query("SELECT * FROM config WHERE id='$codigop'");
$cliente    = $cliente->fetch(PDO::FETCH_OBJ);

$data_inicial = date("Y-m-d");;
$data_final = $cliente->expiracao;;
$diferenca = strtotime($data_final) - strtotime($data_inicial);
$prazo = floor($diferenca / (60 * 60 * 24)); 

if(isset($_POST["codicli"]))  {
$codicli = $_POST['codicli'];
$nomeempresa = $_POST['nomeempre'];
$updatesenha = $connect->query("UPDATE config SET senha='779a923d69b2e072747b11975ba86949de167037' WHERE id='$codicli'");
if($updatesenha){ header("location: controle.php?senhaok=ok&emp=".$nomeempresa.""); exit;}
}

if(isset($_POST["codcliente"]))  {
$statusf = $_POST['status'];
$codigop = $_POST['codcliente'];
$pacote = $_POST['pacote'];

if($pacote){
$dataatual = $_POST['dataatual'];
$dataexpira = date('Y-m-d', strtotime("+$pacote days",strtotime($dataatual)));
$update = $connect->query("UPDATE config SET status='$statusf', expiracao='$dataexpira' WHERE id='$codigop'");
} else {
$update = $connect->query("UPDATE config SET status='$statusf' WHERE id='$codigop'");
}
if($update){ header("location: controle.php?statusok=ok&emp=".$nomeempresa.""); exit;}
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
			  $dia 	 = date("d-m-Y"); 
			  $todia = $connect->query("SELECT vtotal, SUM(vtotal) AS soma1 FROM pedidos WHERE idu='".$codigop."' AND status='5'");
			  $todia = $todia->fetch(PDO::FETCH_OBJ);
			  ?>
			<div class="col-lg-4">
              <i class="icon ion-ios-pie-outline"></i>
              <div class="dash-content">
                <label class="tx-success">Total de Pedidos Finalizados</label>
                <h2>R$: <?php echo number_format($todia->soma1, 2, '.', '.'); ?></h2>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
			<?php
			  function formatCurrency($num) {
					if (preg_match('/' . "," . '/', $num)) {
						return formatValorMoedaDatabase($num);	
					} else {
						$num = formatMoedaBr($num);
						return formatValorMoedaDatabase($num);	
					}
			  }
			  function formatValorMoedaDatabase($num) {
					return str_replace(',','.',preg_replace('#[^\d\,]#is','', $num)); 
			  }
		
			  function formatMoedaBr($num) {
					return number_format($num, 2, ',', '.'); 
			  }
		
		
			  $status1 = $connect->query("SELECT vtotal FROM pedidos WHERE idu='".$codigop."' AND status='1'");
			  $status2 = $connect->query("SELECT vtotal FROM pedidos WHERE idu='".$codigop."' AND status='2'");
			  $status3 = $connect->query("SELECT vtotal, SUM(vtotal) AS soma8 FROM pedidos WHERE idu='".$codigop."' AND status='3'");
			  $status4 = $connect->query("SELECT vtotal, SUM(vtotal) AS soma9 FROM pedidos WHERE idu='".$codigop."' AND status='4'");
		
			  $aguar = 0;
			  while ($status1x = $status1->fetch(PDO::FETCH_OBJ)) {
				$aguar += formatCurrency($status1x->vtotal);
			  }
			  while ($status2x = $status2->fetch(PDO::FETCH_OBJ)) {
				$aguar += formatCurrency($status2x->vtotal);
			  }
			  while ($status3x = $status3->fetch(PDO::FETCH_OBJ)) {
				$aguar += formatCurrency($status3x->vtotal);
			  }
			  while ($status4x = $status4->fetch(PDO::FETCH_OBJ)) {
				$aguar += formatCurrency($status4x->vtotal);
			  }
					  
			  ?>
            <div class="col-lg-4">
			  <i class="icon ion-ios-stopwatch-outline"></i>
              <div class="dash-content">
                <label class="tx-warning">Total de Pedidos na Fila</label>
                <h2>R$: <?php echo number_format($aguar, 2, ',', '.'); ?></h2>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
			
			  <?php
			  $final = $connect->query("SELECT vtotal, SUM(vtotal) AS soma3 FROM pedidos WHERE idu='".$codigop."' AND status='6'");
			  $final = $final->fetch(PDO::FETCH_OBJ);
			  ?>
            <div class="col-lg-4">
			  <i class="icon ion-ios-analytics-outline"></i>
              <div class="dash-content">
                <label class="tx-danger">Total de Pedidos Cancelados</label>
                <h2>R$: <?php echo number_format($final->soma3, 2, '.', '.'); ?></h2>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
			
          </div><!-- row -->
        </div><!-- card -->
		  
		<div class="section-wrapper mg-t-20">
          <label class="section-title">
		  <a href="controle.php" class="btn btn-success btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> -  
		  Cliente - <?php print $cliente->nomeempresa; ?>
		  </label>
		  <hr>
		  
          <div class="row">
		  
		  		<div class="col-md-5">
					<div class="form-group">
						<label>Nome da Empresa</label>
						<input type="text" class="form-control" value="<?php print $cliente->nomeempresa;?>" disabled>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="form-group">
						<label>Data do Cadastro</label>
						<input type="text" class="form-control" value="<?php print date("d / m / Y", strtotime($cliente->datacad));?>" disabled>
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="form-group">
						<label>Nº do Celular</label>
						<input type="text" class="form-control" value="<?php print $cliente->celular;?>" disabled>
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="form-group">
						<label>Nome do Contato</label>
						<input type="text" class="form-control" value="<?php print $cliente->nomeadmin;?>" disabled>
					</div>
				</div>
			
		  </div>
		  
		  <div class="row">
		  
		  		<div class="col-md-2">
					<div class="form-group">
						<label>Data de Expiração</label>
						<input type="text" class="form-control" value="<?php print date("d / m / Y", strtotime($cliente->expiracao));?>" disabled>
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="form-group">
						<label>Dias Restantes</label>
						 <?php if($prazo >=1){?>
						<input type="text" class="form-control" value="<?php print $prazo;?>" disabled>
						<?php }else{?>
						<input type="text" class="form-control" value="Conta Expirada" disabled style="background-color:#FF6600; color:#FFFFFF">
						<?php }?>
					</div>
				</div>
				
				<div class="col-md-8">
					<div class="form-group">
						<label>Endereço</label>
						<input type="text" class="form-control" value="<?php print $cliente->rua;?> - <?php print $cliente->numero;?>, <?php print $cliente->bairro;?> - <?php print $cliente->cidade;?>/<?php print $cliente->uf;?> " disabled>
					</div>
				</div>
				
		  </div>
		  <hr/>	
		  
		        <?php
				$sts = $cliente->status;
				if($sts == 1){$statusxx = "Ativo";}
				if($sts == 2){$statusxx = "Cliente Novo";}
				if($sts == 3){$statusxx = "Bloqueado";}
				?>
				 
				 <form method="post" action="">
				 <input type="hidden" name="codcliente" value="<?php print $cliente->id; ?>">
				 <input type="hidden" name="dataatual" value="<?php print $cliente->expiracao; ?>">
				 
		  <div class="row">
				
				<div class="col-md-2">
					<div class="form-group">
						<label>Conta do cliente:</label>
						<select name="status" required class="form-control status">
						  <option value="<?php print $sts;?>" selected><b><?php print $statusxx;?></b></option>
						  <option></option>
						  <option value="1">Ativar</option>
						  <option value="3">Bloquear</option>
						</select>
					</div>
				</div>
				
				<div class="col-md-2">
					<div class="form-group">
						<label>Status do cliente:</label>
						<?php if($cliente->status == 1 ) { ?>
						<input type="text" class="form-control" value="ATIVO" disabled style="background-color:#009900; color:#FFFFFF">
						<?php } ?>
						<?php if($cliente->status == 2 ) { ?>
						<input type="text" class="form-control" value="NOVA CONTA" disabled style="background-color:#3366CC; color:#FFFFFF">
						<?php } ?>
						<?php if($cliente->status == 3 ) { ?>
						<input type="text" class="form-control" value="BLOQUEADO" disabled style="background-color:#FF6600; color:#FFFFFF">
						<?php } ?>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="form-group">
						<label>Renovar Pacote</label>
						<select name="pacote" class="form-control status">
						  <option value="" disabled selected></option>
						  <option value="30">+ 30 dias (1 mês)</option>
						  <option value="90">+ 90 dias (3 meses)</option>
						  <option value="150">+ 180 dias (6 meses)</option>
						  <option value="365">+ 365 dias (Anual)</option>
						</select>
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