<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

if (isset($_SESSION['user_id'])) {
    header("Location: /gestion_parc_auto/dashboard/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de flotte de véhicules - Connexion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #0c3b2e 0%, #1b5e42 50%, #0c3b2e 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #333;
        }
        
        .login-container {
            display: flex;
            max-width: 1000px;
            width: 100%;
            background-color: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .company-info {
            flex: 1;
            background: linear-gradient(to bottom right, #1b5e42, #2e8b57);
            color: white;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .company-logo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            flex-direction: column;
            text-align: center;
        }
        
        .logo-square {
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            padding: 15px;
        }
        
        .logo-square img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            max-width: 120px;
            max-height: 120px;
        }
        
        .logo-placeholder-text {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 5px;
        }
        
        .logo-text {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .logo-subtitle {
            font-size: 18px;
            font-weight: 300;
            margin-top: 5px;
            opacity: 0.9;
        }
        
        .company-tagline {
            font-size: 18px;
            margin-bottom: 25px;
            line-height: 1.5;
            opacity: 0.9;
            text-align: center;
        }
        
        .company-features {
            list-style: none;
            margin-top: 30px;
        }
        
        .company-features li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .company-features i {
            color: #a5d6a7;
            margin-right: 12px;
            font-size: 18px;
            background: rgba(255, 255, 255, 0.1);
            padding: 8px;
            border-radius: 50%;
        }
        
        .login-form-section {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-header {
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #1b5e42;
            font-size: 32px;
            margin-bottom: 8px;
        }
        
        .login-header p {
            color: #666;
            font-size: 16px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #1b5e42;
            font-weight: 600;
            font-size: 15px;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #1b5e42;
            font-size: 18px;
        }
        
        .input-with-icon input {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .input-with-icon input:focus {
            outline: none;
            border-color: #2e8b57;
            box-shadow: 0 0 0 3px rgba(46, 139, 87, 0.1);
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 18px;
        }
        
        .password-toggle:hover {
            color: #2e8b57;
        }
        
        .login-button {
            background: linear-gradient(to right, #1b5e42, #2e8b57);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            margin-top: 20px;
            letter-spacing: 0.5px;
        }
        
        .login-button:hover {
            background: linear-gradient(to right, #175539, #267d4d);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 139, 87, 0.3);
        }
        
        .login-button:active {
            transform: translateY(0);
        }
        
        .login-footer {
            margin-top: 25px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        
        .login-footer a {
            color: #2e8b57;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        
        .remember-me input {
            margin-right: 8px;
        }
        
        .remember-me label {
            color: #666;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 450px;
            }
            .company-info {
                padding: 30px;
            }
            .login-form-section {
                padding: 30px;
            }
            .logo-text {
                font-size: 28px;
            }
            .logo-square {
                width: 120px;
                height: 120px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Section Informations Entreprise -->
        <div class="company-info">
            <div class="company-logo">
                <div class="logo-square">
                    <img src="Image/logo.png" alt="Logo Mobilis Fleet">
                </div>
                <div class="logo-placeholder-text"></div>
                <div class="logo-text">Mobilis Fleet</div>
                <div class="logo-subtitle">Système de gestion des véhicules</div>
            </div>

            <p class="company-tagline">
                Optimisez la gestion de votre flotte de véhicules grâce à des solutions intelligentes.
                Accès sécurisé à votre tableau de bord et centre de contrôle.
            </p>

            <ul class="company-features">
                <li><i class="fas fa-gas-pump"></i> Suivi de la consommation de carburant</li>
                <li><i class="fas fa-tools"></i> Planification de la maintenance</li>
                <li><i class="fas fa-chart-line"></i> Analyse des performances</li>
                <li><i class="fas fa-shield-alt"></i> Gestion de la sécurité de la flotte</li>
                <li><i class="fas fa-users"></i> Outils de gestion des conducteurs</li>
            </ul>
        </div>

        <!-- Section Formulaire de Connexion -->
        <div class="login-form-section">
            <div class="login-header">
                <h1>Connexion à la gestion de flotte</h1>
                <p>Entrez vos identifiants pour accéder au système de gestion de flotte</p>
            </div>

            <form id="loginForm" action="auth/login.php" method="POST">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Entrez votre nom d'utilisateur">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe">
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember">
                    <label for="remember">Se souvenir de moi</label>
                </div>

                <button type="submit" class="login-button">
                    <i class="fas fa-sign-in-alt"></i> Accéder au tableau de bord
                </button>
            </form>

            <div class="login-footer">
                <p>Besoin d'aide ? 
                    <a href="https://mobilis.dz/contact">Contacter le support flotte</a> | 
                    <a href="#">Configuration requise</a>
                </p>
                <p>© 2026 Mobilis Fleet Management. Tous droits réservés.</p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        const logoSquare = document.querySelector('.logo-square');
        logoSquare.addEventListener('click', function() {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = 'image/*';

            fileInput.onchange = function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const img = document.createElement('img');
                        img.src = event.target.result;
                        img.alt = "Logo Mobilis Fleet";

                        logoSquare.innerHTML = '';
                        logoSquare.appendChild(img);

                        document.querySelector('.logo-placeholder-text').style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            };

            fileInput.click();
        });

        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = this;
            const button = document.querySelector('.login-button');
            const formData = new FormData(form);

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authentification...';
            button.disabled = true;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                    window.location.href = result.redirect;
                } else {
                    alert(result.message);
                    button.innerHTML = 'Accéder au tableau de bord';
                    button.disabled = false;
                }
            } catch (err) {
                alert("Erreur serveur. Vérifiez login.php ou la base de données.");
                button.innerHTML = 'Accéder au tableau de bord';
                button.disabled = false;
            }
        });
    </script>
</body>
</html>