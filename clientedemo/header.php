<?php
ob_start();
session_start();
if(!isset($_SESSION['id_cliente'])) {
for ($i = 0; $i < 10; $i++) {
$id_pedido = rand(100000, 999999)."PW";
$_SESSION["id_cliente"] = $id_pedido;}
} else { $id_cliente = $_SESSION['id_cliente'];
}
if(!isset($id_cliente)) { header("location: ./"); exit; }
include('../funcoes/Conexao.php');
include('../funcoes/Key.php');
include('db/base.php');
$xurl = 'clientedemo';
$site = HOME;
include('db/Funcoes.php');
$Url[1] = (empty($Url[1]) ? null : $Url[1]);
