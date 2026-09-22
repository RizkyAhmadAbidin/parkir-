<?php 
// 1. Panggil file koneksi di paling atas
include 'database/koneksi.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Saya</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h1>Status Koneksi Database</h1>
        
        <?php if ($koneksi): ?>
            <p class="status-sukses">Terhubung ke database <strong><?php echo $database; ?></strong>!</p>
        <?php endif; ?>
    </div>

</body>
</html>