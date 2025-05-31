<?php
if (isset($_GET['npm'])) {
    include "koneksi.php";

    $npm = htmlspecialchars(strip_tags($_GET['npm']));

    // Hapus data mahasiswa berdasarkan NPM
    $stmt = $conn->prepare("DELETE FROM tbl_mahasiswa WHERE npm = ?");
    $stmt->bind_param("s", $npm);

    if ($stmt->execute()) {
        // Jika berhasil
        $message = [
            'title' => 'Data berhasil dihapus!',
            'icon' => 'success',
            'redirect' => 'index.php'
        ];
    } else {
        // Jika gagal
        $message = [
            'title' => 'Data gagal dihapus!',
            'icon' => 'error',
            'redirect' => 'index.php'
        ];
    }

    $stmt->close();
} else {
    // Jika NPM tidak ditemukan
    $message = [
        'title' => 'NPM tidak ditemukan!',
        'icon' => 'warning',
        'redirect' => 'index.php'
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Data</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
    Swal.fire({
        title: '<?= $message['title'] ?>',
        icon: '<?= $message['icon'] ?>',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = '<?= $message['redirect'] ?>';
    });
</script>

</body>
</html>