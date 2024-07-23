<?php
ob_start();
session_start();
if((!isset ($_SESSION['cod_id']) == true)) { unset($_SESSION['cod_id']); header('location: ./'); }
$cod_id = $_SESSION['cod_id'];
require_once "../funcoes/Conexao.php";
require_once "../funcoes/Key.php";
require_once "Funcoes.php";
require('Mobile_Detect.php');
$detect = new Mobile_Detect;
?>
<!DOCTYPE html>
<html lang="pt_BR">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Ansonika">
  <title>ADMINISTRAÇÃO CLIENTE</title>
  <link href="../basecard/master/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../basecard/master/css/admin.css" rel="stylesheet">
  <link href="../basecard/master/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link href="../basecard/master/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <link href="../basecard/master/vendor/dropzone.css" rel="stylesheet">
  <link href="../basecard/master/css/date_picker.css" rel="stylesheet">
  <link href="../basecard/master/css/custom.css" rel="stylesheet">
  <link rel="stylesheet" href="../basecard/master/js/editor/summernote-bs4.css">
</head>

<body class="fixed-nav sticky-footer" id="page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-default fixed-top" id="mainNav">
    <a class="navbar-brand" href="index.html"><img src="../basecard/master/img/lg.png" data-retina="true" alt="" width="142" height="36"></a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        
		<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Inicial">
          <a class="nav-link" href="home.php">
            1 - 
            <span class="nav-link-text">Inicial</span>
          </a>
        </li>
		<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dados da Empresa">
          <a class="nav-link" href="dadosempresa.php">
            2 - 
            <span class="nav-link-text">Dados da Empresa</span>
          </a>
        </li>
		<li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Banner's e Logo">
          <a class="nav-link" href="bannerelogo.php">
            3 - 
            <span class="nav-link-text">Banner's e Logo</span></span>
          </a>
        </li>
		
		<li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Bairros e Regiões">
          <a class="nav-link" href="bairros.php">
            4 - 
            <span class="nav-link-text">Bairros e Regiões</span></span>
          </a>
        </li>
		
		<li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Categorias">
          <a class="nav-link" href="categorias.php">
           5 - 
            <span class="nav-link-text">Categorias</span></span>
          </a>
        </li>
		
		<li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Cadastrar Produtos">
          <a class="nav-link" href="produtos.php">
            6 - 
            <span class="nav-link-text">Cadastrar Produtos</span></span>
          </a>
        </li>
		
		<li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Listar Produtos">
          <a class="nav-link" href="listaprodutos.php">
            7 - 
            <span class="nav-link-text">Listar Produtos</span></span>
          </a>
        </li>
		
		<li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Dados do Usuário">
          <a class="nav-link" href="usuario.php">
            8 - 
            <span class="nav-link-text">Dados do Usuário</span></span>
          </a>
        </li>
		
		<li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Dados do Usuário">
          <a class="nav-link" href="filtro.php">
            9 - 
            <span class="nav-link-text">Filtrar Movimento</span></span>
          </a>
        </li>
				
      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
	  
	  
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="sair.php">
            <i class="fa fa-fw fa-sign-out"></i>Sair</a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- /Navigation-->
  <script language="JavaScript">
	window.onload = function() {
		document.addEventListener("contextmenu", function(e){
			e.preventDefault();
		}, false);
		document.addEventListener("keydown", function(e) {
            //document.onkeydown = function(e) {
              // "I" key
              if (e.ctrlKey && e.shiftKey && e.keyCode == 73) {
              	disabledEvent(e);
              }
              // "J" key
              if (e.ctrlKey && e.shiftKey && e.keyCode == 74) {
              	disabledEvent(e);
              }
              // "S" key + macOS
              if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
              	disabledEvent(e);
              }
              // "U" key
              if (e.ctrlKey && e.keyCode == 85) {
              	disabledEvent(e);
              }
              // "F12" key
              if (event.keyCode == 123) {
              	disabledEvent(e);
              }
          }, false);
		function disabledEvent(e){
			if (e.stopPropagation){
				e.stopPropagation();
			} else if (window.event){
				window.event.cancelBubble = true;
			}
			e.preventDefault();
			return false;
		}
	};
</script>