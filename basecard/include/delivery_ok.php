<?php

$data		= date("d-m-Y");
$hora		= date("H:i:s");

if(isset($_POST["totalg"]))  {

$nome 			= strtoupper($_POST['nome']);
$wps  			= $_POST['wps'];
$vow	 		= array("(", ")", "-");
$wps			= str_replace($vow, "", $wps);

$valcel     = strlen($wps);
if($valcel < 11) {
header("location: ".$site."delivery&erro="); exit;
}

$fmpgto  		= $_POST['fmpgto'];

if($fmpgto=="CARTAO") {
$troco  		= "0,00";    
} else { 
$troco  		= $_POST['troco'];
}

$troco          = str_replace(",", ".", $troco);

$cidade  		= $_POST['cidade'];
$uf  			= $_POST['uf'];
$numero  		= $_POST['numero'];
$rua  			= $_POST['rua'];
$bairro  		= $_POST['bairro'];

if(!isset($_POST['complemento'])){
$complemento  		= "";
} else {
$complemento  	= $_POST['complemento'];
}

$cookie_name = "nomecli";
$cookie_value1 = $nome;
setcookie($cookie_name, $cookie_value1, time() + (86400 * 90));

$cookie_cel = "celcli";
$cookie_value2 = $wps;
setcookie($cookie_cel, $cookie_value2, time() + (86400 * 90));

$cookie_num = "numero";
$cookie_value3 = $numero;
setcookie($cookie_num, $cookie_value3, time() + (86400 * 90));

$cookie_rua = "rua";
$cookie_value4 = $rua;
setcookie($cookie_rua, $cookie_value4, time() + (86400 * 90));

$cookie_comp = "comp";
$cookie_value5 = $complemento;
setcookie($cookie_comp, $cookie_value5, time() + (86400 * 90));


$subtotal 		= $_POST['subtotal'];
$adcionais  	= $_POST['adcionais'];
$totalg  		= $_POST['totalg'];
$bairro  		= $_POST['bairro'];
$taxa  		    = $_POST['taxa'];
$taxa        	= str_replace(",", ".", $taxa);

if(!$taxa) {
$taxa  		= "0.00";    
}

$subtotalx      = str_replace(",", ".", $subtotal);
$adcionaisx     = str_replace(",", ".", $adcionais);
$totalgx        = str_replace(",", ".", $totalg);

if($troco > 0) {
	if($troco < $totalgx) {
		header("location: ".$site."delivery&troco="); 
		exit;
	}
}

$inst = $connect->query("INSERT INTO pedidos(idu, idpedido, fpagamento, cidade, numero, complemento, rua, bairro, troco, nome, data, hora, celular, taxa, mesa, pessoas, obs, vsubtotal, vadcionais, vtotal) VALUES ('$idu','$id_cliente','$fmpgto','$cidade','$numero','$complemento','$rua','$bairro','$troco','$nome','$data','$hora','$wps','$taxa','0','0','0','$subtotalx','$adcionaisx','$totalgx')");
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
$msg .= "*Ingredientes/Adicionais:*\n";
while ($adcionaisv = $adcionais->fetch(PDO::FETCH_OBJ)) {
$msg .= "-  R$: ".$adcionaisv->valor." | ".$adcionaisv->nome."\n";
}
}
$msg .= "\n";
}

$msg .= "DADOS DA ENTREGA\n";
$msg .= "\n";
$msg .= "*Tipo:* Delivery\n";
$msg .= "*Tempo de Entrega:* ".$dadosempresa->timerdelivery."\n";
$msg .= "*Cliente:* ".$nome."\n";
$msg .= "*Contato:* ".$wps."\n";
$msg .= "*Endereço:* ".$rua." - ".$numero."\n";
$msg .= "*Complemento:* ".$complemento."\n";
$msg .= "*Bairro:* ".$bairro."\n";
$msg .= "*Cidade:* ".$cidade."/".$uf."\n";
$msg .= "\n";

$msg .= "DADOS DO PAGAMENTO\n";
$msg .= "\n";
$msg .= "*Pagamento em:* ".$fmpgto."\n";
$msg .= "*Subtotal:* R$: ".number_format($subtotalx, 2, ',', ' ')."\n";
if($adcionaisx > 0.00){
$msg .= "*Adicionais:* R$: ".$adcionaisx."\n";
}
if($taxa > 0.00){
$msg .= "*Taxa de Entrega:* R$: ".$taxa."\n";
} else {
$msg .= "*Taxa de Entrega:* Grátis\n";
}
$msg .= "*Total:* R$: ".number_format($totalgx, 2, ',', ' ')."\n";

if($troco > 0.00){
$msg .= "*Troco para:* R$: ".number_format($troco, 2, ',', ' ')."\n";

$vtroco = $troco - $totalgx;

$msg .= "*Valor do Troco:* R$: ".number_format($vtroco, 2, ',', ' ')."\n";
}
$msg;
?>
<div class="slim-mainpanel" style="margin-bottom:80px">
	<div class="container">
	
		 <div class="section-wrapper mg-t-10">
		 
       	 
		 		 
					<form action="<?php echo $site; ?>" method="post">
					<input type="hidden" id="celular" value="<?php echo $dadosempresa->celular; ?>">
					<input type="hidden" id="mensagem" value="<?php echo $msg; ?>">
					<input type="hidden" name="pedidodelivery" value="ok">
					
					
					
          				<div class="section-wrapper mg-t-10" align="center">
         					<img src="img/fim.gif" width="250" />
							<br>
							<br>
							SEU PEDIDO FOI RECEBIDO<br>CLIQUE ABAIXO<br>PARA<br>
		  					
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
    celular = celular.replace(/\D/g,''); //Deixar apenas nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Âºmeros
  
	  //Verificar se tem DDI e adicionar se nÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â£o tiver
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