<?php
if(isset($_COOKIE['pdvx'])){
$cod_id = $_COOKIE['pdvx'];
} else {
header("location: sair.php"); 
}
date_default_timezone_set( 'America/Sao_Paulo' );
include_once('../../funcoes/Conexao.php');
include_once('../../funcoes/Key.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head><meta charset="windows-1252">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema de PDV.">
    <meta name="author" content="MDINELLY">
    <title>RECEBIMENTO DE PEDIDOS</title>
    <link href="../lib/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../lib/Ionicons/css/ionicons.css" rel="stylesheet">
	<link href="../lib/datatables/css/jquery.dataTables.css" rel="stylesheet">
    <link href="../lib/select2/css/select2.min.css" rel="stylesheet">
	<link href="../lib/SpinKit/css/spinkit.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/slim.css">
  </head>
  <body>
    
	<div class="slim-navbar">
      <div class="container">
        <ul class="nav">
		  <li class="nav-item">
            <a class="nav-link" href="#">
              <i class="icon ion-ios-home-outline"></i>
              <span>RECEBIMENTO DE PEDIDOS</span>
            </a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" href="#">
              <span>
                <progress value="0" max="30" id="progressBar"></progress>
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
			  $dia 	 = date("d-m-Y"); 
			  $todia = $connect->query("SELECT vtotal, SUM(vtotal) AS soma1 FROM pedidos WHERE idu='".$cod_id."' AND status='5' AND data = '".$dia."'");
			  $todia = $todia->fetch(PDO::FETCH_OBJ);
			  ?>
			<div class="col-lg-4">
              <i class="icon ion-ios-pie-outline"></i>
              <div class="dash-content">
                <label class="tx-success">Finalizado em <?=$dia?></label>
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
		
		
			  $status1 = $connect->query("SELECT vtotal FROM pedidos WHERE idu='".$cod_id."' AND data = '".$dia."' AND status='1'");
			  $status2 = $connect->query("SELECT vtotal FROM pedidos WHERE idu='".$cod_id."' AND data = '".$dia."' AND status='2'");
			  $status3 = $connect->query("SELECT vtotal, SUM(vtotal) AS soma8 FROM pedidos WHERE idu='".$cod_id."' AND data = '".$dia."' AND status='3'");
			  $status4 = $connect->query("SELECT vtotal, SUM(vtotal) AS soma9 FROM pedidos WHERE idu='".$cod_id."' AND data = '".$dia."' AND status='4'");
		
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
                <label class="tx-warning">Pedidos da Fila</label>
                <h2>R$: <?php echo number_format($aguar, 2, ',', '.'); ?></h2>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
			
			  <?php
			  $final = $connect->query("SELECT vtotal, SUM(vtotal) AS soma3 FROM pedidos WHERE idu='".$cod_id."' AND status='6' AND data = '".$dia."'");
			  $final = $final->fetch(PDO::FETCH_OBJ);
			  ?>
            <div class="col-lg-4">
			  <i class="icon ion-ios-analytics-outline"></i>
              <div class="dash-content">
                <label class="tx-danger">Cancelados em <?=$dia?></label>
                <h2>R$: <?php echo number_format($final->soma3, 2, '.', '.'); ?></h2>
              </div><!-- dash-content -->
            </div><!-- col-3 -->
			
          </div><!-- row -->
        </div><!-- card -->
		  
		<div class="section-wrapper mg-t-20">
          <label class="section-title"><i class="fa fa-check-square-o" aria-hidden="true"></i> PEDIDOS RECEBIDOS || <a href="pdvpedido.php?idpedido=<?=$id_pedido = rand(100000, 999999);?>" class="btn btn-success btn-sm"><i class="fa fa-plus" aria-hidden="true"></i> Pedido Manual</a> </label>
		  <hr>
          <div class="table-wrapper">
            <table id="datatable1" class="table display responsive nowrap" width="100%">
              <thead>
                <tr>
                  <th>#</th>
				  <th>Comanda</th>
                  <th>Data</th>
                  <th>Tipo</th>
                  <th>Cliente</th>
				  <th>WhatsApp</th>
				  <th>Total</th>
				  <th>Status</th>
				  <th></th>
				  <th></th>
                </tr>
              </thead>
              <tbody>
                
			 <?php
			    $dia 	 = date("d-m-Y"); 
	            $pedidoss = $connect->query("SELECT * FROM pedidos WHERE idu='".$cod_id."' ORDER BY id DESC LIMIT 200");
			  	while ($pedidossx = $pedidoss->fetch(PDO::FETCH_OBJ)) {
				if($pedidossx->status == 1){ $status = "<button class=\"btn btn-warning btn-sm\">Novo Pedido</button>";
           		echo "
				<script>
				var audio = new Audio('campainha.mp3');
				audio.addEventListener('canplaythrough', function() {
				audio.play();
				});
				</script>
				";
				}
				if($pedidossx->status == 2){$status = "<button class=\"btn btn-info btn-sm\">Pedido Aceito</button>";}
				if($pedidossx->status == 3){$status = "<button class=\"btn btn-warning btn-sm\">Saiu para entrega</button>";}
				if($pedidossx->status == 4){$status = "<button class=\"btn btn-purple btn-sm\">Disponivel para retirada</button>";}
				if($pedidossx->status == 5){$status = "<button class=\"btn btn-success btn-sm\">Finalizado</button>";}
				if($pedidossx->status == 6){$status = "<button class=\"btn btn-danger btn-sm\">Cancelado</button>";}
				if($pedidossx->status == 7){$status = "<button class=\"btn btn-purple btn-sm\">Confirmado Cozinha</button>";}
				
				if($pedidossx->fpagamento == "DINHEIRO"){$delivery = "<span style=\"color:#FF0000\">DELIVERY</span>";}
				if($pedidossx->fpagamento == "CARTAO" || $pedidossx->fpagamento == "CARTÃO"){$delivery = "<span style=\"color:#FF0000\">DELIVERY</span>";}
				if($pedidossx->fpagamento == "MESA"){$delivery = "MESA";}
				if($pedidossx->fpagamento == "BALCAO"){$delivery = "BALCÃO";}
			  	?>
                <tr>
				  <td><?php print $pedidossx->id;?></td>
				  <td><?php print $pedidossx->idpedido;?></td>
                  <th><?php print $pedidossx->data;?> ás <?php print $pedidossx->hora;?></th>
                  <td><?php print $delivery; ?></td>
                  <td><?php print $pedidossx->nome; ?></td>
				  <td><a href="https://api.whatsapp.com/send?phone=55<?=$pedidossx->celular;?>&text=Olá" target="_blank"><img src="../img/wp.png" style="width:15px" /> <?php print $pedidossx->celular; ?></a></td>
				  <td>R$ <?php print formatMoedaBr(formatCurrency($pedidossx->vtotal)); ?></td>
				  
				  <td><?php print $status; ?></td>
				  <td align="center">
	              <form action="verpedido.php" method="post">
	              <input type="hidden" name="codigop" value="<?php print $pedidossx->idpedido;?>"/>
	              <button style="cursor: pointer;" type="submit" class="btn btn-purple btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button>
				  </form> 
				  </td>
				  <td>
				  <a href="pdvpedidoeditar.php?idpedido=<?php print $pedidossx->idpedido; ?>"><button class="btn btn-warning btn-sm"><i class="fa fa-pencil" aria-hidden="true"></i></button> </a></td>
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
	
	 

    <script src="../lib/jquery/js/jquery.js"></script>
     <script src="../lib/datatables/js/jquery.dataTables.js"></script>
    <script src="../lib/datatables-responsive/js/dataTables.responsive.js"></script>
    <script src="../lib/select2/js/select2.min.js"></script>
    
    <script>
      $(function(){
        'use strict';

        $('#datatable1').DataTable({
          "order": [[ 0, "desc" ]],   
          responsive: true,
          language: {
            searchPlaceholder: 'Buscar...',
            sSearch: '',
            lengthMenu: '_MENU_ ítens',
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
var timeleft = 30;
var downloadTimer = setInterval(function(){
  document.getElementById("progressBar").value = 30 - timeleft;
  timeleft -= 1;
  if(timeleft <= 0){
    clearInterval(downloadTimer);
  }
}, 1000);
</script>

  </body>
</html>