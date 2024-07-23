<?php
ob_start();
session_start();
if((!isset ($_SESSION['cod_id']) == true)) { unset($_SESSION['cod_id']); header('location: ./'); }
$cod_id = $_SESSION['cod_id'];
require_once "../funcoes/Conexao.php";
require_once "../funcoes/Key.php";

if(isset($_POST["codigoe"]))  {
$delcliente  	= $connect->query("DELETE FROM config WHERE id='".$_POST['codigoe']."'");
$delbairro   	= $connect->query("DELETE FROM bairros WHERE idu='".$_POST['codigoe']."'");
$delbanner 		= $connect->query("DELETE FROM banner WHERE idu='".$_POST['codigoe']."'");
$delcategorias 	= $connect->query("DELETE FROM categorias WHERE idu='".$_POST['codigoe']."'");
$delfundotopo  	= $connect->query("DELETE FROM fundotopo WHERE idu='".$_POST['codigoe']."'");
$dellogo 		= $connect->query("DELETE FROM logo WHERE idu='".$_POST['codigoe']."'");
$delopcionais 	= $connect->query("DELETE FROM opcionais WHERE idu='".$_POST['codigoe']."'");
$delprodutos 	= $connect->query("DELETE FROM produtos WHERE idu='".$_POST['codigoe']."'");
if ( $delcliente ) { header("location: controle.php"); exit; } else { header("controle.php"); exit; }
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
		  <a href="configuracoes.php" class="btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Configurações</a> 
		  <a href="usuario.php" class="btn btn-info btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Dados do Usuários</a>
		  </label>
		  <hr>
          <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap" width="100%">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Empresa</th>
				  <th>Expira em</th>
                  <th>Cliente</th>
                  <th>Email</th>
				  <th>WhatsApp</th>
				   
				  <th>Status</th>
				  <th></th>
				  <th></th>
                </tr>
              </thead>
               <tbody>
			  <?php
	            $pedidoss = $connect->query("SELECT * FROM config WHERE idu='1' ORDER BY id DESC");
			  	while ($pedidossx = $pedidoss->fetch(PDO::FETCH_OBJ)) {
				
				if($pedidossx->status == 1){ $status = "<button class=\"btn btn-success btn-sm\">Ativo</button>";}
				if($pedidossx->status == 2){$status = "<button class=\"btn btn-info btn-sm\">Novo Cliente</button>";}
				if($pedidossx->status == 3){$status = "<button class=\"btn btn-danger btn-sm\">Bloqueado</button>";}
				
				$data_inicial = date("Y-m-d");
				$data_final = $pedidossx->expiracao;
				$diferenca = strtotime($data_final) - strtotime($data_inicial);
			   	$prazo = floor($diferenca / (60 * 60 * 24));
				
			  	?>
                <tr>
				  
				  <td><?php print $pedidossx->id;?></td>
                  <th><?php print $pedidossx->nomeempresa;?></th>
				  <?php if($prazo >=1){?>
				  <th><span style="color:#00CC00"><?php print $prazo;?> dias</span></th>
				  <?php }else{?>
				  <th><span style="color:#FF6600">Já expirou</span></th>
				  <?php }?>
                  <td><?php print $pedidossx->nomeadmin; ?></td>
                  <td><?php print $pedidossx->email; ?></td>
				  <td><a href="https://api.whatsapp.com/send?phone=55<?=$pedidossx->celular;?>&text=OlÃƒÆ’Ã‚Â¡" target="_blank"><img src="../basecard/img/wp.png" style="width:15px" /> <?php print $pedidossx->celular; ?></a></td>
				  
				  <td><?php print $status; ?></td>
				  <td align="center">
				  <form action="vercliente.php" method="post">
	              <input type="hidden" name="codigop" value="<?php print $pedidossx->id;?>"/>
	              <button type="submit" class="btn btn-purple btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button>
	              </form>
				  </td>
				  <td align="center">
				  <form action="" method="post">
	              <input type="hidden" name="codigoe" value="<?php print $pedidossx->id;?>"/>
	              <button type="submit" class="btn btn-danger btn-sm" onClick="return confirm('Deseja mesmo excluir esse cliente?');"><i class="fa fa-times" aria-hidden="true"></i></button>
	              </form>
				  </td>
                </tr>
                <?php }?>    
              </tbody>
            </table>
          </div> 
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
      $(function(){
        'use strict';

        $('#datatable1').DataTable({
          "order": [[ 0, "desc" ]],   
          responsive: true,
          language: {
            searchPlaceholder: 'Buscar...',
            sSearch: '',
            lengthMenu: '_MENU_ Itens',
          }
        });

        $('#datatable2').DataTable({
          bLengthChange: false,
          searching: false,
          responsive: true
        });

        // Select2
        $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });

      });
    </script>
	
<script type="text/javascript">
  setTimeout(function() {
    window.location.reload(1);
  }, 30000);
</script>

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