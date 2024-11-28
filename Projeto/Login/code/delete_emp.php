<?php
require_once 'conexao.php';

if (isset($_POST['delete_emp']) && isset($_POST['emprestimoid'])) {
    $emprestimoId = $_POST['emprestimoid'];

    // Remove o empréstimo
    $sqlRemoverEmprestimo = "DELETE FROM Emprestimo WHERE id_emprestimo = $emprestimoId";
    if ($mysqli->query($sqlRemoverEmprestimo) === TRUE) {
        header("Location: ../emprestimos.php");
        echo "<script> alert('Foi excluído com sucesso'); </script>";
        exit;
    } else {
        header("Location: ../emprestimos.php");
        echo "<script> alert('Erro ao excluir o empréstimo'); </script>";
        exit;
    }
} else {
    header("Location: ../emprestimos.php");
    exit;
}
?>
