<?php
if (isset($_COOKIE['pdvx'])) {
    $cod_id = $_COOKIE['pdvx'];
} else {
    header("location: sair.php");
}

include_once('../../funcoes/Conexao.php');
include_once('../../funcoes/Key.php');

$pegadadosgerais = $connect->query("SELECT * FROM config WHERE id='$cod_id'");
$dadosgerais = $pegadadosgerais->fetch(PDO::FETCH_OBJ);
$nomeempresa = $dadosgerais->nomeempresa;

$motoboys = $connect->query("SELECT * FROM motoboy WHERE status='disponivel'")->fetchAll(PDO::FETCH_OBJ);

date_default_timezone_set('' . $dadosgerais->fuso . '');

$codigop = $_POST['codigop'];

$pedido = $connect->query("SELECT * FROM pedidos WHERE idpedido='$codigop'");
$pedido = $pedido->fetch(PDO::FETCH_OBJ);
$celcli = $pedido->celular;

$produtoscay = $connect->query("SELECT * FROM store WHERE idsecao = '$codigop' ORDER BY id DESC");

$produtoscaxy = $connect->query("SELECT * FROM store WHERE idsecao = '$codigop' ORDER BY id DESC");

$produtosca = $connect->query("SELECT * FROM store WHERE idsecao = '$codigop' ORDER BY id DESC");
$produtoscx = $produtosca->rowCount();

$item = $connect->query("SELECT * FROM store WHERE idsecao='$codigop'");
$opcionais = $connect->query("SELECT * FROM store_o WHERE ids='$codigop'");

$msg1 = "Olá! O Seu pedido foi aceito e já foi encaminhado para o preparo.\n\n*$nomeempresa*\n";
$msg2 = "Olá! O seu pedido está a caminho.\n\n*$nomeempresa*\n";
$msg3 = "Olá! Seu pedido já está disponível para retirada em nosso estabelecimento.\n\n*$nomeempresa*\n";
$msg4 = "Pedido entregue! Obrigado pela preferência.\n\n*$nomeempresa*\n";
$msg5 = "Olá! Infelizmente o seu pedido foi cancelado.\n\n*$nomeempresa*\n";
$msg6 = "Pedido para entrega! Favor coletar o pedido.\n\n*$nomeempresa*\n";

if (isset($_POST["andamento"])) {
    $update = $connect->query("UPDATE pedidos SET status='2' WHERE idpedido='".$_POST["andamento"]."'");
    header("location: pdv.php?ok=");
}

// saiu para entrega

$msg2 =  "Olá! O seu pedido está a caminho.\n";
$msg2 .= "\n";
$msg2 .= "*".$nomeempresa."*\n";
$msg2;

if(isset($_POST["entrega"]))  {
    $update = $connect->query("UPDATE pedidos SET status='3' WHERE idpedido='".$_POST["entrega"]."'");
    header("location: pdv.php?ok=");
}

// disponivel para retirada

$msg3 =  "Olá! Seu pedido já esta disponível para retirada em nosso estabelecimento.\n";
$msg3 .= "\n";
$msg3 .= "*".$nomeempresa."*\n";
$msg3;

if(isset($_POST["retirada"]))  {
    $update = $connect->query("UPDATE pedidos SET status='4' WHERE idpedido='".$_POST["retirada"]."'");
    header("location: pdv.php?ok=");
}

// finalizado

$msg4 =  "Pedido entregue! Obrigado pela preferência.\n";
$msg4 .= "\n";
$msg4 .= "*".$nomeempresa."*\n";
$msg4;

if(isset($_POST["finalizado"]))  {
    $update = $connect->query("UPDATE pedidos SET status='5' WHERE idpedido='".$_POST["finalizado"]."'");
    header("location: pdv.php?ok=");
}

// Disp Motoboy

