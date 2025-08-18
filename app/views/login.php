<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - ChatMe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --accent-color: #f59e0b;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --danger-color: #ef4444;
            --success-color: #10b981;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .login-container {
            max-width: 450px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-logo {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .login-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            color: #6b7280;
            font-size: 1rem;
        }
        
        .form-floating {
            margin-bottom: 1.5rem;
        }
        
        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
        }
        
        .form-label {
            color: var(--dark-color);
            font-weight: 500;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
            color: white;
        }
        
        .btn-login:disabled {
            opacity: 0.7;
            transform: none;
            box-shadow: none;
        }
        
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .form-links {
            text-align: center;
            margin-top: 2rem;
        }
        
        .form-links a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .form-links a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }
        
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }
        
        .divider span {
            background: white;
            padding: 0 1rem;
            color: #6b7280;
            font-size: 0.9rem;
        }
        
        .back-home {
            position: absolute;
            top: 2rem;
            left: 2rem;
            color: white;
            font-size: 1.2rem;
            text-decoration: none;
            opacity: 0.8;
            transition: all 0.3s ease;
        }
        
        .back-home:hover {
            opacity: 1;
            color: white;
            transform: translateX(-5px);
        }
        
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            z-index: 10;
        }
        
        .form-control.with-icon {
            padding-left: 3rem;
        }
        
        @media (max-width: 768px) {
            .login-container {
                padding: 1rem;
            }
            
            .login-card {
                padding: 2rem;
            }
            
            .back-home {
                top: 1rem;
                left: 1rem;
            }
        }
    </style>
</head>
<body>
    <a href="/" class="back-home">
        <i class="fas fa-arrow-left me-2"></i>Retour à l'accueil
    </a>
    
    <div class="container">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="login-logo">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h1 class="login-title">Bon retour !</h1>
                    <p class="login-subtitle">Connectez-vous à votre compte ChatMe</p>
                </div>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= htmlspecialchars($error_message) ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= htmlspecialchars($success_message) ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="/login" id="loginForm">
                    <div class="input-group mb-3">
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="form-floating">
                            <input type="text" 
                                   class="form-control with-icon" 
                                   id="identifiant" 
                                   name="identifiant" 
                                   placeholder="Email ou pseudo"
                                   value="<?= isset($_POST['identifiant']) ? htmlspecialchars($_POST['identifiant']) : '' ?>"
                                   required>
                            <label for="identifiant">Email ou pseudo</label>
                        </div>
                    </div>
                    
                    <div class="input-group mb-4">
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="form-floating">
                            <input type="password" 
                                   class="form-control with-icon" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Mot de passe"
                                   required>
                            <label for="password">Mot de passe</label>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Se souvenir de moi
                            </label>
                        </div>
                        <a href="/forgot-password" class="text-decoration-none">
                            Mot de passe oublié ?
                        </a>
                    </div>
                    
                    <button type="submit" class="btn btn-login" id="loginBtn">
                        <span class="btn-text">
                            <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                        </span>
                        <div class="loading-spinner"></div>
                    </button>
                </form>
                
                <div class="divider">
                    <span>ou</span>
                </div>
                
                <div class="form-links">
                    <p class="mb-0">
                        Pas encore de compte ? 
                        <a href="/register">Créer un compte</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const btnText = loginBtn.querySelector('.btn-text');
            const loadingSpinner = loginBtn.querySelector('.loading-spinner');
            
            // Animation de chargement lors de la soumission
            loginForm.addEventListener('submit', function(e) {
                loginBtn.disabled = true;
                btnText.style.display = 'none';
                loadingSpinner.style.display = 'inline-block';
                
                // Réactiver le bouton après 5 secondes (sécurité)
                setTimeout(() => {
                    loginBtn.disabled = false;
                    btnText.style.display = 'inline-block';
                    loadingSpinner.style.display = 'none';
                }, 5000);
            });
            
            // Validation côté client
            const identifiantInput = document.getElementById('identifiant');
            const passwordInput = document.getElementById('password');
            
            function validateForm() {
                const identifiant = identifiantInput.value.trim();
                const password = passwordInput.value.trim();
                
                if (identifiant.length < 3) {
                    showFieldError(identifiantInput, 'L\'identifiant doit contenir au moins 3 caractères');
                    return false;
                }
                
                if (password.length < 6) {
                    showFieldError(passwordInput, 'Le mot de passe doit contenir au moins 6 caractères');
                    return false;
                }
                
                return true;
            }
            
            function showFieldError(field, message) {
                field.classList.add('is-invalid');
                
                // Supprimer l'ancien message d'erreur s'il existe
                const existingError = field.parentNode.parentNode.querySelector('.invalid-feedback');
                if (existingError) {
                    existingError.remove();
                }
                
                // Ajouter le nouveau message d'erreur
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = message;
                field.parentNode.parentNode.appendChild(errorDiv);
                
                // Supprimer l'erreur quand l'utilisateur tape
                field.addEventListener('input', function() {
                    field.classList.remove('is-invalid');
                    const errorMsg = field.parentNode.parentNode.querySelector('.invalid-feedback');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }, { once: true });
            }
            
            // Validation en temps réel
            identifiantInput.addEventListener('blur', function() {
                if (this.value.trim().length > 0 && this.value.trim().length < 3) {
                    showFieldError(this, 'L\'identifiant doit contenir au moins 3 caractères');
                }
            });
            
            passwordInput.addEventListener('blur', function() {
                if (this.value.trim().length > 0 && this.value.trim().length < 6) {
                    showFieldError(this, 'Le mot de passe doit contenir au moins 6 caractères');
                }
            });
            
            // Animation d'entrée
            const loginCard = document.querySelector('.login-card');
            loginCard.style.opacity = '0';
            loginCard.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                loginCard.style.transition = 'all 0.6s ease';
                loginCard.style.opacity = '1';
                loginCard.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>