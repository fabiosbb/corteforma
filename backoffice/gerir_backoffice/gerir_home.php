<?php
require_once("../helpers/requisitos.php");


if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit();
}

$mensagemErro    = '';
$mensagemSucesso = '';

// Adicionar imagem ao carrossel

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'adicionar_carrossel') {

  $uploadDir = __DIR__ . '/../../uploads/carrossel/';

  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }

  if (empty($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
      $mensagemErro = 'Selecione uma imagem válida.';
  } else {
    $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg','jpeg','png','gif'];

    if (!in_array($extensao, $permitidas)) {

      $mensagemErro = 'Apenas JPG, PNG ou GIF são permitidos.';

    } else {

      $novoNome = uniqid('car_') . '.' . $extensao;
      $destino  = $uploadDir . $novoNome;

      if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {

        //  salva o caminho correto
        $caminhoDB = "uploads/carrossel/$novoNome";
        iduSQL("INSERT INTO carrossel (imagem, ordem) VALUES (?, ?)", [$caminhoDB, $_POST['ordem'] ?? 0]);
        $mensagemSucesso = 'Imagem adicionada com sucesso!';

      } else {
        $mensagemErro = 'Erro ao guardar o ficheiro.';
      }
    }
  }
}

// Atualizar texto da home
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'atualizar_texto') {
    $titulo    = $_POST['titulo']    ?? '';
    $descricao = $_POST['descricao'] ?? '';
    iduSQL("UPDATE home SET titulo = ?, texto = ? WHERE id = 1", [trim($titulo), trim($descricao)]);
    $mensagemSucesso = 'Texto atualizado com sucesso!';
}

// Atualizar ordem de exibição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'atualizar_carrossel') {
    $id    = $_POST['id']    ?? null;
    $ordem = $_POST['ordem'] ?? null;

    if ($id !== null && in_array($ordem, ['0','1'], true)) {
        iduSQL("UPDATE carrossel SET ordem = ? WHERE id = ?", [$ordem, $id]);
        $mensagemSucesso = 'Exibição atualizada!';
    }
}

// Remover imagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'remover_carrossel') {
    $id = $_POST['id'] ?? null;

    if ($id !== null) {
  
      $imagem = selectSQLUnico("SELECT imagem FROM carrossel WHERE id = ?", [$id]);
      if ($imagem && file_exists(__DIR__ . '/../../' . $imagem['imagem'])) {
        unlink(__DIR__ . '/../../' . $imagem['imagem']);
      }

      iduSQL("DELETE FROM carrossel WHERE id = ?", [$id]);
      $mensagemSucesso = 'Imagem removida!';
  }
}

// Carregar dados
$home      = selectSQLUnico("SELECT * FROM home WHERE id = 1");
$titulo    = $home['titulo'] ?? '';
$descricao = $home['texto']  ?? '';
$carrossel = selectSQL("SELECT * FROM carrossel ORDER BY id ASC");

?>

<main class="container py-5 mb-5">

  <h2 class="text-center mb-4 pb-5 pt-5">Editar - Página Home</h2>

  <?php if ($mensagemErro): ?>
    <div class="alert alert-danger text-center mb-5"><?= htmlspecialchars($mensagemErro) ?></div>
  <?php endif; ?>

  <?php if ($mensagemSucesso): ?>
    <div class="alert alert-success text-center mb-5"><?= htmlspecialchars($mensagemSucesso) ?></div>
  <?php endif; ?>

  <!-- Adicionar imagem -->
  <div class="card mb-4">

    <div class="card-header text-center"><strong>Adicionar Imagem ao Carrossel</strong></div>

    <div class="card-body">

      <form method="POST" enctype="multipart/form-data" class="row g-3">

        <input type="hidden" name="acao" value="adicionar_carrossel">

        <div class="col-md-6">
          <label class="form-label fonte_botoes">Imagem:</label>
          <input type="file" name="imagem" class="form-control" required>
        </div>

        <div class="col-md-4 ps-5 pt-3">
          <label class="form-label">Exibir na Home?</label><br>
          <input type="radio" name="ordem" value="1" checked> Sim
          <input type="radio" name="ordem" value="0"> Não
        </div>

        <div class="col-md-2 text-end ps-5 pt-4">
          <button type="submit" class="btn btn-preto-invert fonte_botoes">Adicionar</button>
        </div>

      </form>
    </div>
  </div>

  <div class="card mb-4">

    <div class="card-header text-center"><strong>Imagens do Carrossel</strong></div>

    <div class="card-body p-0">

      <table class="table table-bordered text-center align-middle mb-0">

        <thead class="table-light">

          <tr>
            <th>Imagem</th>
            <th>Exibir na Página Home</th>
            <th>Remover</th>
          </tr>

        </thead>

        <tbody>

          <?php foreach ($carrossel as $item): ?>

            <tr>

              <td>
               <img src="/projeto_final/<?= htmlspecialchars($item['imagem']) ?>" class="img-carrossel-tabela" alt="Imagem do carrossel">

              </td>
              <td>
                <form method="POST" class="d-flex justify-content-center gap-2 m-0">

                  <input type="hidden" name="acao" value="atualizar_carrossel">
                  <input type="hidden" name="id" value="<?= $item['id'] ?>">
                  <input type="radio" name="ordem" value="1" <?= $item['ordem'] == 1 ? 'checked' : '' ?>> Sim
                  <input type="radio" name="ordem" value="0" <?= $item['ordem'] == 0 ? 'checked' : '' ?>> Não

                  <button type="submit" class="btn btn-sm btn-preto-invert fonte_botoes">Salvar</button>

                </form>
              </td>

              <td>
                <form method="POST" onsubmit="return confirm('Remover esta imagem?')">
                  <input type="hidden" name="acao" value="remover_carrossel">
                  <input type="hidden" name="id" value="<?= $item['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-preto-invert fonte_botoes">Remover</button>
                </form>
              </td>

            </tr>

          <?php endforeach; ?>

        </tbody>

      </table>
    </div>
  </div>

  <div class="card">

    <div class="card-header text-center"><strong>Texto da Página Inicial</strong></div>

    <div class="card-body">

      <form method="POST">

        <input type="hidden" name="acao" value="atualizar_texto">

        <div class="mb-3">
          <label class="form-label fonte_botoes">Título:</label>
          <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($titulo) ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label fonte_botoes">Descrição:</label>
          <textarea name="descricao" rows="5" class="form-control" required><?= htmlspecialchars($descricao) ?></textarea>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-preto-invert mb-5 fonte_botoes">Atualizar</button>
        </div>

      </form>
    </div>
  </div>
</main>

<script>
  window.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => alert.style.display = 'none');
    }, 5000);
  });
</script>



