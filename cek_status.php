<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Pendaftaran</title>
    <link rel="stylesheet" href="style.css"> <!-- Gunakan style.css yang sama -->

    <style>
    /* ================================
           STYLING HALAMAN CEK STATUS
        ================================ */
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 60px auto;
        background-color: #ffffff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        padding: 30px;
        text-align: center;
    }

    .container h2 {
        color: #006b6b;
        margin-bottom: 10px;
    }

    .container p {
        color: #555;
        font-size: 15px;
    }

    .form-group {
        margin-top: 20px;
        text-align: left;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="text"] {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid #ccc;
        font-size: 16px;
        outline: none;
        box-sizing: border-box;
    }

    button {
        background-color: #006b6b;
        color: #fff;
        border: none;
        padding: 12px 25px;
        border-radius: 10px;
        margin-top: 15px;
        cursor: pointer;
        transition: 0.3s;
    }

    button:hover {
        background-color: #004f4f;
    }

    .hasil-status {
        margin-top: 25px;
        padding: 15px;
        background-color: #f1fdfd;
        border-radius: 10px;
        text-align: left;
        line-height: 1.6;
    }
    </style>
</head>

<body>

    <!-- ================================
         CEK STATUS PENDAFTARAN
    ================================ -->
    <section class="cek-status-section">
        <div class="container">
            <h2>Cek Status Pendaftaran</h2>
            <p>Masukkan nomor pendaftaran Anda untuk melihat status terbaru.</p>

            <form id="cekStatusForm">
                <div class="form-group">
                    <label for="noPendaftaran">Nomor Pendaftaran</label>
                    <input type="text" id="noPendaftaran" name="noPendaftaran"
                        placeholder="Masukkan nomor pendaftaran (misal: REG001)" required>
                </div>

                <button type="submit">Cek Status</button>
            </form>

            <!-- Placeholder hasil -->
            <div id="hasilStatus" class="hasil-status">
                <p><em>Hasil akan muncul di sini setelah backend terhubung.</em></p>
            </div>
        </div>
    </section>

</body>

</html>