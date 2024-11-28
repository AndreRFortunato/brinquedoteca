<?php
require_once 'conexao.php';

if (isset($_POST['delete_forn']) && isset($_POST['fornecedorid'])) {
    $fornecedorid = $_POST['fornecedorid'];

    // Verificar se existem registros relacionados na tabela "entrada"
    $sqlVerificarEntrada = "SELECT * FROM entrada WHERE fk_fornecedor = $fornecedorid";
    $resultVerificarEntrada = $mysqli->query($sqlVerificarEntrada);

    //disabilita foreign_key_checks
    $disableForeignKey = "SET foreign_key_checks = 0";
    $mysqli->query($disableForeignKey);

    if ($resultVerificarEntrada->num_rows > 0) {
        // Atualizar o campo fk_exemplar para um valor nulo
        $sqlUpdateEntrada = "UPDATE entrada SET fk_fornecedor = NULL WHERE fk_fornecedor = $fornecedorid";
        if ($mysqli->query($sqlUpdateEntrada) !== TRUE) {
            echo "<div class='alert alert-danger'>Erro ao atualizar registros de entrada.</div>";
            exit;
        }
    }

    $sql = "DELETE FROM Fornecedor WHERE id_fornecedor=" . $_POST['fornecedorid'];
    if($mysqli->query($sql) === TRUE){
        header("Location: ../users.php");
        echo "<script> alert('Foi excluído com sucesso'); </script>";
        exit;
    } else {
        header("Location: ../users.php");
        echo "<script> alert('Erro ao excluir usuário'); </script>";
        exit;
    }

    //disabilita foreign_key_checks
    $enableForeignKey = "SET foreign_key_checks = 1";
    $mysqli->query($enableForeignKey);

} else {
    header("Location: ../users.php");
    exit;
}
?>
