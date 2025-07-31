<?php
// Configurações
$containerUrl = "https://galeriapedro01.blob.core.windows.net/imagens?";
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$porPagina = 16;

// Função simples para obter lista de blobs (versão pública sem autenticação)
function obterImagens($containerUrl) {
    $xml = simplexml_load_file($containerUrl . "?restype=container&comp=list");
    $imagens = [];
    foreach ($xml->Blobs->Blob as $blob) {
        $imagens[] = $containerUrl . "/" . (string)$blob->Name;
    }
    return $imagens;
}

// Obter e paginar imagens
$imagens = obterImagens($containerUrl);
$total = count($imagens);
$paginas = ceil($total / $porPagina);
$inicio = ($pagina - 1) * $porPagina;
$imagensPagina = array_slice($imagens, $inicio, $porPagina);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Galeria de Imagens</title>
    <style>
        .galeria {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        .galeria img {
            width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: cover;
        }
        .paginacao {
            margin-top: 20px;
        }
        .paginacao a {
            margin: 0 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>Galeria de Imagens</h1>
    <div class="galeria">
        <?php foreach ($imagensPagina as $img): ?>
            <img src="<?= htmlspecialchars($img) ?>" alt="Imagem">
        <?php endforeach; ?>
    </div>

    <div class="paginacao">
        <?php if ($pagina > 1): ?>
            <a href="?pagina=<?= $pagina - 1 ?>">← Anterior</a>
        <?php endif; ?>
        <?php if ($pagina < $paginas): ?>
            <a href="?pagina=<?= $pagina + 1 ?>">Próxima →</a>
        <?php endif; ?>
    </div>

    <p><a href="upload.php">Ir para upload</a></p>
</body>
</html>
