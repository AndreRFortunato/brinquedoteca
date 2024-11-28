<?php 
require_once 'code/conexao.php';

require_once 'header.php';

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

?>
<div class="container">
<?php

    if(isset($_POST['update'])){
        if( empty($_POST['nome']) || empty($_POST['documento']) ) {
            echo "Please fill out all required fields"; 
        }else{		
            $nome  = $_POST['nome'];
            $documento = $_POST['documento'];
            $id = $_POST['userid'];

            // Verificar se o CPF/CNPJ já existe na tabela Fornecedor, exceto para o próprio fornecedor sendo alterado
            $query = "SELECT COUNT(*) FROM Fornecedor WHERE (documento = '$documento' AND id_fornecedor <> $id)";
            $result = $mysqli->query($query);
            $count = $result->fetch_row()[0];

            if ($count > 0) {
                echo "<script>alert('CPF/CNPJ já cadastrado para outro fornecedor');</script>";
            } else {
                $sql = "UPDATE Fornecedor SET nome='{$nome}', documento = '{$documento}' WHERE id_fornecedor={$id}";

                if( $mysqli->query($sql) === TRUE){
                    echo "<script>alert('Alterado com sucesso');</script>";
                }else{
                    echo "<script>alert('ERRO');</script>";
                }
            }
        }
    }
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $sql = "SELECT * FROM Fornecedor WHERE id_fornecedor={$id}";
    $result = $mysqli->query($sql);

    if($result->num_rows < 1){
        header('Location: index.php');
        exit;
    }
    $row = $result->fetch_assoc();
    ?>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="box">
                <h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Alterar Fornecedor</h3> 
                <form action="" method="POST" onsubmit="return validarFormulario()">
                    <input type="hidden" value="<?php echo $row['id_fornecedor']; ?>" name="userid">
                    <label for="nome">Nome</label>
                    <input type="text" id="nome"  name="nome" value="<?php echo $row['nome']; ?>" class="form-control"><br>
                    <label for="documento">Documento</label>
                    <input type="text" name="documento" id="documento" value="<?php echo $row['documento']; ?>" class="form-control"><br>
                    <br>
                    <input type="submit" name="update" class="btn btn-success" value="Alterar">
                    <a href="index.php" class="btn btn-danger">Cancelar</a>
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
<?php 

 require_once 'code/footer.php';