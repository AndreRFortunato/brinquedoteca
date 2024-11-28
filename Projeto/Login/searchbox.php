<?php
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="box">
                <h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Pesquisar</h3> 
                <br>
                <form action="" method="POST" id="form-pesquisa">
                    <select name="tabela" id="tabela" class="form-control">
                        <?php
                        $sql = "SHOW TABLES";
                        $result = $mysqli->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['Tables_in_banco-php'] . "'>" . $row['Tables_in_banco-php'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>Nenhum dado encontrado</option>";
                        }
                        ?>
                    </select>
                    <br>
                    <select name="colunas" id="colunas" class="form-control">
                        <!-- Opções de colunas serão adicionadas dinamicamente via JavaScript -->
                    </select>
                    <br>
                    <label for="pesquisa">Pesquisa</label> 
                    <input type="text" name="valor-pesquisa" id="valor-pesquisa" class="form-control"><br>
                    <input type="submit" name="Search" class="btn btn-success" value="Pesquisar">
                </form>
            </div>
        </div>
    </div>
</div>

<div id="resultado-pesquisa"></div>

<script type="text/javascript">
    // Função para atualizar as opções de colunas com base na tabela selecionada
    function updateColumns() {
        var tabelaSelecionada = document.getElementById("tabela").value;
        var selectColunas = document.getElementById("colunas");

        // Limpa as opções atuais
        selectColunas.innerHTML = "";

        // Consulta as colunas via requisição AJAX
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var colunas = JSON.parse(xhr.responseText);

                    // Cria as opções de colunas
                    for (var i = 0; i < colunas.length; i++) {
                        var option = document.createElement("option");
                        option.value = colunas[i];
                        option.text = colunas[i];
                        selectColunas.appendChild(option);
                    }
                } else {
                    console.error("Erro na requisição: " + xhr.status);
                }
            }
        };
        xhr.open("GET", "code/get_columns.php?tabela=" + tabelaSelecionada, true);
        xhr.send();
    }

    // Evento que dispara quando a tabela selecionada é alterada
    document.getElementById("tabela").addEventListener("change", updateColumns);

    // Atualiza as opções de colunas quando a página é carregada
    updateColumns();

    // Submissão do formulário de pesquisa
    document.getElementById("form-pesquisa").addEventListener("submit", function(event) {
        event.preventDefault(); // Impede o envio do formulário

        var tabelaSelecionada = document.getElementById("tabela").value;
        var colunaSelecionada = document.getElementById("colunas").value;
        var valorPesquisa = document.getElementById("valor-pesquisa").value;

        // Realiza a pesquisa via requisição AJAX
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var resultado = xhr.responseText;
                    document.getElementById("resultado-pesquisa").innerHTML = resultado;
                } else {
                    console.error("Erro na requisição: " + xhr.status);
                }
            }
        };
        xhr.open("GET", "code/pesquisa.php?tabela=" + tabelaSelecionada + "&coluna=" + colunaSelecionada + "&valor=" + valorPesquisa, true);
        xhr.send();
    });
</script>

<?php 
require_once 'code/footer.php';
?>
