<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["imagem"])) {
    $fileName = $_FILES["imagem"]["name"];
    $fileTmp = $_FILES["imagem"]["tmp_name"];

    $blobUrl = "https://galeriapedro01.blob.core.windows.net/imagens/" . $fileName;
    $sasToken = "?sp=rcwl&st=2025-07-31T17:58:50Z&se=2025-08-01T02:13:50Z&spr=https&sv=2024-11-04&sr=c&sig=Td3vtwuHt7uOjs16ZaRqQO3zZoPL1%2FJYIb5pY6%2Bf1AA%3D";

    $destination = $blobUrl . $sasToken;
    $fileContents = file_get_contents($fileTmp);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $destination);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "x-ms-blob-type: BlockBlob",
        "Content-Length: " . strlen($fileContents)
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContents);

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status == 201) {
        echo "Imagem carregada com sucesso!<br>";
    } else {
        echo "Erro ao carregar imagem (HTTP $status)<br>";
    }
}
?>

<form method="post" enctype="multipart/form-data">
  <label>Escolher imagem:</label>
  <input type="file" name="imagem" required>
  <input type="submit" value="Enviar">
</form>
