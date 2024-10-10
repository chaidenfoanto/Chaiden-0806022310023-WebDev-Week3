<?php
require_once 'Book.php';
require_once 'EBook.php';
require_once 'PrintedBook.php';

session_start(); // Memulai sessio

// Cek apakah session 'books' sudah ada, jika belum, buat array kosong
if (!isset($_SESSION['books'])) {
    $_SESSION['books'] = [];
}

// Fungsi untuk menambahkan buku baru berdasarkan input pengguna
function addBook($type, $title, $author, $publicationYear, $additionalInfo) {
    if ($type === 'EBook') {
        $book = new EBook($title, $author, $publicationYear, (int)$additionalInfo);
    } elseif ($type === 'PrintedBook') {
        $book = new PrintedBook($title, $author, $publicationYear, (int)$additionalInfo);
    }
    
    // Simpan buku ke dalam session
    $_SESSION['books'][] = $book;
}

// Fungsi untuk menampilkan detail buku berdasarkan indeks
function getBookDetailsByIndex($index) {
    if (isset($_SESSION['books'][$index - 1])) {
        return $_SESSION['books'][$index - 1]->getDetails();
    } else {
        return "Buku tidak ditemukan.";
    }
}

// Menangani form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['bookType'])) {
        $type = $_POST['bookType'];
        $title = trim($_POST['title']);
        $author = trim($_POST['author']);
        $publicationYear = (int)$_POST['publicationYear'];
        $additionalInfo = (int)$_POST['additionalInfo'];

        // Validasi batasan input
        if (strlen($title) > 100 || strlen($author) > 100) {
            echo "Judul dan nama penulis tidak boleh lebih dari 100 karakter.";
        } elseif ($publicationYear < 1500 || $publicationYear > 2024) {
            echo "Tahun terbit harus antara 1500 dan 2024.";
        } elseif ($type === 'EBook' && ($additionalInfo < 1 || $additionalInfo > 100)) {
            echo "Ukuran file EBook harus antara 1 dan 100 MB.";
        } elseif ($type === 'PrintedBook' && $additionalInfo <= 0) {
            echo "Jumlah halaman untuk PrintedBook harus lebih dari 0.";
        } else {
            addBook($type, $title, $author, $publicationYear, $additionalInfo);
        }
    }

    if (isset($_POST['queryIndex'])) {
        $queryIndex = (int)$_POST['queryIndex'];
        $queryResult = getBookDetailsByIndex($queryIndex);
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Fungsi untuk mengubah label input sesuai dengan jenis buku yang dipilih
        function updateInputLabel() {
            const bookType = document.querySelector('input[name="bookType"]:checked').value;
            const additionalInfoLabel = document.getElementById('additionalInfoLabel');
            const additionalInfoInput = document.getElementById('additionalInfoInput');

            if (bookType === 'EBook') {
                additionalInfoLabel.textContent = 'File Size (MB):';
                additionalInfoInput.setAttribute('type', 'number');
                additionalInfoInput.setAttribute('min', '1');
                additionalInfoInput.setAttribute('max', '100');
            } else {
                additionalInfoLabel.textContent = 'Number of Pages:';
                additionalInfoInput.setAttribute('type', 'number');
                additionalInfoInput.setAttribute('min', '1');
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Library Management System</h1>
        <form method="POST">
            <label for="bookType">Jenis Buku:</label>
            <input type="radio" name="bookType" value="EBook" onchange="updateInputLabel()" checked>EBook
            <input type="radio" name="bookType" value="PrintedBook" onchange="updateInputLabel()">PrintedBook

            <label for="title">Judul Buku:</label>
            <input type="text" name="title" required maxlength="100">

            <label for="author">Nama Penulis:</label>
            <input type="text" name="author" required maxlength="100">

            <label for="publicationYear">Tahun Terbit:</label>
            <input type="number" name="publicationYear" required min="1500" max="2024">

            <label id="additionalInfoLabel" for="additionalInfoInput">File Size (MB):</label>
            <input id="additionalInfoInput" name="additionalInfo" required>

            <br> 

            <input type="submit" value="Tambah Buku">
        </form>

        <hr>

        <form method="POST">
            <label for="queryIndex">Pencarian Detail Buku (Indeks):</label>
            <input type="number" name="queryIndex" required min="1">
            <input type="submit" value="Cari Buku">
        </form>

        <h2>Daftar Buku</h2>
        <ul>
            <?php foreach ($_SESSION['books'] as $index => $book): ?>
                <li><?php echo ($index + 1) . ". " . $book->getDetails(); ?></li>
            <?php endforeach; ?>
        </ul>

        <?php
        // Menampilkan hasil pencarian jika ada
        if (isset($queryResult)) {
            echo "<h3>Hasil Pencarian:</h3>";
            echo "<p>$queryResult</p>";
        }
        ?>
    </div>
</body>
</html>
