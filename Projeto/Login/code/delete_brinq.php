<?php
require_once 'conexao.php';

if (isset($_POST['delete_brinq']) && isset($_POST['exemplarid'])) {
    $exemplarId = $_POST['exemplarid'];

    // Verificar se existem registros relacionados na tabela "emprestimo"
    $sqlVerificarEmprestimo = "SELECT * FROM emprestimo WHERE fk_exemplar = $exemplarId";
    $resultVerificarEmprestimo = $mysqli->query($sqlVerificarEmprestimo);

    if ($resultVerificarEmprestimo->num_rows > 0) {
        // Excluir os registros da tabela "emprestimo" relacionados ao exemplar
        $sqlDeleteEmprestimo = "DELETE FROM emprestimo WHERE fk_exemplar = $exemplarId";
        if ($mysqli->query($sqlDeleteEmprestimo) !== TRUE) {
            echo "<div class='alert alert-danger'>Erro ao excluir registros de empréstimo.</div>";
            exit;
        }
    }

    // Verificar se existem registros relacionados na tabela "entrada"
    $sqlVerificarEntrada = "SELECT * FROM entrada WHERE fk_exemplar = $exemplarId";
    $resultVerificarEntrada = $mysqli->query($sqlVerificarEntrada);

    if ($resultVerificarEntrada->num_rows > 0) {
        // Verificar se é o último fk_exemplar relacionado com id_entrada
        $sqlVerificarUltimoExemplar = "SELECT * FROM entrada WHERE id_entrada = (SELECT id_entrada FROM entrada WHERE fk_exemplar = $exemplarId) AND fk_exemplar <> $exemplarId";
        $resultVerificarUltimoExemplar = $mysqli->query($sqlVerificarUltimoExemplar);

        if ($resultVerificarUltimoExemplar->num_rows == 0) {
            // É o último fk_exemplar relacionado, excluir também o id_entrada
            $sqlDeleteEntrada = "DELETE FROM entrada WHERE fk_exemplar = $exemplarId";
            if ($mysqli->query($sqlDeleteEntrada) !== TRUE) {
                echo "<div class='alert alert-danger'>Erro ao excluir entrada.</div>";
                exit;
            }
        }
    }

    // Excluir o fk_exemplar da tabela "entrada"
    $sqlUpdateEntrada = "UPDATE entrada SET fk_exemplar = NULL WHERE fk_exemplar = $exemplarId";
    if ($mysqli->query($sqlUpdateEntrada) === TRUE) {
        echo "<div class='alert alert-success'>Exclusão do fk_exemplar realizada com sucesso.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao excluir fk_exemplar.</div>";
    }
    
    // Verificar se existem registros relacionados na tabela "Saida"
    $sqlVerificarSaida = "SELECT * FROM Saida WHERE fk_exemplar = $exemplarId";
    $resultVerificarSaida = $mysqli->query($sqlVerificarSaida);

    if ($resultVerificarSaida->num_rows > 0) {
        // Excluir os registros da tabela "Saida" relacionados ao exemplar
        $sqlDeleteSaida = "DELETE FROM Saida WHERE fk_exemplar = $exemplarId";
        if ($mysqli->query($sqlDeleteSaida) !== TRUE) {
            echo "<div class='alert alert-danger'>Erro ao excluir registros de saída.</div>";
            exit;
        }
    }

    // Excluir o exemplar da tabela "Exemplar"
    $sqlDeleteExemplar = "DELETE FROM Exemplar WHERE id_exemplar = $exemplarId";
    if ($mysqli->query($sqlDeleteExemplar) === TRUE) {
        header("Location: ../brinquedos.php");
        echo "<script> alert('Foi excluído com sucesso'); </script>";
        exit;
    } else {
        header("Location: ../brinquedos.php");
        echo "<script> alert('Erro'); </script>";
        exit;
    }

} else {
    header("Location: ../brinquedos.php");
    echo "<script> alert('Erro'); </script>";
    exit;
}
?>
