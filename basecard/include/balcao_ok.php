<?php

$data		= date("d-m-Y");
$hora		= date("H:i:s");

if(isset($_POST["totalg"]))  {

$nome 		= strtoupper($_POST['nome']);
$wps  		= $_POST['wps'];
$vow	 	= array("(", ")", "-");
$wps		= str_replace($vow, "", $wps);
$valcel     = strlen($wps);
if($valcel != 11) {
header("location: ".$site."balcao&erro="); exit;
}
$subtotal 	= $_POST['subtotal'];
$adcionais  = $_POST['adcionais'];
$totalg  	= $_POST['totalg'];
$subtotalx  = str_replace(",", ".", $subtotal);
$adcionaisx = str_replace(",", ".", $adcionais);
$totalgx    = str_replace(",", ".", $totalg);

$cookie_name = "nomecli";
$cookie_value1 = $nome;
setcookie($cookie_name, $cookie_value1, time() + (86400 * 90));

$cookie_cel = "celcli";
$cookie_value2 = $wps;
setcookie($cookie_cel, $cookie_value2, time() + (86400 * 90));
if(isset($_GET["up"])){ echo unlink("".$_GET["up"].""); }


$inst = $connect->query("INSERT INTO pedidos(idu, idpedido, fpagamento, cidade, numero, complemento, rua, bairro, troco, nome, data, hora, celular, taxa, mesa, pessoas, obs, vsubtotal, vadcionais, vtotal) VALUES ('$idu','$id_cliente','BALCAO','0','0','0','0','0','0.00','$nome','$data','$hora','$wps','0','0','0','0','$subtotalx','$adcionaisx','$totalgx')");
$update = $connect->query("UPDATE store SET status='1' WHERE idsecao='$id_cliente'");
$update = $connect->query("UPDATE store_o SET status='1' WHERE ids='$id_cliente'");

}

// MONTA MENSAGEM WHATSAPP
$msg =  "NOVO PEDIDO - ".$id_cliente."\n";
$msg .= "*Data:* ".$data."\n";
$msg .= "*Hora:* ".$hora."\n";
$msg .= "\n";

$msg .= "DADOS DO PEDIDO\n";
$msg .= "\n";

$produtosca    	= $connect->query("SELECT * FROM store WHERE idsecao = '".$id_cliente."' AND status='1' AND idu='$idu' ORDER BY id DESC");
while ($carpro 	= $produtosca->fetch(PDO::FETCH_OBJ)) { 

$nomepro  		= $connect->query("SELECT nome FROM produtos WHERE id = '".$carpro->produto_id."' AND idu='$idu'");
$nomeprox 		= $nomepro->fetch(PDO::FETCH_OBJ);
$msg .= "*Item:* ".$nomeprox->nome."\n";
if($carpro->tamanho != "N" ) {
$msg .= "*Tamanho:* ".$carpro->tamanho."\n";
}
$msg .= "*Qnt:* ".$carpro->quantidade."\n";
$msg .= "*V. Unitário:* ".$carpro->valor."\n";
if($carpro->obs) {
$msg .= "*Obs:* ".$carpro->obs."\n";
}

// PEGA DADOS DO MEIO A MEIO SE TIVER

$meiom  = $connect->query("SELECT * FROM store_o WHERE idp='".$carpro->idpedido."' AND status = '1' AND idu='$idu' AND meioameio='1'"); 
$meiomc = $meiom->rowCount();
if($meiomc>0){
$msg .= "*".$meiomc." Sabores:*\n";
while ($meiomv = $meiom->fetch(PDO::FETCH_OBJ)) {
$msg .= "".$meiomv->nome."\n";
}
}

// PEGA ADCIONAIS

$adcionais  = $connect->query("SELECT * FROM store_o WHERE idp='".$carpro->idpedido."' AND status = '1' AND idu='$idu' AND meioameio='0'"); 
$adcionaisc = $adcionais->rowCount();
if($adcionaisc>0){
$msg .= "*Ingedientes/Adicionais:*\n";
while ($adcionaisv = $adcionais->fetch(PDO::FETCH_OBJ)) {
$msg .= "-  R$: ".$adcionaisv->valor." | ".$adcionaisv->nome."\n";
}
}
$msg .= "\n";
}

$msg .= "DADOS DA ENTREGA\n";
$msg .= "\n";
$msg .= "*Tipo:* Retirada no Balcão\n";
$msg .= "*Tempo de Entrega:* ".$dadosempresa->timerbalcao."\n";
$msg .= "*Cliente:* ".$nome."\n";
$msg .= "*Contato:* ".$wps."\n";
$msg .= "\n";

$msg .= "DADOS DO PAGAMENTO\n";
$msg .= "\n";

$msg .= "*Subtotal:* R$: ".$subtotalx."\n";
if($adcionais > "0.00") {
$msg .= "*Adicionais:* R$: ".$adcionaisx."\n";
}
$msg .= "*Valor Total:* R$: ".$totalgx."\n";
$msg .= "\n";
$msg .= "ENDEREÇO DE RETIRADA\n";
$msg .= "\n";
$msg .= "*".$dadosempresa->nomeempresa."*\n";
$msg .= "".$dadosempresa->rua." - nº ".$dadosempresa->numero."\n";
$msg .= "".$dadosempresa->bairro."\n";
$msg;
?>
<div class="slim-mainpanel" style="margin-bottom:80px">
	<div class="container">
	
		 <div class="section-wrapper mg-t-10">
		 		 
					<form action="<?php echo $site; ?>" method="post">
					<input type="hidden" id="celular" value="<?php echo $dadosempresa->celular; ?>">
					<input type="hidden" id="mensagem" value="<?php echo $msg; ?>">
					<input type="hidden" name="balcao" value="ok">
					
          				<div class="section-wrapper mg-t-10" align="center">
         					<img src="img/fim.gif" width="250" />

							<br>
							SEU PEDIDO FOI RECEBIDO<br>CLIQUE ABAIXO<br>PARA
						<div>	
						  <div class="row">
							<div class="col-md-12">
							  
								
								 <hr>
								 
								 <div class="row mg-b-10">
								 
								 <div class="col-12">
								 <button type="submit" class="btn btn-success btn-block" onclick="enviarMensagem()">FINALIZAR<i class="fa fa-arrow-right mg-l-5"></i></button>
								
								 </div>
								 
								 
								 
								 </div>
</form>



            				</div>
          				</div>
        			</div>
      			</div>
			</div>
<script type="text/javascript">
	function enviarMensagem(){
	var celular = document.querySelector("#celular").value;
    celular = celular.replace(/\D/g,'');
	  if(celular.length < 13){
		celular = "55" + celular;
	  }
	  var texto = document.querySelector("#mensagem").value;
	  texto = window.encodeURIComponent(texto);
	  window.open("https://api.whatsapp.com/send?phone=" + celular + "&text=" + texto, "_blank");
	}
</script>
<?php 
    session_destroy(); 
    session_write_close();
    ?>