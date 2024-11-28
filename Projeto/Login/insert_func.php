<?php
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');

function validarCPF($cpf) {
    // Remover caracteres não numéricos
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);

    // Verifica se o CPF possui 11 dígitos
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se todos os dígitos são iguais, o que é inválido
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Validação dos dígitos verificadores
    $soma = 0;
    for ($i = 0; $i < 9; $i++) {
        $soma += intval($cpf[$i]) * (10 - $i);
    }

    $resto = $soma % 11;
    $digito1 = ($resto < 2) ? 0 : 11 - $resto;

    if ($cpf[9] != $digito1) {
        return false;
    }

    $soma = 0;
    for ($i = 0; $i < 10; $i++) {
        $soma += intval($cpf[$i]) * (11 - $i);
    }

    $resto = $soma % 11;
    $digito2 = ($resto < 2) ? 0 : 11 - $resto;

    if ($cpf[10] != $digito2) {
        return false;
    }

    return true;
}

if (isset($_POST['addnew'])) {
    if (empty($_POST['nome']) || empty($_POST['cpf'])) {
        echo "<script>alert('Preencha os campos Nome e CPF');</script>";
    } else {
        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];

        // Validar CPF
        if (!validarCPF($cpf)) {
            echo "<script>alert('CPF inválido');</script>";
        } else {
            // Verificar se o CPF já existe na tabela Funcionario
            $cpfExistente = false;
            $sql = "SELECT cpf FROM Funcionario WHERE cpf = '$cpf'";
            $result = $mysqli->query($sql);
            if ($result && $result->num_rows > 0) {
                $cpfExistente = true;
            }

            if ($cpfExistente) {
                echo "<script>alert('CPF já cadastrado');</script>";
            } else {
                $sql = "INSERT INTO Funcionario(nome, cpf) 
                        VALUES('$nome', '$cpf')";

                if ($mysqli->query($sql) === TRUE) {
                    echo "<script>alert('Funcionário cadastrado com sucesso');</script>";
                } else {
                    echo "<div class='alert alert-danger'>Error: There was an error while adding new user</div>";
                }
            }
        }
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="box">
                <h2><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastrar Funcionário</h2>
                <br>
                <form action="" method="POST" onsubmit="return validarFormulario()">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" class="form-control"><br>
                    <label for="cpf">CPF</label>
                    <input type="text" name="cpf" id="cpf" class="form-control"><br>
                    <br>
                    <input type="submit" name="addnew" class="btn btn-success" value="Adicionar">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function validarFormulario() {
    var nome = document.getElementById('nome').value;
    var cpf = document.getElementById('cpf').value;

    if (nome === '') {
        alert('Preencha o campo Nome');
        return false;
    }

    if (cpf === '') {
        alert('Preencha o campo CPF');
        return false;
    }

    // Validar CPF utilizando a função PHP
    if (!validarCPF(cpf)) {
        alert('CPF inválido');
        return false;
    }

    return true;
}
</script>

<?php
require_once 'code/footer.php';
?>
