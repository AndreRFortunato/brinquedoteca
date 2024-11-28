<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="code/header.css">
  <link rel="icon" type="image/x-icon" href="img/einstein.ico">
  <title>Brinquedoteca</title>
</head>
<body>
  <header>
      <div class="container-logo">
          <div class="logo-texto"><a href="https://www.einsteinlimeira.com.br/portal/public/"> 
            <input type="image" src="img/einstein.png"> 
        </a></div>
      </div>

      <div class="menu">
        <ul>
          <li class="active"><a href="index.php">Início</a></li>
          <li class="dropdown nav-item">
            <a class="dropdown-toggle nav-link" id="drop-down-insert" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#" aria-controls="drop-down-menu-insert">
              Cadastrar
            </a>
              <div class="dropdown-menu" role="menu" id="drop-down-menu-insert" aria-labelledby="drop-down-insert">
                <a class="dropdown-item" role="menuitem" href="insert_func.php">Funcionário</a>
                <a class="dropdown-item" role="menuitem" href="insert_aluno.php">Aluno</a>
                <a class="dropdown-item" role="menuitem" href="insert_forn.php">Fornecedor</a>
                <a class="dropdown-item" role="menuitem" href="insert_brinq.php">Brinquedo</a>
                <a class="dropdown-item" role="menuitem" href="insert_emprestimo.php">Empréstimo</a>
                <a class="dropdown-item" role="menuitem" href="insert_Exemplar.php">Entrada</a>
                <a class="dropdown-item" role="menuitem" href="insert_saida.php">Saída</a>
              </div>
          </li>
          <li><a href="users.php">Todos Usuários</a></li>
          <li><a href="brinquedos.php">Brinquedos</a></li>
          <li><a href="emprestimos.php">Empréstimos</a></li>
          <li><a href="logout.php">Sair</a></li>
        </ul>
      </div> 
  </header>
</body>
</html>