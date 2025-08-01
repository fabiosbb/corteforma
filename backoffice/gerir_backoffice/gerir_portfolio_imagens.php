<?php

require_once(__DIR__ . '/../../helpers/requisitos.php');

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit();
}

$mensagemErro = '';
$mensagemSucesso = '';
$portfolioId = $_GET['id'] ?? null;

// Buscar imagens relacionadas ao portfolio
$imagens = selectSQL("SELECT * FROM portfolio_imagens WHERE portfolio_id = ? ORDER BY id DESC", [$portfolioId]);

// Inserir nova imagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'inserir') {

    $legenda = trim($_POST['legenda'] ?? '');

    if (!empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {

        $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $permitidas)) {

            $uploadDir = __DIR__ . '/../../uploads/portfolios/';

            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $nomeArquivo = uniqid('img_') . '.' . $ext;
            $destino = $uploadDir . $nomeArquivo;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {

                $caminhoRelativo = "uploads/portfolios/" . $nomeArquivo;

                iduSQL("INSERT INTO portfolio_imagens (portfolio_id, imagem, legenda) VALUES (?, ?, ?)", [$portfolioId, $caminhoRelativo, $legenda]);

                $mensagemSucesso = "Imagem adicionada com sucesso!";
                $imagens = selectSQL("SELECT * FROM portfolio_imagens WHERE portfolio_id = ? ORDER BY id DESC", [$portfolioId]);

            } else {

                $mensagemErro = "Erro ao mover o arquivo.";
            }

        } else {

            $mensagemErro = "Formato de imagem inválido.";
        }

    } else {

        $mensagemErro = "Selecione uma imagem.";
    }
}

// Editar imagem e legenda
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'editar') {

    $imagemId = $_POST['imagem_id'] ?? null;
    $novaLegenda = trim($_POST['legenda'] ?? '');

    if ($imagemId && $novaLegenda) {

        iduSQL("UPDATE portfolio_imagens SET legenda = ? WHERE id = ? AND portfolio_id = ?", [$novaLegenda, $imagemId, $portfolioId]);

        if (!empty($_FILES['imagem']['name']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {

            $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($ext, $permitidas)) {

                $imgAntiga = selectSQLUnico("SELECT imagem FROM portfolio_imagens WHERE id = ? AND portfolio_id = ?", [$imagemId, $portfolioId]);

                if ($imgAntiga && file_exists(__DIR__ . '/../../' . $imgAntiga['imagem'])) {
                    unlink(__DIR__ . '/../../' . $imgAntiga['imagem']);
                }

                $uploadDir = __DIR__ . '/../../uploads/portfolios/';
                $nomeNovo = uniqid('img_') . '.' . $ext;
                $destino = $uploadDir . $nomeNovo;

                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {

                    $caminhoRelativo = "uploads/portfolios/" . $nomeNovo;
                    iduSQL("UPDATE portfolio_imagens SET imagem = ? WHERE id = ? AND portfolio_id = ?", [$caminhoRelativo, $imagemId, $portfolioId]);
                    $mensagemSucesso = "Imagem atualizada com sucesso!";

                } else {

                    $mensagemErro = "Erro ao atualizar imagem.";
                }

            } else {

                $mensagemErro = "Formato de imagem inválido.";
            }
        } else {

            $mensagemSucesso = $mensagemSucesso ?: "Legenda atualizada!";
        }

        $imagens = selectSQL("SELECT * FROM portfolio_imagens WHERE portfolio_id = ? ORDER BY id DESC", [$portfolioId]);
    }
}

// Apagar imagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'apagar') {

    $imagemId = $_POST['imagem_id'] ?? null;

    if ($imagemId) {

        $img = selectSQLUnico("SELECT imagem FROM portfolio_imagens WHERE id = ? AND portfolio_id = ?", [$imagemId, $portfolioId]);

        if ($img && file_exists(__DIR__ . '/../../' . $img['imagem'])) {
            unlink(__DIR__ . '/../../' . $img['imagem']);
        }

        iduSQL("DELETE FROM portfolio_imagens WHERE id = ? AND portfolio_id = ?", [$imagemId, $portfolioId]);

        $mensagemSucesso = "Imagem removida.";
        $imagens = selectSQL("SELECT * FROM portfolio_imagens WHERE portfolio_id = ? ORDER BY id DESC", [$portfolioId]);
    }
}


$portfolioTitulo = '';
if ($portfolioId) {
    $portfolioDados = selectSQLUnico("SELECT titulo FROM portfolio WHERE id = ?", [$portfolioId]);
    if ($portfolioDados) {
        $portfolioTitulo = $portfolioDados['titulo'];
    }
}

?>

<main class="container py-5">

   <h2 class="text-center mb-5 pt-4">Editar -  <?= htmlspecialchars($portfolioTitulo ?: "ID #$portfolioId") ?></h2>


    <?php if ($mensagemErro): ?>
        <div class="alert alert-danger text-center mb-4"><?= htmlspecialchars($mensagemErro) ?></div>
    <?php endif; ?>

    <?php if ($mensagemSucesso): ?>
        <div class="alert alert-success text-center mb-4"><?= htmlspecialchars($mensagemSucesso) ?></div>
    <?php endif; ?>

    <div class="card mb-5 shadow-sm">

        <div class="card-header fw-bold text-center">Adicionar Nova Imagem</div>

        <div class="card-body pt-4">

            <form method="POST" enctype="multipart/form-data" class="row g-3">

                <input type="hidden" name="acao" value="inserir">

                <div class="col-md-6">
                    <label class="form-label fonte_botoes">Legenda:</label>
                    <input type="text" name="legenda" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fonte_botoes">Imagem:</label>
                    <input type="file" name="imagem" class="form-control" accept=".jpg,.jpeg,.png,.gif" required>
                </div>

                <div class="col-12 text-center mt-3 pt-3 pb-3">
                    <button type="submit" class="btn btn-preto-invert px-4 fonte_botoes">Salvar</button>
                </div>

            </form>

        </div>

    </div>

    <div class="row g-4">

        <?php foreach ($imagens as $img): ?>

            <div class="col-md-4 col-sm-6 pb-5">

                <div class="card h-100 shadow-sm">

                    <img src="/projeto_final/<?= htmlspecialchars($img['imagem']) ?>" class="img-admin-padrao" alt="Imagem">

                    <div class="card-body">

                        <form method="POST" enctype="multipart/form-data" class="mb-3">

                            <input type="hidden" name="acao" value="editar">
                            <input type="hidden" name="imagem_id" value="<?= $img['id'] ?>">

                            <label class="form-label fonte_botoes">Legenda:</label>
                            <input type="text" name="legenda" class="form-control mb-2" value="<?= htmlspecialchars($img['legenda']) ?>" required>

                            <label class="form-label fonte_botoes">Imagem:</label>
                            <input type="file" name="imagem" class="form-control mb-3" accept=".jpg,.jpeg,.png,.gif">

                            <div class="d-flex justify-content-center pt-3 gap-4">
                                <button type="submit" name="acao" value="editar" class="btn btn-sm btn-preto-invert btn-tamanho-fixo fonte_botoes">Atualizar</button>
                                <button type="submit" name="acao" value="apagar" class="btn btn-sm btn-preto-invert btn-tamanho-fixo fonte_botoes" onclick="return confirm('Remover esta imagem?')">Apagar</button>
                            </div>

                        </form>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</main>
