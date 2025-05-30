<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RandevuNET Kayıt Ol</title>
    <style>
   body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .kayit-formu {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
        }

        .kayit-formu h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .firma-secenegi-alani {
            margin-bottom: 20px;
            padding: 0; 
            border: none; 
            background-color: transparent; 
        }

        .firma-secenegi-alani label {
            display: flex;
            align-items: center;
            color: #555;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 0; 
        }

        .firma-secenegi-alani input[type="checkbox"] {
            margin-right: 10px;
            width: auto;
        }

        .firma-secenekleri {
            margin-top: 10px;
            padding-left: 25px;
            display: none;
        }

        .firma-secenekleri.acik {
            display: block;
        }

        .firma-secenekleri label {
            display: block;
            margin-top: 10px;
            font-weight: normal;
        }

        .firma-secenekleri input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 5px;
        }

        .kayit-formu button {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .kayit-formu button:hover {
            background-color: #0056b3;
        }

        .kayit-formu p.uyari {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
<body>
    <div class="kayit-formu">
        <h2>Sign Up</h2>
        <form action="kayit_islemi.php" method="POST">
            <div class="form-group">
                <label for="ad">Name:</label>
                <input type="text" id="ad" name="ad" required>
            </div>
            <div class="form-group">
                <label for="soyad">Surname:</label>
                <input type="text" id="soyad" name="soyad" required>
            </div>
            <div class="form-group">
                <label for="eposta">E-mail:</label>
                <input type="email" id="eposta" name="eposta" required>
            </div>
            <div class="form-group">
                <label for="telefon">Telephone Number:</label>
                <input type="tel" id="telefon" name="telefon" required>
            </div>
            <div class="form-group">
                <label for="sifre">Password:</label>
                <input type="password" id="sifre" name="sifre" required>
            </div>
            <div class="firma-secenegi-alani">
                <label for="firma_sahibi">
                    <input type="checkbox" id="firma_sahibi" name="firma_sahibi"> Firma sahibi misiniz?
                </label>
                <div id="firma_secenekleri" class="firma-secenekleri">
                    <label for="firma_adi">Company name:</label>
                    <input type="text" id="firma_adi" name="firma_adi">
                    <label for="firma_meslek">Business Sector:</label>
                    <input type="text" id="firma_meslek" name="firma_meslek">
                </div>
            </div>
            <button type="submit">Sign Up</button>
            <?php if (isset($_GET['hata'])): ?>
                <p class="uyari"><?php echo htmlspecialchars($_GET['hata']); ?></p>
            <?php endif; ?>
        </form>
        <p style="margin-top: 20px; text-align: center;">Do you already have an account?" <a href="giris.php">Sign in</a></p>
    </div>

    <script>
        const firmaSahibiCheckbox = document.getElementById('firma_sahibi');
        const firmaSecenekleriDiv = document.getElementById('firma_secenekleri');

        firmaSahibiCheckbox.addEventListener('change', function() {
            if (this.checked) {
                firmaSecenekleriDiv.classList.add('acik');
                document.getElementById('firma_adi').setAttribute('required', '');
                document.getElementById('firma_meslek').setAttribute('required', '');
            } else {
                firmaSecenekleriDiv.classList.remove('acik');
                document.getElementById('firma_adi').removeAttribute('required');
                document.getElementById('firma_meslek').removeAttribute('required');
            }
        });
    </script>
</body>
</html>