<?php
require 'vendor/autoload.php';

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=galeriapedro01;AccountKey=INSERE_AQUI_A_TUA_ACCOUNT_KEY;EndpointSuffix=core.windows.net";
$blobClient = BlobRestProxy::createBlobService($connectionString);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["imagem"])) {
    $containerName = "imagens";
    $fileName = $_FILES["imagem"]["name"];
    $fileTmp = $_FILES["imagem"]["tmp_name"];

    $options = new CreateBlockBlobOptions();
    $options->setContentType(mime_content_type($fileTmp));

    try {
        $content = fopen($fileTmp, "r");
        $blobClient->createBlockBlob($containerName, $fileName, $content, $options);
        echo "Imagem carregada com sucesso!<br>";
    } catch (ServiceException $e) {
        echo "Erro ao carregar imagem: " . $e->getMessage();
    }
}
?>

<form method="post" enctype="multipart/form-data">
  <label>Escolher imagem:</label>
  <input type="file" name="imagem" required>
  <input type="submit" value="Enviar">
</form>
