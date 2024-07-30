<?php
require_once "../../../funcoes/Conexao.php";
require_once "../../../funcoes/Key.php";


if(isset($_POST["data_i"])){

$user_o = $_POST["idusr"];

$data_i = date('Y-m-d', strtotime($_POST['data_i']));
$data_f = date('Y-m-d', strtotime($_POST['data_f']));

// 

$todia = $connect->query("SELECT vtotal, SUM(vtotal) AS somames FROM pedidos WHERE DATE(entrada) BETWEEN '".$data_i."' AND '".$data_f."' AND idu = '".$user_o."' AND status ='1'");
$todia = $todia->fetch(PDO::FETCH_OBJ);

$todiax = $connect->query("SELECT id FROM pedidos WHERE DATE(entrada) BETWEEN '".$data_i."' AND '".$data_f."' AND idu = '".$user_o."' AND status ='1'");
$todiax	= $todiax->rowCount();

$cancelados = $connect->query("SELECT vtotal, SUM(vtotal) AS somacan FROM pedidos WHERE DATE(entrada) BETWEEN '".$data_i."' AND '".$data_f."' AND idu = '".$user_o."' AND status ='6'");
$cancelados = $cancelados->fetch(PDO::FETCH_OBJ);

$canceladosx = $connect->query("SELECT id FROM pedidos WHERE DATE(entrada) BETWEEN '".$data_i."' AND '".$data_f."' AND idu = '".$user_o."' AND status ='6'");
$canceladosx = $canceladosx->rowCount();
			  
$nomex 	= "Relatoriosintetico";

$dia = date("d");
$ano = date("Y");
$seg = date("s");
$mes = date("m");

$arquivo = $nomex.$dia.$mes.$ano.$seg.'.xls';

// Criamos uma tabela HTML com o formato da planilha para excel
$tabela = '<table border="1">';
$tabela .= '<tr>';
$tabela .= '<td colspan="4" align="center">Relatorio Sintetico de '.$_POST['data_i'].' a '.$_POST['data_f'].'</tr>';
$tabela .= '</tr>';
$tabela .= '<tr>';
$tabela .= '<td><b>Pedidos Finalizado</b></td>';
$tabela .= '<td><b>Quantidade Finalizado</b></td>';
$tabela .= '<td><b>Pedidos Cancelados</b></td>';
$tabela .= '<td><b>Quantidade Cancelados</b></td>';
$tabela .= '</tr>';

$tabela .= '<tr>';
$tabela .= '<td>'.number_format($todia->somames, 2, ',', '.').'</td>';
$tabela .= '<td>'.$todiax.'</td>';
$tabela .= '<td>'.number_format($cancelados->somacan, 2, ',', '.').'</td>';
$tabela .= '<td>'.$canceladosx.'</td>';
$tabela .= '</tr>';
 
$tabela .= '</table>';

// Força o Download do Arquivo Gerado
header ('Cache-Control: no-cache, must-revalidate');
header ('Pragma: no-cache');
header('Content-Type: application/x-msexcel');
header ("Content-Disposition: attachment; filename=\"{$arquivo}\"");
echo $tabela;

}
?>