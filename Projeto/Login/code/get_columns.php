<?php
require_once 'conexao.php';

if (isset($_GET['tabela'])) {
    $tabelaSelecionada = $_GET['tabela'];

    // Consulta para obter os nomes das colunas da tabela selecionada
    $sqlColunas = "SHOW COLUMNS FROM " . $tabelaSelecionada;
    $resultColunas = $mysqli->query($sqlColunas);

    $colunas = [];

    if ($resultColunas->num_rows > 0) {
        while ($rowColunas = $resultColunas->fetch_assoc()) {
            $colunas[] = $rowColunas['Field'];
        }
    }

    // Retorna as colunas como resposta em formato JSON
    echo json_encode($colunas);
} else {
    echo "Tabela não especificada";
}
?>