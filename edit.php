<?php
include "koneksi.php";

if (isset($_GET['npm'])) {
    $npm = htmlspecialchars(strip_tags($_GET['npm']));
    $query = mysqli_prepare($conn, "SELECT * FROM tbl_mahasiswa WHERE npm = ?");
    mysqli_stmt_bind_param($query, "s", $npm);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        echo "<script>
            window.onload = function() {
                Swal.fire({
                    title: 'Data tidak ditemukan',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href='index.php';
                });
            }
        </script>";
        exit;
    }
} else {
    echo "<script>
        window.onload = function() {
            Swal.fire({
                title: 'NPM tidak ditemukan',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href='index.php';
            });
        }
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Data Mahasiswa</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
    <style>
        /* Styling tetap */
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: rgb(109, 115, 121);
            margin: 0;
            padding: 0;
        }
        form {
            width: 400px;
            margin: 30px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 123, 255, 0.2);
        }
        td {
            padding: 10px;
        }
        input[type="text"],
        input[type="email"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        input[type="submit"] {
            background-color: rgb(74, 82, 92);
            color: white;
            padding: 10px 20px;
            border: none;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: rgb(11, 65, 122);
        }
        a {
            display: inline-block;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            color: rgb(79, 82, 87);
        }
        h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
    </style>
</head>

<body>

    <form action="" method="post">
        <h3>Edit Data Mahasiswa</h3>
        <table>
            <tr>
                <td>NPM:</td>
                <td><input type="text" name="npm" value="<?= htmlspecialchars($data['npm']) ?>" readonly></td>
            </tr>
            <tr>
                <td>Nama:</td>
                <td><input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required></td>
            </tr>
            <tr>
                <td>Program Studi:</td>
                <td>
                    <select name="prodi" required>
                        <option value="">--Pilih Prodi--</option>
                        <?php
                        $prodi_list = ["Pendidikan Informatika", "Teknologi Informasi", "Sistem Informasi", "Teknik Komputer", "Teknik Informatika"];
                        foreach ($prodi_list as $p) {
                            $selected = ($p == $data['prodi']) ? "selected" : "";
                            echo "<option value='" . htmlspecialchars($p) . "' $selected>$p</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>"></td>
            </tr>
            <tr>
                <td>Alamat:</td>
                <td><textarea name="alamat"><?= htmlspecialchars($data['alamat']) ?></textarea></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" name="update" value="Update Data"></td>
            </tr>
        </table>
    </form>

    <p style="text-align: center;"><a href="index.php">← Kembali ke Daftar Mahasiswa</a></p>

    <?php
    if (isset($_POST['update'])) {
        $nama = htmlspecialchars(strip_tags($_POST['nama']));
        $prodi = htmlspecialchars(strip_tags($_POST['prodi']));
        $email = htmlspecialchars(strip_tags($_POST['email']));
        $alamat = htmlspecialchars(strip_tags($_POST['alamat']));

        $errors = [];

        // Validasi server-side
        if (empty($nama)) {
            $errors[] = "Nama harus diisi";
        } elseif (strlen($nama) < 3) {
            $errors[] = "Nama terlalu pendek";
        }

        if (empty($prodi)) {
            $errors[] = "Program Studi harus dipilih";
        }

        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email tidak valid";
        }

        if (empty($errors)) {
            $stmt = $conn->prepare("UPDATE tbl_mahasiswa SET nama=?, prodi=?, email=?, alamat=? WHERE npm=?");
            $stmt->bind_param("sssss", $nama, $prodi, $email, $alamat, $npm);

            if ($stmt->execute()) {
                echo "<script>
                    Swal.fire({
                        title: 'Data berhasil diperbarui!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        title: 'Data gagal diperbarui!',
                        text: '".htmlspecialchars($conn->error)."',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>";
            }
            $stmt->close();
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Validasi Gagal',
                    html: '".implode("<br>", array_map('htmlspecialchars', $errors))."',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            </script>";
        }
    }
    ?>

</body>

</html>