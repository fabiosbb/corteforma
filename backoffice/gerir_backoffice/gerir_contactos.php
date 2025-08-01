<?php
require_once("../helpers/requisitos.php");

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit();
}

$mensagemErro = '';
$mensagemSucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'atualizar_contato') {

    $id = $_POST['id'] ?? null;
    $telefone = $_POST['telefone'] ?? '';
    $email = $_POST['email'] ?? '';
    $linkedin = $_POST['linkedin'] ?? '';
    $morada = $_POST['morada'] ?? '';
    $exibir_na_home = isset($_POST['exibir_na_home']) ? 1 : 0;

    if ($id) {

        $contatoAtual = selectSQLUnico("SELECT imagem FROM contactos WHERE id = ?", [$id]);
        $imagemAntiga = is_array($contatoAtual) ? ($contatoAtual['imagem'] ?? '') : '';

        if (!empty($_FILES['nova_imagem']['name'])) {

            $arquivoTmp = $_FILES['nova_imagem']['tmp_name'];
            $nomeOriginal = basename($_FILES['nova_imagem']['name']);

            $nomeSanitizado = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $nomeOriginal);

            $pastaImagens = '../imagens/contactos/';
            if (!is_dir($pastaImagens)) {
                mkdir($pastaImagens, 0755, true);
            }
            $caminhoNovo = $pastaImagens . $nomeSanitizado;

            if (move_uploaded_file($arquivoTmp, $caminhoNovo)) {
                if ($imagemAntiga && file_exists('../' . $imagemAntiga) && $imagemAntiga !== substr($caminhoNovo, 3)) {
                    unlink('../' . $imagemAntiga);
                }
                $imagemParaSalvar = substr($caminhoNovo, 3);
            } else {
                $mensagemErro = "Erro ao enviar a nova imagem.";
                $imagemParaSalvar = $imagemAntiga;
            }
        } else {
            $imagemParaSalvar = $imagemAntiga;
        }

        if (!$mensagemErro) {
            iduSQL("UPDATE contactos SET telefone = ?, email = ?, linkedin = ?, morada = ?, exibir_na_home = ?, imagem = ? WHERE id = ?",
                [$telefone, $email, $linkedin, $morada, $exibir_na_home, $imagemParaSalvar, $id]);
            $mensagemSucesso = "Contato atualizado com sucesso!";
        }
    } else {
        $mensagemErro = "ID do contato inválido.";
    }
}

$contactos = selectSQL("SELECT * FROM contactos ORDER BY id ASC");

?>

<div class="container py-5">

  <h2 class="pt-5 pb-5 text-center">Editar - Página Contactos</h2>

  <?php if ($mensagemSucesso): ?>

    <div class="alert alert-success mensagem-temporaria text-center mx-auto mb-4" style="max-width: 500px;">
      ✅ <?= htmlspecialchars($mensagemSucesso) ?>
    </div>

  <?php endif; ?>

  <?php foreach ($contactos as $contato): ?>

    <form method="POST" enctype="multipart/form-data" class="form-edicao border rounded p-4 mb-5 bg-light">

      <input type="hidden" name="acao" value="atualizar_contato" />
      <input type="hidden" name="id" value="<?= htmlspecialchars($contato['id'] ?? '') ?>" />

      <div class="row mb-4 align-items-center">

        <div class="col-md-4 text-center">

          <?php if (!empty($contato['imagem']) && file_exists(__DIR__ . '/../../' . $contato['imagem'])): ?>

            <img src="/projeto_final/<?= htmlspecialchars($contato['imagem']) ?>" alt="Imagem" class="img-fluid shadow rounded mb-3" style="max-height: 250px;"/>

          <?php else: ?>

            <span class="text-muted">Sem imagem</span>

          <?php endif; ?>

        </div>

        <div class="col-md-8">

          <label class="form-label fw-bold mb-2">Atualizar Imagem:</label>
          <input type="file" name="nova_imagem" class="form-control mb-3" />

        </div>

      </div>

      <div class="row g-4 mb-4">

        <div class="col-md-6">

          <label class="form-label fw-bold mb-2">Telefone:</label>
          <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($contato['telefone'] ?? '') ?>" required />

        </div>

        <div class="col-md-6">

          <label class="form-label fw-bold mb-2">Email:</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($contato['email'] ?? '') ?>" required />

        </div>

        <div class="col-md-6">

          <label class="form-label fw-bold mb-2">LinkedIn:</label>
          <input type="text" name="linkedin" class="form-control" value="<?= htmlspecialchars($contato['linkedin'] ?? '') ?>" />

        </div>

        <div class="col-md-6">

          <label class="form-label fw-bold mb-2">Morada:</label>
          <input type="text" name="morada" class="form-control" value="<?= htmlspecialchars($contato['morada'] ?? '') ?>" />

        </div>

      
        </div>

        <div class="col-md-6 text-end pt-4">

          <button type="submit" class="btn btn-outline-dark px-4 botao-hover fonte_botoes">Salvar</button>

        </div>

      </div>
      
    </form>

  <?php endforeach; ?>
</div>
