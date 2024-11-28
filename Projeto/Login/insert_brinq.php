<?php 
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');
?>
<div class="container">
	<?php 

	if(isset($_POST['addnew'])){

		if( empty($_POST['nome'])  || empty($_POST['faixaEtaria']) 
			|| empty($_POST['fk_tipo']) || empty($_POST['fk_areaDesenvolvimento'])){
			echo "<script>alert('Por favor, preencha todos os campos');</script>";
		}else{		
			$nome  = $_POST['nome'];
			$descricao 	= $_POST['descricao'];
			$faixaEtaria  	= $_POST['faixaEtaria'];
			$fk_tipo  	= $_POST['fk_tipo'];
			$fk_areaDesenvolvimento  = $_POST['fk_areaDesenvolvimento'];
			$sql = "INSERT INTO Brinquedo(nome,descricao,faixaEtaria,fk_tipo,fk_areaDesenvolvimento) 
		    VALUES('$nome','$descricao','$faixaEtaria','$fk_tipo','$fk_areaDesenvolvimento')";

			if( $mysqli->query($sql) === TRUE){
				echo "<script>alert('Cadastro realizado com sucesso');</script>";
			}else{
				echo "<div class='alert alert-danger'>Error: There was an error while adding new user</div>";
			}
		}
	}

	$query_tipo = "SELECT id_tipo, descricao FROM Tipo";
	$result_tipo = $mysqli->query($query_tipo);

	$query_areaDesenvolvimento = "SELECT id_areaDesenvolvimento, descricao FROM AreaDesenvolvimento";
	$result_areaDesenvolvimento = $mysqli->query($query_areaDesenvolvimento);

	?>
	<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="box">
			<h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastrar de Brinquedo</h3> 
			<form action="" method="POST">
				<label for="nome">Nome</label>
				<input type="text" id="nome"  name="nome" class="form-control"><br>
				<label for="faixaEtaria">Faixa Etária (Anos)</label> 
				<input type="text"  name="faixaEtaria" id="faixaEtaria" class="form-control"><br>
				<label for="fk_tipo">Tipo
					<select name="fk_tipo" id="fk_tipo" class="form-control">
						<?php
						if ($result_tipo->num_rows > 0) {
							while ($row = $result_tipo->fetch_assoc()) {
							echo "<option value='" . $row['id_tipo'] . "'>" . $row['id_tipo'] . " - " . $row['descricao'] . "</option>";
							}
						} else {
							echo "<option value=''>Nenhum dado encontrado</option>";
						}
						?>
					</select>
				</label>
				<br>
				<label for="fk_areaDesenvolvimento">Área de Desenvolvimento
					<select name="fk_areaDesenvolvimento" id="fk_areaDesenvolvimento" class="form-control">
						<?php
						if ($result_areaDesenvolvimento->num_rows > 0) {
							while ($row = $result_areaDesenvolvimento->fetch_assoc()) {
							echo "<option value='" . $row['id_areaDesenvolvimento'] . "'>" . $row['id_areaDesenvolvimento'] . " - " . $row['descricao'] . "</option>";
							}
						} else {
							echo "<option value=''>Nenhum dado encontrado</option>";
						}
						?>
					</select>
				</label>
				<br>
				<label for="descricao">Descrição</label>
				<textarea rows="4" name="descricao" id="descricao" class="form-control"></textarea><br>
				<input type="submit" name="addnew" class="btn btn-success" value="Adicionar">
			</form>
		</div>
	</div>
</div>
</div>

<script>
function cadastrarBrinquedo() {
  var nome = document.getElementById("nome").value;
  var faixaEtaria = document.getElementById("faixaEtaria").value;
  var tipo = document.getElementById("fk_tipo").value;
  var areaDesenvolvimento = document.getElementById("fk_areaDesenvolvimento").value;

  if (nome === "" || faixaEtaria === "" || tipo === "" || areaDesenvolvimento === "") {
    alert("Preencha todos os campos a seguir.");
  } else {
    alert("Cadastro com sucesso.");
  }
}
</script>

<?php 

 require_once 'code/footer.php';