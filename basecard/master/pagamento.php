<?php
require_once "topo.php";

$msg =  "*".$nomeempresa."*\n";
$msg .= "\n";
$msg .= "Click no link abaixo para vocÃª acessar e fazer seu pedido com mais agilidade.\n";
$msg .= "\n";
$msg .= "".$sitexx."\n";
$msg .= "\n";
$msg .= "Estamos aguardando o seu pedido.\n";
$msg .= "\n";
$msg;

?>
	<div class="slim-mainpanel">
        <div class="container">
		  <?php if($configadmin->statuslink == 1){?>
		  <div class="row row-sm mg-t-20">
           
				<div class="col-lg-12">
					<div class="card card-info">
						<div class="card-body pd-40">
							<div class="row">
								<div class="col-md-3" ></a>
									<img src="../img/fim.png" alt="" title="" class="img-thumbnail"/>
								</div>
								<div class="col-md-9" align="justify">
									<br>
									<?php if($prazo <= 5 && $prazo >= 1){?> 
									<span style="font-size:18px"><i class="fa fa-qrcode" aria-hidden="true"></i> OLÁ AMIGO CLIENTE. SEU PLANO VENCE EM <span style="color:#FF0033; font-size:28px"><?php print $prazo;?></span> DIAS.</span><br><br>
									<?php } ?> 
									<?php if($prazo <= 0){?> 
									<span style="font-size:18px"><i class="fa fa-qrcode" aria-hidden="true"></i> OLÁ AMIGO CLIENTE. SEU PLANO ESTÁ <span style="color:#FF0033;">EXPIRADO</span></span><br><br>
									<?php } ?> 
									<span>Clique no botão abaixo para renovar seu plano por mais <b>30 dias</b>. Você será redirecionado para uma tela de pagamento seguro.</span><br><br>
									<center><?php print $configadmin->linkpgmto;?></center><br><br>
									<span>Caso queira um pacote com duração maior que <b>30 dias</b>, clique no ícone do WhatsApp e fale como nosso atendimento comercial. <a href="https://api.whatsapp.com/send?phone=55<?=$configadmin->celular;?>&text=Olá gostaria de renovar meu plano com mais de 30 dias. *<?=$nomeempresa;?>*" target="_blank"><img src="../img/wp.png" width="40"/></span><br>
								</div>
							</div>
						</div>
					</div>
				</div>
				
		  </div>
		  <?php } ?> 
		  
		   
        </div>
		
      </div><!-- slim-mainpanel -->
    </div><!-- slim-body -->
    <script src="../lib/jquery/js/jquery.js"></script>
  </body>
</html>
<?php
ob_end_flush();
?>