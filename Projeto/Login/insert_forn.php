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

function validarCNPJ($cnpj) {
    // Remover caracteres não numéricos
    $cnpj = preg_replace('/[^0-9]/is', '', $cnpj);

    // Verifica se o CNPJ possui 14 dígitos
    if (strlen($cnpj) != 14) {
        return false;
    }

    // Validação do primeiro dígito verificador
    $soma = 0;
    $peso = 5;
    for ($i = 0; $i < 12; $i++) {
        $soma += intval($cnpj[$i]) * $peso;
        $peso = ($peso == 2) ? 9 : $peso - 1;
    }

    $resto = $soma % 11;
    $digito1 = ($resto < 2) ? 0 : 11 - $resto;

    if ($cnpj[12] != $digito1) {
        return false;
    }

    // Validação do segundo dígito verificador
    $soma = 0;
    $peso = 6;
    for ($i = 0; $i < 13; $i++) {
        $soma += intval($cnpj[$i]) * $peso;
        $peso = ($peso == 2) ? 9 : $peso - 1;
    }

    $resto = $soma % 11;
    $digito2 = ($resto < 2) ? 0 : 11 - $resto;

    if ($cnpj[13] != $digito2) {
        return false;
    }

    return true;
}

if (isset($_POST['addnew'])) {
    if (empty($_POST['nome']) || empty($_POST['documento'])) {
        echo "<script>alert('Por favor, preencha todos os campos');</script>";
    } else {
        $nome  = $_POST['nome'];
        $documento = $_POST['documento'];

        // Verificar se é CPF ou CNPJ
        if (strlen($documento) == 11 && !validarCPF($documento)) {
            echo "<script>alert('CPF inválido');</script>";
        } elseif (strlen($documento) == 14 && !validarCNPJ($documento)) {
            echo "<script>alert('CNPJ inválido');</script>";
        } else {
            // Verificar se já existe um CPF ou CNPJ igual na tabela Fornecedor
            $query = "SELECT COUNT(*) FROM Fornecedor WHERE documento = '$documento'";
            $result = $mysqli->query($query);
            $count = $result->fetch_row()[0];

            if ($count > 0) {
                echo "<script>alert('CPF ou CNPJ já cadastrado');</script>";
            } else {
                $sql = "INSERT INTO Fornecedor(nome, documento) 
                        VALUES ('$nome', '$documento')";

                if ($mysqli->query($sql) === TRUE) {
                    echo "<script>alert('Fornecedor cadastrado com sucesso');</script>";
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
                <h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastrar Fornecedor</h3> 
                <form action="" method="POST" onsubmit="return validarFormulario()">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome" name="nome" class="form-control"><br>
                    <label for="documento">Documento</label>
                    <input type="text" name="documento" id="documento" class="form-control"><br>
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
    var documento = document.getElementById('documento').value;

    if (nome === '') {
        alert('Preencha o campo Nome');
        return false;
    }

    if (documento === '') {
        alert('Preencha o campo Documento');
        return false;
    }

    if (documento.length === 11) {
        // Validar CPF utilizando a função PHP
        if (!validarCPF(documento)) {
            alert('CPF inválido');
            return false;
        }
    } else if (documento.length === 14) {
        // Validar CNPJ utilizando a função PHP
        if (!validarCNPJ(documento)) {
            alert('CNPJ inválido');
            return false;
        }
    } else {
        alert('Documento inválido');
        return false;
    }

    return true;
}
</script>

<?php
require_once 'code/footer.php';
?>
