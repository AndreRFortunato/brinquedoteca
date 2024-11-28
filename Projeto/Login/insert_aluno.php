<?php 
require_once 'code/conexao.php';
require_once 'header.php';
include('protect.php');
?>
<div class="container">
	<?php 

	if(isset($_POST['addnew'])){

		if( empty($_POST['nome']) || empty($_POST['ra']) || empty($_POST['turma']) ){
			echo "<script>alert('Por favor, preencha todos os campos');</script>";
		}else{		
			$nome  = $_POST['nome'];
			$ra = $_POST['ra'];
			$turma  = $_POST['turma'];
			$sql = "INSERT INTO Aluno(nome,ra,turma) 
		    VALUES('$nome','$ra','$turma')";

			if( $mysqli->query($sql) === TRUE){
				echo "<script>alert('Cadastro realizado com sucesso');</script>";
			}else{
				echo "<div class='alert alert-danger'>Error: There was an error while adding new user</div>";
			}
		}
	}
	?>
	<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="box">
			<h3><i class="glyphicon glyphicon-plus"></i>&nbsp;Cadastrar Aluno</h3> 
			<form action="" method="POST">
				<label for="nome">Nome</label>
				<input type="text" id="nome"  name="nome" class="form-control"><br>
				<label for="ra">RA</label>
				<input type="text" name="ra" id="ra" class="form-control"><br>
				<label for="turma">Turma</label> 
				<input type="text"  name="turma" id="turma" class="form-control"><br>
				<input type="submit" name="addnew" class="btn btn-success" value="Adicionar">
			</form>
		</div>
	</div>
</div>
</div>

<script>
function validarFormulario() {
    var nome = document.getElementById("nome").value;
    var ra = document.getElementById("ra").value;
    var turma = document.getElementById("turma").value;

    if (nome === "" || ra === "" || turma === "") {
        alert("Por favor, preencha todos os campos");
        return false;
    }

    return true;
}
</script>

<?php 

 require_once 'code/footer.php';
