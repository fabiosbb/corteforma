<?php
require_once("../helpers/requisitos.php");

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit();
}

$mensagemSucesso = "";

// Envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $id = $_POST['id'];

  // Busca os dados antigos
  $dadosAntigos = selectSQLUnico("SELECT texto, imagem FROM sobre WHERE id = ?", [$id]);

  // Mantém texto antigo se estiver vazio
  $novoTexto = !empty(trim($_POST['texto'])) ? $_POST['texto'] : $dadosAntigos['texto'];

  // Verifica se uma nova imagem foi enviada
  if (!empty($_FILES['imagem']['name'])) {

    $nomeOriginal = basename($_FILES['imagem']['name']);
    $nomeSanitizado = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $nomeOriginal);
    $novaImagem = 'imagens/' . $nomeSanitizado;
    move_uploaded_file($_FILES['imagem']['tmp_name'], '../' . $novaImagem);

  } else {
    $novaImagem = $dadosAntigos['imagem'];
  }

  // Atualiza na bd
  iduSQL("UPDATE sobre SET texto = ?, imagem = ? WHERE id = ?", [$novoTexto, $novaImagem, $id]);

  // Mensagem de sucesso
  $_SESSION['mensagemSucesso'] = "Registro enviado com sucesso!";
  header("Location: gerir_sobre.php");
  exit();
}

// Busca os dados para exibição
$sobre = selectSQL("SELECT * FROM sobre");

?>

<main class="container py-5">

  <h2 class="pt-5 pb-5 text-center">Editar - Página Sobre</h2>

  <?php if (!empty($_SESSION['mensagemSucesso'])): ?>
    <div class="alert alert-success mensagem-temporaria text-center mx-auto mb-4" style="max-width: 500px;">
      ✅ <?= htmlspecialchars($_SESSION['mensagemSucesso']) ?>
    </div>
  <?php unset($_SESSION['mensagemSucesso']); ?>
  <?php endif; ?>

  <?php foreach ($sobre as $item): ?>

    <form method="post" enctype="multipart/form-data" class="form-edicao border rounded p-4 mb-5 bg-light">

      <input type="hidden" name="id" value="<?= $item['id'] ?>" />

      <div class="row align-items-start">

        <div class="col-md-4 text-center pe-md-4">
          <img src="../<?= $item['imagem'] ?>" class="img-fluid shadow imagem-ajustada mb-3" />
          <label class="form-label rotulo pb-3">Imagem atual</label><br>

        </div>

        <div class="col-md-8">

          <label class="form-label rotulo pt-3">Texto:</label>
          <textarea name="texto" rows="6" class="form-control mb-3"><?= htmlspecialchars($item['texto']) ?></textarea>

          <label class="form-label rotulo pt-3">Nova imagem:</label>
          <input type="file" name="imagem" class="form-control mb-3">

          <div class="text-end pt-5">
            <button type="submit" class="btn btn-outline-dark px-4 fonte_botoes" style="transition: 0.3s;" onmouseover="this.classList.add('bg-dark','text-white')" onmouseout="this.classList.remove('bg-dark','text-white')">Salvar alterações</button>
          </div>
          
        </div>

      </div>

    </form>

  <?php endforeach; ?>

</main>




