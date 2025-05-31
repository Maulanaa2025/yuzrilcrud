<?php
// --- Koneksi Database ---
$host = "localhost";    // sesuaikan
$user = "root";         // sesuaikan
$pass = "";             // sesuaikan
$db   = "db_praktikumweb"; // sesuaikan

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses simpan data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $npm = trim($_POST['npm']);
    $nama = trim($_POST['nama']);
    $prodi = trim($_POST['prodi']);
    $email = trim($_POST['email']);
    $alamat = trim($_POST['alamat']);

    // Validasi minimal server-side (boleh ditambah lagi)
    if ($npm === '' || $nama === '' || $prodi === '') {
        $error = "NPM, Nama, dan Program Studi harus diisi!";
    } else {
        // Cek apakah NPM sudah ada (untuk menghindari duplikasi)
        $check_stmt = $conn->prepare("SELECT npm FROM tbl_mahasiswa WHERE npm = ?");
        $check_stmt->bind_param("s", $npm);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "NPM sudah terdaftar! Gunakan NPM yang berbeda.";
        } else {
            // Insert data menggunakan prepared statement untuk keamanan
            // Menyesuaikan dengan struktur tabel yang ada: idMhs, npm, nama, prodi, email, alamat
            $stmt = $conn->prepare("INSERT INTO tbl_mahasiswa (npm, nama, prodi, email, alamat) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $npm, $nama, $prodi, $email, $alamat);

            if ($stmt->execute()) {
                // Redirect ke index.php setelah sukses tambah data
                header("Location: index.php");
                exit;
            } else {
                $error = "Gagal menambahkan data: " . $conn->error;
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Tambah Data Mahasiswa</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #74ebd5, #9face6);
            margin: 0; padding: 0;
        }
        h3 { text-align: center; color: #fff; margin-top: 30px; font-size: 28px; }
        p { text-align: center; color: #f0f0f0; margin-bottom: 10px; }
        form {
            width: 420px;
            margin: 30px auto;
            padding: 25px 30px;
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        table { width: 100%; }
        td { padding: 10px 0; }
        label { font-weight: 600; color: #333; }
        input[type="text"], input[type="email"], textarea, select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f9f9f9;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #74b9ff;
            outline: none;
        }
        .button-group {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 20px;
        }
        input[type="submit"], .cancel-btn {
            padding: 12px 24px;
            font-size: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: transform 0.2s, background-color 0.3s;
        }
        input[type="submit"] {
            background-color: #00b894;
            color: white;
        }
        input[type="submit"]:hover {
            background-color: #019875;
            transform: scale(1.05);
        }
        .cancel-btn {
            background-color: #d63031;
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        .cancel-btn:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }
        a {
            background-color: transparent;
            color: #ffffff;
            text-decoration: underline;
            display: block;
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }
        .error {
            color: #d63031;
            font-size: 12px;
            margin-top: 5px;
            text-align: center;
        }
        .error-border {
            border-color: #d63031 !important;
        }
        .success {
            color: #00b894;
            font-size: 14px;
            margin: 10px 0;
            text-align: center;
            background-color: #d4edda;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin: 10px auto;
            width: 420px;
            text-align: center;
        }
    </style>
</head>
<body>

    <h3>📋 Tambah Data Mahasiswa</h3>
    <p>Isi formulir dengan lengkap dan benar</p>

    <?php if (!empty($error)) : ?>
        <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="" method="post" id="formMahasiswa" onsubmit="return validateForm()">
        <table>
            <tr>
                <td><label for="npm">NPM:</label></td>
                <td>
                    <input type="text" name="npm" id="npm" maxlength="12" required
                        value="<?= isset($_POST['npm']) ? htmlspecialchars($_POST['npm']) : '' ?>"
                        placeholder="Contoh: 23030502">
                    <div id="npmError" class="error"></div>
                </td>
            </tr>
            <tr>
                <td><label for="nama">Nama:</label></td>
                <td>
                    <input type="text" name="nama" id="nama" required
                        value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>"
                        placeholder="Nama lengkap mahasiswa">
                    <div id="namaError" class="error"></div>
                </td>
            </tr>
            <tr>
                <td><label for="prodi">Program Studi:</label></td>
                <td>
                    <select name="prodi" id="prodi" required>
                        <option value="">-- Pilih Prodi --</option>
                        <?php
                        $prodiOptions = [
                            'Pendidikan Informatika', 
                            'Teknologi Informasi', 
                            'Sistem Informasi', 
                            'Teknik Komputer', 
                            'Teknik Informatika'
                        ];
                        foreach ($prodiOptions as $option) {
                            $selected = (isset($_POST['prodi']) && $_POST['prodi'] === $option) ? 'selected' : '';
                            echo "<option value=\"$option\" $selected>$option</option>";
                        }
                        ?>
                    </select>
                    <div id="prodiError" class="error"></div>
                </td>
            </tr>
            <tr>
                <td><label for="email">Email:</label></td>
                <td>
                    <input type="email" name="email" id="email"
                        value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                        placeholder="contoh@gmail.com">
                    <div id="emailError" class="error"></div>
                </td>
            </tr>
            <tr>
                <td><label for="alamat">Alamat:</label></td>
                <td>
                    <textarea name="alamat" id="alamat" rows="3" 
                        placeholder="Alamat lengkap mahasiswa"><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : '' ?></textarea>
                    <div id="alamatError" class="error"></div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="button-group">
                        <input type="submit" name="submit" value="💾 Simpan Data">
                        <a href="index.php" class="cancel-btn">❌ Batal</a>
                    </div>
                </td>
            </tr>
        </table>
    </form>

    <a href="index.php">← Kembali ke Daftar Mahasiswa</a>

    <script>
        function validateForm() {
            resetErrors();
            const npm = document.getElementById('npm').value.trim();
            const nama = document.getElementById('nama').value.trim();
            const prodi = document.getElementById('prodi').value;
            const email = document.getElementById('email').value.trim();
            let isValid = true;

            // Validasi NPM
            if (npm === '') {
                showError('npm', 'NPM harus diisi');
                isValid = false;
            } else if (!/^\d+$/.test(npm)) {
                showError('npm', 'NPM harus berupa angka');
                isValid = false;
            } else if (npm.length < 8 || npm.length > 12) {
                showError('npm', 'NPM harus 8-12 digit');
                isValid = false;
            }

            // Validasi Nama
            if (nama === '') {
                showError('nama', 'Nama harus diisi');
                isValid = false;
            } else if (nama.length < 3) {
                showError('nama', 'Nama minimal 3 karakter');
                isValid = false;
            } else if (!/^[a-zA-Z\s]+$/.test(nama)) {
                showError('nama', 'Nama hanya boleh berisi huruf dan spasi');
                isValid = false;
            }

            // Validasi Program Studi
            if (prodi === '') {
                showError('prodi', 'Program studi harus dipilih');
                isValid = false;
            }

            // Validasi Email (opsional, tapi jika diisi harus valid)
            if (email !== '' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError('email', 'Format email tidak valid');
                isValid = false;
            }

            return isValid;
        }

        function showError(fieldId, message) {
            document.getElementById(fieldId + 'Error').textContent = message;
            document.getElementById(fieldId).classList.add('error-border');
        }

        function resetErrors() {
            document.querySelectorAll('.error').forEach(e => e.textContent = '');
            document.querySelectorAll('input, select, textarea').forEach(e => e.classList.remove('error-border'));
        }

        // Reset error saat user mulai mengetik
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('error-border');
                    const errorElement = document.getElementById(this.id + 'Error');
                    if (errorElement) {
                        errorElement.textContent = '';
                    }
                });
            });
        });
    </script>
</body>
</html>