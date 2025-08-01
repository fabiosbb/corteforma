<?php
require_once(__DIR__ . "/../../helpers/requisitos.php");

define('BASE_URL', '/projeto_final/backoffice/');

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit();
}

$mensagemErro = '';
$mensagemSucesso = '';

$servicos = selectSQL("SELECT * FROM servicos ORDER BY id DESC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $acao = $_POST['acao'] ?? '';

    if ($acao === 'adicionar_servico') {

        $exibir = isset($_POST['exibir_na_home']) ? '1' : '0';
        $titulo = trim($_POST['titulo'] ?? '');
        $uploadDir = __DIR__ . '/../../uploads/servicos/';

        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        if (!empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {

            $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($ext, $permitidas)) {

                $novoNome = uniqid('srv_') . '.' . $ext;
                $destino = $uploadDir . $novoNome;

                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {

                    $caminho = "uploads/servicos/$novoNome";
                    iduSQL("INSERT INTO servicos (titulo, imagem, exibir_na_home) VALUES (?, ?, ?)", [$titulo, $caminho, $exibir]);
                    $mensagemSucesso = 'Serviço adicionado com sucesso!';
                    $servicos = selectSQL("SELECT * FROM servicos ORDER BY id DESC");

                } else {
                    $mensagemErro = 'Erro ao salvar a imagem.';
                }
            } else {
                $mensagemErro = 'Formato de imagem inválido.';
            }
        } else {
            $mensagemErro = 'Selecione uma imagem.';
        }
    }

    if ($acao === 'apagar_servico') {

        $id = $_POST['id'] ?? null;

        if ($id) {
            $servico = selectSQLUnico("SELECT imagem FROM servicos WHERE id = ?", [$id]);
            if ($servico && file_exists(__DIR__ . '/../../' . $servico['imagem'])) {
                unlink(__DIR__ . '/../../' . $servico['imagem']);
            }

            iduSQL("DELETE FROM servicos WHERE id = ?", [$id]);
            $mensagemSucesso = 'Serviço removido.';
            $servicos = selectSQL("SELECT * FROM servicos ORDER BY id DESC");
        }
    }

    if ($acao === 'atualizar_servico') {
        $id = $_POST['id'] ?? null;
        $titulo = trim($_POST['titulo'] ?? '');
        $exibir = isset($_POST['exibir_na_home']) ? '1' : '0';

        if ($id && $titulo !== '') {
            $campos = ["titulo = ?", "exibir_na_home = ?"];
            $valores = [$titulo, $exibir];

            if (!empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
                $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($ext, $permitidas)) {
                    $uploadDir = __DIR__ . '/../../uploads/servicos/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

                    $novoNome = uniqid('srv_') . '.' . $ext;
                    $destino = $uploadDir . $novoNome;

                    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
                        $caminho = "uploads/servicos/$novoNome";
                        $campos[] = "imagem = ?";
                        $valores[] = $caminho;
                    } else {
                        $mensagemErro = 'Erro ao salvar a nova imagem.';
                    }
                } else {
                    $mensagemErro = 'Formato de imagem inválido.';
                }
            }

            $valores[] = $id;
            $sql = "UPDATE servicos SET " . implode(", ", $campos) . " WHERE id = ?";
            iduSQL($sql, $valores);

            $mensagemSucesso = 'Serviço atualizado.';
            $servicos = selectSQL("SELECT * FROM servicos ORDER BY id DESC");
        }
    }
}
?>

