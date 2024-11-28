<?php
require_once 'conexao.php';


if (isset($_POST['delete_func']) && isset($_POST['funcionarioid'])) {
    $funcionarioId = $_POST['funcionarioid'];

    // Consulta para obter os id_emprestimo relacionados ao funcionário
    $sqlGetEmprestimos = "SELECT id_emprestimo FROM Emprestimo WHERE fk_funcionario = $funcionarioId";
    $resultGetEmprestimos = $mysqli->query($sqlGetEmprestimos);

    if ($resultGetEmprestimos->num_rows > 0) {
        header("Location: ../users.php");
        echo "<script> alert('O Funcionário não pode ser excluído por que existe empréstimos relacionados.'); </script>";
        exit;

        // // Exclua os id_emprestimo relacionados ao funcionário
        // while ($row = $resultGetEmprestimos->fetch_assoc()) {
        //     $emprestimoId = $row['id_emprestimo'];
        //     $sqlDeleteEmprestimo = "DELETE FROM Emprestimo WHERE id_emprestimo = $emprestimoId";
        //     $mysqli->query($sqlDeleteEmprestimo);
        // }
    } else{
        echo $resultGetEmprestimos;
        // Exclua o funcionário
        //$sqlDeleteFuncionario = "DELETE FROM Funcionario WHERE id_funcionario = $funcionarioId";
        if ($mysqli->query($sqlDeleteFuncionario) === TRUE) {
            header("Location: ../users.php");
            echo "<script> alert('Foi excluído com sucesso'); </script>";
            exit;
        } else {
            header("Location: ../users.php");
            exit;
        }
    }
} else {
    header("Location: ../users.php");
    exit;
}
?>
