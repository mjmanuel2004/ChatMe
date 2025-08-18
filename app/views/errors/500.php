<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur serveur - ChatMe</title>
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
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
        }
        
        .error-container {
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            background: linear-gradient(45deg, #ffffff, var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .error-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }
        
        .error-message {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .error-illustration {
            font-size: 6rem;
            margin-bottom: 2rem;
            opacity: 0.8;
            animation: shake 2s ease-in-out infinite;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .btn-home {
            background: var(--accent-color);
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }
        
        .btn-home:hover {
            background: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            color: white;
        }
        
        .btn-retry {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin: 0.5rem;
            backdrop-filter: blur(10px);
        }
        
        .btn-retry:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
            transform: translateY(-2px);
            color: white;
        }
        
        .error-details {
            margin-top: 3rem;
            padding: 2rem;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            text-align: left;
        }
        
        .error-details h4 {
            margin-bottom: 1.5rem;
            color: white;
            text-align: center;
        }
        
        .error-details ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .error-details li {
            margin: 1rem 0;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .error-details li:last-child {
            border-bottom: none;
        }
        
        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }
        
        .floating-element {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-element:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-element:nth-child(2) {
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .floating-element:nth-child(3) {
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }
        
        @media (max-width: 768px) {
            .error-code {
                font-size: 6rem;
            }
            
            .error-title {
                font-size: 2rem;
            }
            
            .error-message {
                font-size: 1rem;
            }
            
            .error-container {
                padding: 1rem;
            }
            
            .error-details {
                margin-top: 2rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <div class="floating-element">
            <i class="fas fa-exclamation-triangle fa-3x"></i>
        </div>
        <div class="floating-element">
            <i class="fas fa-cog fa-2x"></i>
        </div>
        <div class="floating-element">
            <i class="fas fa-bug fa-2x"></i>
        </div>
    </div>
    
    <div class="container">
        <div class="error-container">
            <div class="error-illustration">
                <i class="fas fa-server"></i>
            </div>
            
            <div class="error-code">500</div>
            
            <h1 class="error-title">Erreur interne du serveur</h1>
            
            <p class="error-message">
                Oups ! Quelque chose s'est mal passé de notre côté. 
                Nos serveurs rencontrent actuellement un problème technique. 
                Nous travaillons déjà à résoudre ce problème.
            </p>
            
            <div class="d-flex flex-wrap justify-content-center">
                <a href="/" class="btn-home">
                    <i class="fas fa-home me-2"></i>Retour à l'accueil
                </a>
                <a href="javascript:location.reload()" class="btn-retry">
                    <i class="fas fa-redo me-2"></i>Réessayer
                </a>
            </div>
            
            <div class="error-details">
                <h4><i class="fas fa-info-circle me-2"></i>Que faire maintenant ?</h4>
                <ul>
                    <li><i class="fas fa-clock me-2"></i>Attendez quelques minutes et réessayez</li>
                    <li><i class="fas fa-refresh me-2"></i>Actualisez la page</li>
                    <li><i class="fas fa-home me-2"></i>Retournez à la page d'accueil</li>
                    <li><i class="fas fa-envelope me-2"></i>Contactez notre support si le problème persiste</li>
                </ul>
            </div>
        </div>
    </div>
    
    <script>
        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.error-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                container.style.transition = 'all 0.8s ease';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
            
            // Auto-retry après 30 secondes (optionnel)
            setTimeout(() => {
                const retryBtn = document.querySelector('.btn-retry');
                if (retryBtn) {
                    retryBtn.innerHTML = '<i class="fas fa-redo me-2"></i>Réessayer automatiquement...';
                    retryBtn.style.opacity = '0.7';
                    
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                }
            }, 30000);
        });
    </script>
</body>
</html>
