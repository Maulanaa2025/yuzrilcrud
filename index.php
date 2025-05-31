<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daftar Mahasiswa - Modern</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Reset & base */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 20px;
            color: #2c3e50;
        }

        h2 {
            text-align: center;
            font-weight: 600;
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #27ae60;
            letter-spacing: 1px;
        }

        /* Button utama */
        .button-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .button {
            background-color: #27ae60;
            color: white;
            padding: 14px 32px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            box-shadow: 0 6px 12px rgb(39 174 96 / 0.3);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .button:hover {
            background-color: #219150;
            box-shadow: 0 8px 20px rgb(33 145 80 / 0.4);
        }

        /* Search */
        .search-container {
            max-width: 400px;
            margin: 0 auto 40px;
            position: relative;
        }

        .search-container input[type="text"] {
            width: 100%;
            padding: 14px 48px 14px 20px;
            border-radius: 30px;
            border: 2px solid #27ae60;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .search-container input[type="text"]:focus {
            border-color: #1e8449;
        }

        /* Search icon */
        .search-container svg {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            fill: #27ae60;
            width: 22px;
            height: 22px;
            pointer-events: none;
        }

        /* Grid container for cards */
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
            padding: 0 20px;
        }

        /* Card style */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            padding: 20px 25px;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.12);
        }

        .card .header {
            font-weight: 700;
            font-size: 1.25rem;
            color: #27ae60;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }

        .card .info-row {
            margin-bottom: 10px;
            font-size: 0.95rem;
            color: #34495e;
        }

        .card .label {
            font-weight: 600;
            color: #27ae60;
            margin-right: 6px;
        }

        /* Action buttons */
        .aksi {
            margin-top: auto;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .aksi a {
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            color: white;
            text-decoration: none;
            box-shadow: 0 4px 8px rgb(0 0 0 / 0.12);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            user-select: none;
        }

        .aksi a.edit {
            background-color: #f39c12;
            box-shadow: 0 4px 8px rgb(243 156 18 / 0.35);
        }

        .aksi a.edit:hover {
            background-color: #d68910;
            box-shadow: 0 6px 14px rgb(214 137 16 / 0.45);
        }

        .aksi a.hapus {
            background-color: #e74c3c;
            box-shadow: 0 4px 8px rgb(231 76 60 / 0.35);
        }

        .aksi a.hapus:hover {
            background-color: #c0392b;
            box-shadow: 0 6px 14px rgb(192 57 43 / 0.45);
        }

        /* Responsive tweaks */
        @media (max-width: 480px) {
            body {
                padding: 15px 10px;
            }

            h2 {
                font-size: 2rem;
            }

            .button-container {
                margin-bottom: 20px;
            }

            .cards-container {
                grid-template-columns: 1fr;
                padding: 0 10px;
            }

            .search-container {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>

    <h2>Daftar Mahasiswa</h2>

    <div class="button-container">
        <a class="button" href="tambah.php">
            <!-- plus icon (SVG) -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                stroke-linejoin="round" class="feather feather-plus" width="18" height="18" viewBox="0 0 24 24">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Data Mahasiswa
        </a>
    </div>

    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Cari NPM, Nama, atau Prodi..." autocomplete="off" />
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path
                d="M21 21l-4.35-4.35m2.6-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                fill="none"
            />
        </svg>
    </div>

    <div class="cards-container" id="mahasiswaCards">
        <?php
        include "koneksi.php";
        $query = mysqli_query($conn, "SELECT * FROM tbl_mahasiswa");
        $no = 1;
        while ($data = mysqli_fetch_array($query)) {
            // Escape output for safety
            $npm = htmlspecialchars($data['npm']);
            $nama = htmlspecialchars($data['nama']);
            $prodi = htmlspecialchars($data['prodi']);
            $email = htmlspecialchars($data['email']);
            $alamat = htmlspecialchars($data['alamat']);

            echo "<div class='card'>
                <div class='header'>{$nama}</div>
                <div class='info-row'><span class='label'>NPM:</span> {$npm}</div>
                <div class='info-row'><span class='label'>Program Studi:</span> {$prodi}</div>
                <div class='info-row'><span class='label'>Email:</span> {$email}</div>
                <div class='info-row'><span class='label'>Alamat:</span> {$alamat}</div>
                <div class='aksi'>
                    <a class='edit' href='edit.php?npm={$npm}'>Edit</a>
                    <a class='hapus' href='#' onclick=\"konfirmasiHapus('{$npm}')\">Hapus</a>
                </div>
            </div>";
            $no++;
        }
        ?>
    </div>

    <script>
        function konfirmasiHapus(npm) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data mahasiswa dengan NPM " + npm + " akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "hapus.php?npm=" + npm;
                }
            });
        }

        // Search filter for cards
        document.getElementById('searchInput').addEventListener('input', function () {
            const filter = this.value.toLowerCase();
            const cards = document.querySelectorAll('#mahasiswaCards .card');
            cards.forEach(card => {
                const text = card.innerText.toLowerCase();
                card.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    </script>

</body>

</html>