<main class="container py-5 mb-5">

  <h2 class="text-center mb-4 pt-5 pb-5">Editar Página - Serviços</h2>

  <?php if ($mensagemErro): ?>
    <div class="alert alert-danger text-center mb-4"><?= htmlspecialchars($mensagemErro) ?></div>
  <?php endif; ?>

  <?php if ($mensagemSucesso): ?>
    <div class="alert alert-success text-center mb-4"><?= htmlspecialchars($mensagemSucesso) ?></div>
  <?php endif; ?>

  <div class="card mb-5">

    <div class="card-header text-center fw-bold">Adicionar Serviço</div>

    <div class="card-body">

      <form method="POST" enctype="multipart/form-data" class="row justify-content-center">

        <input type="hidden" name="acao" value="adicionar_servico">

        <div class="col-md-12 mb-3">
          <label class="form-label fw-bold">Título</label>
          <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="col-md-12 mb-3">
          <label class="form-label fw-bold">Imagem</label>
          <input type="file" name="imagem" class="form-control" accept=".jpg,.jpeg,.png,.gif" required>
        </div>

        <div class="col-md-12 mb-3">

          <label class="form-label fw-bold d-block mb-2">Exibir na Home:</label>

          <div class="d-flex gap-4">

            <div class="form-check">
              <input class="form-check-input" type="radio" name="exibir_na_home" id="exibirSim" value="1" checked>
              <label class="form-check-label" for="exibirSim">Sim</label>
            </div>

            <div class="form-check">
              <input class="form-check-input" type="radio" name="exibir_na_home" id="exibirNao" value="0">
              <label class="form-check-label" for="exibirNao">Não</label>
            </div>

          </div>

        </div>

        <div class="col-12 text-center mt-3">
          <button type="submit" class="btn btn-preto-invert px-5 fonte_botoes">Salvar</button>
        </div>

      </form>
    </div>
  </div>

  <div class="card">

    <div class="card-header text-center fw-bold">Serviços Cadastrados</div>

    <div class="card-body p-0">

      <table class="table table-bordered text-center align-middle mb-0">

        <thead class="table-light">

          <tr>
            <th>Título</th>
            <th>Imagem</th>
            <th>Exibir na Home</th>
            <th>Ações</th>
          </tr>

        </thead>

        <tbody>

          <?php foreach ($servicos as $servico): ?>

            <tr>

              <td>
                
                <form method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="acao" value="atualizar_servico">
                  <input type="hidden" name="id" value="<?= $servico['id'] ?>">

                  <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($servico['titulo']) ?>" required>
              </td>

              <td>
                <?php if (!empty($servico['imagem']) && file_exists(__DIR__ . '/../../' . $servico['imagem'])): ?>
                  <img src="/projeto_final/<?= htmlspecialchars($servico['imagem']) ?>" class="img-preview" alt="Imagem atual">
                <?php endif; ?>

                <input type="file" name="imagem" class="form-control form-control-sm mt-2" accept=".jpg,.jpeg,.png,.gif">
              </td>

              <td>
                  <div class="d-flex justify-content-center gap-3">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="exibir_na_home" id="exibirSim<?= $servico['id'] ?>" value="1" <?= $servico['exibir_na_home'] ? 'checked' : '' ?>>
                      <label class="form-check-label" for="exibirSim<?= $servico['id'] ?>">Sim</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="exibir_na_home" id="exibirNao<?= $servico['id'] ?>" value="0" <?= !$servico['exibir_na_home'] ? 'checked' : '' ?>>
                      <label class="form-check-label" for="exibirNao<?= $servico['id'] ?>">Não</label>
                    </div>
                  </div>
              </td>

              <td>
                  <div class="d-flex flex-column align-items-center gap-2">

                    <button type="submit" class="btn btn-sm btn-preto-invert btn-tamanho-fixo w-100 mb-2 fonte_botoes">Salvar</button>
                </form>

                    <a href="<?= BASE_URL ?>gerir_servicos_imagens.php?id=<?= $servico['id'] ?>" class="btn btn-sm btn-preto-invert btn-tamanho-fixo w-100 mb-2 fonte_botoes">Imagens</a>

                    <form method="POST" onsubmit="return confirm('Apagar este serviço?')" class="w-100">
                      <input type="hidden" name="acao" value="apagar_servico">
                      <input type="hidden" name="id" value="<?= $servico['id'] ?>">
                      <button type="submit" class="btn btn-sm btn-preto-invert btn-tamanho-fixo w-100 fonte_botoes">Apagar</button>
                    </form>

                  </div>
              </td>
            </tr>
          <?php endforeach; ?>

          <?php if (empty($servicos)): ?>
            <tr>
              <td colspan="4" class="text-muted text-center">Nenhum serviço cadastrado.</td>
            </tr>
          <?php endif; ?>
        </tbody>

      </table>
    </div>
  </div>
</main>

<script>
  setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => el.style.display = 'none');
  }, 5000);
</script>
