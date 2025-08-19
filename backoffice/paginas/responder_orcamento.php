<?php
require_once("../helpers/requisitos.php");

$id = $_GET['id'] ?? null;
$orcamento = selectSQLUnico("SELECT * FROM orcamentos WHERE id = :id", ['id' => $id]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    iduSQL("UPDATE orcamentos SET status = :status, resposta = :resposta WHERE id = :id", [
        'status' => $_POST['status'],
        'resposta' => $_POST['resposta'] ?? null,
        'id' => $id
    ]);

    if (!empty($_POST['resposta'])) {
        $resposta = $_POST['resposta'];
        $emailCliente = $orcamento['email'];
        $assunto = "Resposta ao seu orçamento";
        $corpo = "Olá {$orcamento['nome']},\n\n" . $resposta;
        // Envia email - só vai funcionar se o servidor tiver SMTP configurado
        mail($emailCliente, $assunto, $corpo);
    }

    header("Location: backoffice_home.php");
    exit;
}
?>

<?php include("../componentes/header.php"); ?>

<main class="container pt-5 pb-5">

  <h1 class="tamanho_h fonte_oswald mb-4">📋 Gestão do Orçamento</h1>

  <form method="POST">

    <p><span class="fw-bold">Nome:</span> <?= htmlspecialchars($orcamento['nome']) ?></p>
    <p><span class="fw-bold">Email:</span> <?= htmlspecialchars($orcamento['email']) ?></p>
    <p><span class="fw-bold">Mensagem:</span><br><?= nl2br(htmlspecialchars($orcamento['mensagem'])) ?></p>

    <div class="mb-3 mt-4">
      <label class="form-label fw-bold">🔄 Atualizar status:</label>
      <select name="status" class="form-select">
        <option value="recebido" <?= $orcamento['status'] === 'recebido' ? 'selected' : '' ?>>Recebido</option>
        <option value="respondido" <?= $orcamento['status'] === 'respondido' ? 'selected' : '' ?>>Respondido</option>
        <option value="concluido" <?= $orcamento['status'] === 'concluido' ? 'selected' : '' ?>>Concluído</option>
      </select>
    </div>

    <div class="mb-4">
      <label class="form-label fw-bold">✉️ Resposta ao cliente (opcional):</label>
      <textarea name="resposta" rows="5" class="form-control" placeholder="Escreve aqui a tua resposta..."><?= htmlspecialchars($orcamento['resposta'] ?? '') ?></textarea>
    </div>

    <div class="d-flex gap-3">
      <button type="submit" class="btn btn-success">💾 Gravar Gestão</button>
      <a href="backoffice_home.php" class="btn btn-secondary">↩️ Voltar ao painel</a>
    </div>

  </form>
</main>

<?php include("../componentes/footer.php"); ?>

