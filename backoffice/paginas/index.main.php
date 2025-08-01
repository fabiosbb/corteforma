<?php

require_once("../helpers/requisitos.php");

$id = $_GET['id'] ?? null;
$orcamento = selectSQLUnico("SELECT * FROM orcamentos WHERE id = :id", ['id' => $id]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  iduSQL("UPDATE orcamentos SET status = :status WHERE id = :id", ['status' => $_POST['status'], 'id' => $id]);
  
  // Enviar resposta ao cliente por email
  if (!empty($_POST['resposta'])) {

    $resposta = $_POST['resposta'];
    $emailCliente = $orcamento['email'];
    $assunto = "Resposta ao seu orçamento";
    $corpo = "Olá {$orcamento['nome']},\n\n" . $resposta;
    mail($emailCliente, $assunto, $corpo);
      
  }

  header("Location: backoffice_home.php");
  exit;
}
?>

<?php include("../componentes/header.php"); ?>

<main class="container pt-5 pb-5">

  <h1 class="tamanho_h fonte_oswald">📋 Gestão do Orçamento</h1>

  <form method="POST">
    
    <p><strong>Nome:</strong> <?= htmlspecialchars($orcamento['nome']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($orcamento['email']) ?></p>
    <p><strong>Mensagem:</strong><br><?= nl2br(htmlspecialchars($orcamento['mensagem'])) ?></p>

    <div class="mb-3">
      <label class="form-label">Atualizar status:</label>
      <select name="status" class="form-select">
        <option value="recebido" <?= $orcamento['status'] === 'recebido' ? 'selected' : '' ?>>Recebido</option>
        <option value="respondido" <?= $orcamento['status'] === 'respondido' ? 'selected' : '' ?>>Respondido</option>
        <option value="concluido" <?= $orcamento['status'] === 'concluido' ? 'selected' : '' ?>>Concluído</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Resposta ao cliente (opcional):</label>
      <textarea name="resposta" rows="5" class="form-control" placeholder="Escreve aqui a tua resposta..."></textarea>
    </div>

    <button type="submit" class="btn btn-success">Gravar Gestão</button>
    <a href="backoffice_home.php" class="btn btn-secondary">Voltar ao painel</a>
  </form>
</main>

<?php include("../componentes/footer.php"); ?>
