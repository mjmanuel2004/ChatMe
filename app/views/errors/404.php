<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page non trouvée - ChatMe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --accent-color: #f59e0b;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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
        
        .btn-back {
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
        
        .btn-back:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
            transform: translateY(-2px);
            color: white;
        }
        
        .suggestions {
            margin-top: 3rem;
            padding: 2rem;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .suggestions h4 {
            margin-bottom: 1.5rem;
            color: white;
        }
        
        .suggestions ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .suggestions li {
            margin: 0.5rem 0;
        }
        
        .suggestions a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .suggestions a:hover {
            color: var(--accent-color);
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
            
            .suggestions {
                margin-top: 2rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <div class="floating-element">
            <i class="fas fa-comment fa-3x"></i>
        </div>
        <div class="floating-element">
            <i class="fas fa-heart fa-2x"></i>
        </div>
        <div class="floating-element">
            <i class="fas fa-share fa-2x"></i>
        </div>
    </div>
    
    <div class="container">
        <div class="error-container">
            <div class="error-illustration">
                <i class="fas fa-search"></i>
            </div>
            
            <div class="error-code">404</div>
            
            <h1 class="error-title">Oups ! Page non trouvée</h1>
            
            <p class="error-message">
                Désolé, la page que vous recherchez semble avoir disparu dans les méandres d'Internet. 
                Elle a peut-être été déplacée, supprimée ou n'a jamais existé.
            </p>
            
            <div class="d-flex flex-wrap justify-content-center">
                <a href="/" class="btn-home">
                    <i class="fas fa-home me-2"></i>Retour à l'accueil
                </a>
                <a href="javascript:history.back()" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Page précédente
                </a>
            </div>
            
            <div class="suggestions">
                <h4><i class="fas fa-lightbulb me-2"></i>Suggestions</h4>
                <div class="row">
                    <div class="col-md-6">
                        <ul>
                            <li><a href="/"><i class="fas fa-home me-2"></i>Accueil</a></li>
                            <li><a href="/login"><i class="fas fa-sign-in-alt me-2"></i>Connexion</a></li>
                            <li><a href="/register"><i class="fas fa-user-plus me-2"></i>Inscription</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul>
                            <li><a href="/feed"><i class="fas fa-newspaper me-2"></i>Fil d'actualité</a></li>
                            <li><a href="/search"><i class="fas fa-search me-2"></i>Recherche</a></li>
                            <li><a href="/messages"><i class="fas fa-envelope me-2"></i>Messages</a></li>
                        </ul>
                    </div>
                </div>
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
        });
    </script>
</body>
</html>
