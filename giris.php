<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign İn</title>
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

        .giris-formu {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 350px;
            max-width: 90%;
        }

        .giris-formu h2 {
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

        .form-group input[type="email"],
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

        .giris-formu button {
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

        .giris-formu button:hover {
            background-color: #0056b3;
        }

        .giris-formu p.uyari {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="giris-formu">
        <h2>Sign In</h2>
        <form action="giris_islemi.php" method="POST">
            <div class="form-group">
                <label for="eposta">E-mail:</label>
                <input type="email" id="eposta" name="eposta" required>
            </div>
            <div class="form-group">
                <label for="sifre">Password:</label>
                <input type="password" id="sifre" name="sifre" required>
            </div>
            <button type="submit">Sign in</button>
            <?php if (isset($_GET['hata'])): ?>
                <p class="uyari"><?php echo htmlspecialchars($_GET['hata']); ?></p>
            <?php endif; ?>
        </form>
        <p style="margin-top: 20px; text-align: center;">Don't have an account yet?" <a href="kayit.php">Sign up</a></p>
        
    </div>
</body>
</html>