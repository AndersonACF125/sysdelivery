<?php
require_once "config.php"; // Inclua sua configuração de banco de dados


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['iddocupom'])) {
        $id_cupom = $_POST['iddocupom'];

        // Conexão com o banco de dados
        try {
			$connect = new PDO("mysql:host=localhost;dbname=sys_delivery", "root", "");

            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Excluir o cupom
            $stmt = $connect->prepare("DELETE FROM cupom_desconto WHERE id_cupom = :id_cupom");
            $stmt->bindParam(':id_cupom', $id_cupom, PDO::PARAM_INT);
            $stmt->execute();

            echo 'success';
        } catch (PDOException $e) {
            echo 'error';
        }
    }
}
?>