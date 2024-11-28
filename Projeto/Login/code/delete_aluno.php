<?php
require_once 'conexao.php';

if (isset($_POST['delete_aluno']) && isset($_POST['alunoid'])) {
    $alunoid = $_POST['alunoid'];

    // Consulta para obter os id_emprestimo relacionados ao aluno
    $sqlGetEmprestimos = "SELECT id_emprestimo FROM Emprestimo WHERE fk_aluno = $alunoid";
    $resultGetEmprestimos = $mysqli->query($sqlGetEmprestimos);

    if ($resultGetEmprestimos->num_rows > 0) {
        // Exclua os id_emprestimo relacionados ao aluno
        while ($row = $resultGetEmprestimos->fetch_assoc()) {
            $emprestimoId = $row['id_emprestimo'];
            $sqlDeleteEmprestimo = "DELETE FROM Emprestimo WHERE id_emprestimo = $emprestimoId";
            $mysqli->query($sqlDeleteEmprestimo);
        }
    }

    // Exclua o aluno
    $sqlDeleteAluno = "DELETE FROM Aluno WHERE id_aluno = $alunoid";
    if ($mysqli->query($sqlDeleteAluno) === TRUE) {
        header("Location: ../users.php");
        echo "<script> alert('Foi excluído com sucesso'); </script>";
        exit;
    } else {
        header("Location: ../users.php");
        echo "<script> alert('Erro ao excluir usuário'); </script>";
        exit;
    }
} else {
    // Redirecionar para brinquedos.php exibindo a mensagem de erro
    header("Location: ../users.php");
    exit;
}
?>
