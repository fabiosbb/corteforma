<?php
require_once(__DIR__ . '/../../helpers/requisitos.php');

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit();
}

$mensagemErro = '';
$mensagemSucesso = '';

$id = $_GET['id'] ?? null;

// Enviar resposta via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'responder') {
    $orcamentoId = $_POST['orcamento_id'] ?? null;
    $resposta = trim($_POST['resposta'] ?? '');

    if ($orcamentoId && $resposta) {

        iduSQL("UPDATE orcamentos SET resposta = ?, status = 'respondido' WHERE id = ?", [$resposta, $orcamentoId]);

        $orcamento = selectSQLUnico("SELECT nome, email FROM orcamentos WHERE id = ?", [$orcamentoId]);

        if ($orcamento) {
            $para = $orcamento['email'];
            $assunto = "Resposta ao seu orçamento";
            $mensagemEmail = "Olá " . htmlspecialchars($orcamento['nome']) . ",\n\n"
                . "Recebemos sua mensagem e respondemos abaixo:\n\n"
                . $resposta . "\n\n"
                . "Atenciosamente,\nSua Empresa";

            $headers = "From: contato@seudominio.com\r\n"
                     . "Content-Type: text/plain; charset=utf-8\r\n";

            if (mail($para, $assunto, $mensagemEmail, $headers)) {
                $mensagemSucesso = "Resposta enviada por email com sucesso.";
            } else {
                $mensagemErro = "Erro ao enviar email.";
            }
        } else {
            $mensagemErro = "Orçamento não encontrado.";
        }
    } else {
        $mensagemErro = "Preencha a resposta para enviar.";
    }
}

if ($id) {
    $orcamento = selectSQLUnico("SELECT * FROM orcamentos WHERE id = ?", [$id]);

    if (!$orcamento) {
        echo "<p>Orçamento não encontrado.</p>";
        exit;
    }
} else {
    $orcamentos = selectSQL("SELECT * FROM orcamentos ORDER BY data_envio DESC");
}
?>

<main class="container py-5">

    <h2 class="mb-4 text-center">Gestão de Orçamentos</h2>

    <?php if ($mensagemErro): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($mensagemErro) ?></div>
    <?php endif; ?>

    <?php if ($mensagemSucesso): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mensagemSucesso) ?></div>
    <?php endif; ?>

    <?php if ($id): ?>

        <h3 class="pt-4 pb-4">Orçamento de <?= htmlspecialchars($orcamento['nome']) ?></h3>
        <p><strong>Email:</strong> <?= htmlspecialchars($orcamento['email']) ?></p>
        <p><strong>Mensagem:</strong><br><?= nl2br(htmlspecialchars($orcamento['mensagem'])) ?></p>

        <form method="POST" class="mt-4">
            <input type="hidden" name="acao" value="responder">
            <input type="hidden" name="orcamento_id" value="<?= $orcamento['id'] ?>">

            <div class="mb-3">
                <label for="resposta" class="form-label">Resposta:</label>
                <textarea name="resposta" id="resposta" class="form-control" rows="6" required><?= htmlspecialchars($orcamento['resposta'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Enviar Resposta</button>
            
        </form>

    <?php else: ?>

        <table class="table table-bordered text-center">

            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Mensagem</th>
                    <th>Resposta</th>
                    <th>Data Envio</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($orcamentos as $o): ?>

                    <tr>
                        <td><?= htmlspecialchars($o['nome']) ?></td>
                        <td><?= htmlspecialchars($o['email']) ?></td>
                        <td><?= nl2br(htmlspecialchars(substr($o['mensagem'], 0, 50))) ?>...</td>
                        <td><?= nl2br(htmlspecialchars(substr($o['resposta'] ?? '', 0, 50))) ?>...</td>
                        <td><?= $o['data_envio'] ?></td>
                        <td><?= $o['status'] ?></td>
                        <td><a href="?id=<?= $o['id'] ?>" class="btn btn-sm btn-preto-invert  m-4 fonte_botoes">Abrir</a></td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

</main>