if (isset($_POST["dispmoto"])) {
    $motoboy_id = $_POST["motoboy"];
    $update = $connect->query("UPDATE motoboy SET status='ocupado' WHERE id='$motoboy_id'");
    // Aqui você deve adicionar a lógica para disparar a mensagem no WhatsApp usando a API apropriada
    header("location: pdv.php?ok=");
}

// cancelado

$msg5 =  "Olá! Infelizmente o seu pedido foi cancelado.\n";
$msg5 .= "\n";
$msg5 .= "*".$nomeempresa."*\n";
$msg5;

if(isset($_POST["cancelado"]))  {
    $update = $connect->query("UPDATE pedidos SET status='6' WHERE idpedido='".$_POST["cancelado"]."'");
    header("location: pdv.php?ok=");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Premium Quality and Responsive UI for Dashboard.">
    <meta name="author" content="ThemePixels">
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
                        <span>
                            <?php
                            if ($pedido->status == 1) {
                                echo $statusxx = "Status Atual - Pedido Novo";
                            } elseif ($pedido->status == 2) {
                                echo $statusxx = "Status Atual - Em Andamento";
                            } elseif ($pedido->status == 3) {
                                echo $statusxx = "Status Atual - Saiu para entrega";
                            } elseif ($pedido->status == 4) {
                                echo $statusxx = "Status Atual - Disp. para retirada";
                            } elseif ($pedido->status == 5) {
                                echo $statusxx = "Status Atual - Finalizado";
                            } elseif ($pedido->status == 6) {
                                echo $statusxx = "Status Atual - Disp Motoboy";
                            } elseif ($pedido->status == 8) {								
                                echo $statusxx = "Status Atual - Cancelado";
                            } elseif ($pedido->status == 7) {
                                echo $statusxx = "Status Atual - Confirmado pela Cozinha";
                            }
                            ?>
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pdv.php">
                        <i class="icon ion-ios-analytics-outline"></i>
                        <span> VOLTAR </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pdv.php">
                        <i class="icon ion-ios-analytics-outline"></i>
                        <span> botao aqui </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="slim-mainpanel">
        <div class="container">
            <div class="row mg-t-10">
                <div class="col-md-6">
                    <div class="card card-people-list pd-15 mg-b-10">
                        <label class="section-title" style="margin-top:-1px"><i class="fa fa-check-square-o" aria-hidden="true"></i> ALTERAR STATUS DO PEDIDO</label>
                        <hr>
                        <div class="row mg-t-10">
                            <div class="col-md-4 mg-b-10">
                                <form action="" method="post">
                                    <input type="hidden" name="andamento" value="<?=$codigop;?>">
                                    <input type="hidden" id="celular" value="<?=$celcli;?>">
                                    <input type="hidden" id="mensagem" value="<?=$msg1; ?>">
                                    <button type="submit" class="btn btn-warning btn-block" onClick="enviarMensagem()">Aceitar Pedido</button>
                                </form>
                            </div>
                            <div class="col-md-4 mg-b-10">
                                <form action="" method="post">
                                    <input type="hidden" name="entrega" value="<?=$codigop;?>">
                                    <input type="hidden" id="celular2" value="<?=$celcli;?>">
                                    <input type="hidden" id="mensagem2" value="<?=$msg2; ?>">
                                    <button type="submit" class="btn btn-success btn-block" onClick="enviarMensagem2()">Saiu para entrega</button>
                                </form>
                            </div>
                            <div class="col-md-4 mg-b-10">
                                <form action="" method="post">
                                    <input type="hidden" name="retirada" value="<?=$codigop;?>">
                                    <input type="hidden" id="celular3" value="<?=$celcli;?>">
                                    <input type="hidden" id="mensagem3" value="<?=$msg3; ?>">
                                    <button type="submit" class="btn btn-info btn-block" onClick="enviarMensagem3()">Disp. para retirada</button>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <form action="" method="post">
                                    <input type="hidden" name="finalizado" value="<?=$codigop;?>">
                                    <input type="hidden" id="celular4" value="<?=$celcli;?>">
                                    <input type="hidden" id="mensagem4" value="<?=$msg4; ?>">
                                    <button type="submit" class="btn btn-dark btn-block" onClick="enviarMensagem4()">Finalizar Pedido</button>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <form action="" method="post">
                                    <input type="hidden" name="dispmoto" value="<?=$codigop;?>">
                                    <input type="hidden" id="celular6" value="<?=$celcli;?>">
                                    <input type="hidden" id="mensagem6" value="<?=$msg6; ?>">
                                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#selecionarMotoboyModal">Disparar MotoBoy</button>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <form action="" method="post">
                                    <input type="hidden" name="cancelado" value="<?=$codigop;?>">
                                    <input type="hidden" id="celular5" value="<?=$celcli;?>">
                                    <input type="hidden" id="mensagem5" value="<?=$msg5; ?>">
                                    <button type="submit" class="btn btn-danger btn-block" onClick="enviarMensagem5()">Cancelar Pedido</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mg-t-20 mg-md-t-0">
                    <div class="card card-people-list pd-15">
                        <label class="section-title">Pedido:</label>
                        <h5> <?=$pedido->idpedido;?> - <?=$pedido->nomecli;?></h5>
                        <p> Pedido realizado em: <?=$pedido->data;?><br>Telefone: <?=$pedido->celular;?></p>
                        <hr>
                        <?php while($row = $produtoscaxy->fetch(PDO::FETCH_OBJ)) { ?>
                        <h6><i class="fa fa-cutlery" aria-hidden="true"></i> <?=$row->produto;?></h6>
                        <?php } ?>
                        <hr>
                        <label class="section-title">Observações:</label>
                        <p> <?=$pedido->obs;?> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Selecionar Motoboy -->
    <div class="modal fade" id="selecionarMotoboyModal" tabindex="-1" role="dialog" aria-labelledby="selecionarMotoboyModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="selecionarMotoboyModalLabel">Selecionar Motoboy</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="motoboy">Motoboy Disponível:</label>
                            <select class="form-control" id="motoboy" name="motoboy">
                                <?php foreach ($motoboys as $motoboy) { ?>
                                <option value="<?=$motoboy->id;?>"><?=$motoboy->nome;?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
// Get the modal
var modal = document.getElementById("motoboyModal");

// Function to open the modal
function openModal() {
  modal.style.display = "block";
}

// Function to close the modal
function closeModal() {
  modal.style.display = "none";
}

// Close the modal when clicking outside of it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
<style>
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: auto; /* Center the modal */
  padding: 20px;
  border: 1px solid #888;
  width: 250px; /* Fixed width */
  height: 120px; /* Fixed height */
  position: absolute;
  top: 50%; /* Position 50% from the top */
  left: 50%; /* Position 50% from the left */
  transform: translate(-50%, -50%); /* Translate it back by 50% of its own width and height */
}

/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
</style>
					<div class="col-md-4">
					<form action="" method="post">
					<input type="hidden" name="cancelado" value="<?=$codigop;?>">
					<input type="hidden" id="celular5" value="<?=$celcli;?>">
					<input type="hidden" id="mensagem5" value="<?php echo $msg5; ?>">
					<button type="submit" class="btn btn-danger btn-block" onClick="enviarMensagem5()">Cancelar</button>
					</form>
					</div>
				</div>
				
				
				
			</div>
		  </div>
		  
<div class="col-md-3">
		  	<center>
			 <a href="#" class="btn btn-primary btn-block invoice-print" name="btnprint" onClick="PrintMe('print')"><i class="fa fa-print" aria-hidden="true"></i> Balcão</a>
			</center>
<div class="card card-people-list pd-15 mg-b-10" style="background-color:#fdfbe3">

<div id="print" style="font-family: Arial;">
	<center><p class="tx-15"><strong>RESUMO DO PEDIDO</strong></p></center>
	<center><p class="tx-12">Comanda Balcão</span></p>
	<center><p class="tx-12"><?=$pedido->data;?> às <?=$pedido->hora;?></span></p>
	<center><p class="tx-12">Nº <?=$codigop;?></span></p>
	<hr />
	<?php 
	while ($carpro = $produtosca->fetch(PDO::FETCH_OBJ)) { 
	$nomepro  = $connect->query("SELECT nome FROM produtos WHERE id = '".$carpro->produto_id."'");
	$nomeprox = $nomepro->fetch(PDO::FETCH_OBJ);
	?>
	
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>** Item: </b><?php print $nomeprox->nome;?></span></p>
	
	<?php if($carpro->tamanho != "N" ) { ?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>- Tamanho: </b><?php print $carpro->tamanho;?></span></p>
	<?php } ?>
		
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>- Qnt:</b> <?php print $carpro->quantidade; ?></span></p>
	
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>- V. Unitário:</b> <?php echo "R$: ".$carpro->valor; ?></span></p>
	
	<?php if($carpro->obs) {?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>- Obs:</b> <?php echo $carpro->obs; ?></span></p>
	<?php } else { ?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>- Obs:</b> Não</span></p>
	<?php } ?>
	
	<?php
	$meiom  = $connect->query("SELECT * FROM store_o WHERE idp = '".$carpro->idpedido."' AND status = '1' AND idu='$cod_id' AND meioameio='1'"); 
	$meiomc = $meiom->rowCount();
	?>
	
	<?php if($meiomc>0){ ?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>* <?=$meiomc;?> Sabores:</b></span></p>
	<p style="margin-left:10px;" align="left"><span class="tx-12">
	<?php while ($meiomv = $meiom->fetch(PDO::FETCH_OBJ)) { ?>
	<?=$meiomv->nome."<br>";?>
	<?php } ?>
	</span></p>
	<?php } ?>
	
	<?php
	$adcionais  = $connect->query("SELECT * FROM store_o WHERE idp = '".$carpro->idpedido."' AND status = '1' AND idu='$cod_id' AND meioameio='0'"); 
	$adcionaisc = $adcionais->rowCount();
	?>
	
	<?php if($adcionaisc>0){ ?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>* Adicionais/Ingredientes:</b></p>
	<p style="margin-left:10px;" align="left"><span class="tx-12">
	<?php while ($adcionaisv = $adcionais->fetch(PDO::FETCH_OBJ)) { ?>
	<?="-  R$: ".$adcionaisv->valor." | ".$adcionaisv->nome."<br>";?>
	<?php } ?>
	</span></p>
	<?php } ?>
	<center>=========================</center></p>
	<?php } ?>
	
	<?php
	$nome = str_replace('%20', ' ', $pedido->nome);
	?>
	
	<br>
	<center><strong>DADOS DO CLIENTE</strong></center>
	<hr />
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Nome: </b><?=$nome;?></span></p>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Celular: </b><?=$pedido->celular;?></span></p>
	<?php if($pedido->mesa > 0 ){ ?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Mesa: </b><?=$pedido->mesa;?></span></p>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Pessoa na Mesa: </b><?=$pedido->pessoas;?></span></p>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Obs: </b><?=$pedido->obs;?></span></p>
	<?php } ?>
	<?php if($pedido->fpagamento == "DINHEIRO" || $pedido->fpagamento == "CARTAO" || $pedido->fpagamento == "CARTÃO") { ?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Rua: </b><?=$pedido->rua;?></span></p>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Nº: </b><?=$pedido->numero;?></span></p>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Bairro: </b><?=$pedido->bairro;?></span></p>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Complemento: </b><?=$pedido->complemento;?></span></p>
	<?php } ?>
<br>
	<center><strong>PAGAMENTO</strong></center>
	<hr />
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
	if($pedido->fpagamento == "DINHEIRO"){print $delivery = "<p style=\"margin-left:10px;\" align=\"left\"><span class=\"tx-12\"><b>Dinheiro na Entrega</b></span></p>";}
	if($pedido->fpagamento == "CARTAO" || $pedido->fpagamento == "CARTÃO"){print $delivery = "<p style=\"margin-left:10px;\" align=\"left\"><span class=\"tx-12\"><b>Cartão na Entrega</b></span></p>";}
	if($pedido->fpagamento == "MESA"){print $delivery = "<p style=\"margin-left:10px;\" align=\"left\"><span class=\"tx-12\"><b>Na Mesa</b></span></p>";}
	if($pedido->fpagamento == "BALCAO"){print $delivery = "<p style=\"margin-left:10px;\" align=\"left\"><span class=\"tx-12\"><b>No Balcão</b></span></p>";}
	?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Subtotal: R$: </b><?=formatMoedaBr(formatCurrency($pedido->vsubtotal))?></span></p>
	<?php if($pedido->vadcionais > 0.00) { ?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Adicionais: R$: </b><?=formatMoedaBr(formatCurrency($pedido->vadcionais))?></span></p>
	<?php } ?>
	<?php if($pedido->taxa > 0) { ?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Taxa de Entrega: R$: </b><?=formatMoedaBr(formatCurrency($pedido->taxa))?></span></p>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Total Geral: </b> R$: <?=formatMoedaBr(formatCurrency(($pedido->vtotal)))?></span></p>
	<?php } else { ?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Total Geral: </b> R$: <?=formatMoedaBr(formatCurrency($pedido->vtotal))?></span></p>
	<?php } ?>
	<?php if($pedido->troco > 0) { ?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Troco para: R$: </b><?=formatMoedaBr(formatCurrency($pedido->troco))?></span></p>
	<?php $ValorDoTroco = $pedido->troco - $pedido->vtotal?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Valor do Troco: R$: </b><?=formatMoedaBr(formatCurrency($ValorDoTroco))?></span></p>
	<?php } ?>
	<br>
	<p style="margin-left:10px;" align="right"><span class="tx-11"><b><?=date("d-m-Y H:i:s");?></b></span></p>

</div>			  			  
</div>
</div>
		  
<div class="col-md-3">
		  
	<center>
	<a href="#" class="btn btn-info btn-block invoice-print" name="btnprint" onClick="PrintMe2('print2')"><i class="fa fa-print" aria-hidden="true"></i> Cozinha</a>
	</center>
			  
<div class="card card-people-list pd-15 mg-b-10" style="background-color:#fdfbe3">

<div id="print2" style="font-family: Arial;">

	<center><p class="tx-15"><strong>RESUMO DO PEDIDO</strong></p></center>
	<center><p class="tx-12">Comanda Cozinha</p></center>
	<center><p class="tx-12"><?=$pedido->data;?> às <?=$pedido->hora;?></span></p>
	<center><p class="tx-12">Nº <?=$codigop;?></p></center>
	<hr />
	
	<?php 
	while ($carpro2 = $produtoscaxy->fetch(PDO::FETCH_OBJ)) { 
	$nomepro2  = $connect->query("SELECT nome FROM produtos WHERE id = '".$carpro2->produto_id."'");
	$nomeprox2 = $nomepro2->fetch(PDO::FETCH_OBJ);
	?>
	
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>** Item:</b> <?php print $nomeprox2->nome;?></span></p>
	
	<?php if($carpro2->tamanho != "N" ) { ?>
	
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>- Tamanho:</b> <?php print $carpro2->tamanho;?></span></p>
	
	<?php } ?>
	
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>- Qnt:</b> <?php print $carpro2->quantidade; ?></span></p>
	
	<?php if($carpro2->obs) {?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>- Obs:</b> <?php echo $carpro2->obs; ?></span></p>
	<?php } else { ?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>- Obs:</b> Não</span></p>
	<?php } ?>
	
	<?php
	$meiom2  = $connect->query("SELECT * FROM store_o WHERE idp = '".$carpro2->idpedido."' AND status = '1' AND idu='$cod_id' AND meioameio='1'"); 
	$meiomc2 = $meiom2->rowCount();
	?>
	
	<?php if($meiomc2>0){ ?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>* <?=$meiomc2;?> Sabores:</b></span></p>
	<p style="margin-left:10px;" align="left"><span class="tx-12">
	<?php while ($meiomv2 = $meiom2->fetch(PDO::FETCH_OBJ)) { ?>
	<?=$meiomv2->nome."<br>";?>
	<?php } ?>
	</span></p>
	<?php } ?>
	
	<?php
	$adcionais2  = $connect->query("SELECT * FROM store_o WHERE idp = '".$carpro2->idpedido."' AND status = '1' AND idu='$cod_id' AND meioameio='0'"); 
	$adcionaisc2 = $adcionais2->rowCount();
	?>
	
	<?php if($adcionaisc2>0){ ?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>* Adicionais/Ingredientes:</b></p>
	<p style="margin-left:10px;" align="left"><span class="tx-12">
	<?php while ($adcionaisv2 = $adcionais2->fetch(PDO::FETCH_OBJ)) { ?>
	<?="- ".$adcionaisv2->nome."<br>";?>
	<?php } ?>
	</span></p>
	<?php } ?>
	<center>=========================</center></p>
	<?php } ?>
	
	<?php
	$nome = str_replace('%20', ' ', $pedido->nome);
	?>
	<br>
	<center><strong>DADOS DO CLIENTE</strong></center>
	<hr />
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Nome: </b><?=$nome;?></span></p>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Celular: </b><?=$pedido->celular;?></span></p>
	<?php if($pedido->mesa > 0 ){ ?>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Mesa: </b><?=$pedido->mesa;?></span></p>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Pessoa na Mesa: </b><?=$pedido->pessoas;?></span></p>
	<p style="margin-left:10px;" align="left"><span class="tx-12"><b>Obs: </b><?=$pedido->obs;?></span></p>
	<?php } ?>
	<br>
	<p style="margin-left:10px;" align="right"><span class="tx-11"><b><?=date("d-m-Y H:i:s");?></b></span></p>
	

</div>			  
</div>
</div>		  
		  
		  
	</div>
</div>
	
	 

    <script src="../lib/jquery/js/jquery.js"></script>
    <script src="../lib/popper.js/js/popper.js"></script>
    <script src="../lib/bootstrap/js/bootstrap.js"></script>
    <script src="../lib/jquery.cookie/js/jquery.cookie.js"></script>
    <script src="../lib/datatables/js/jquery.dataTables.js"></script>
    <script src="../lib/datatables-responsive/js/dataTables.responsive.js"></script>
    <script src="../lib/select2/js/select2.min.js"></script>
    <script src="../js/slim.js"></script>
</body>
</html>

<script>
function enviarMensagem() {
    var celular = document.getElementById('celular').value;
    var mensagem = document.getElementById('mensagem').value;
    // Lógica para enviar mensagem no WhatsApp
}

function enviarMensagem2() {
    var celular = document.getElementById('celular2').value;
    var mensagem = document.getElementById('mensagem2').value;
    // Lógica para enviar mensagem no WhatsApp
}

function enviarMensagem3() {
    var celular = document.getElementById('celular3').value;
    var mensagem = document.getElementById('mensagem3').value;
    // Lógica para enviar mensagem no WhatsApp
}

function enviarMensagem4() {
    var celular = document.getElementById('celular4').value;
    var mensagem = document.getElementById('mensagem4').value;
    // Lógica para enviar mensagem no WhatsApp
}

function enviarMensagem5() {
    var celular = document.getElementById('celular5').value;
    var mensagem = document.getElementById('mensagem5').value;
    // Lógica para enviar mensagem no WhatsApp
}

function enviarMensagem6() {
    var celular = document.getElementById('celular6').value;
    var mensagem = document.getElementById('mensagem6').value;
    // Lógica para enviar mensagem no WhatsApp
}
</script>