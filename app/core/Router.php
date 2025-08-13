<?php
/**
 * Classe Router pour gérer le routage de l'application
 * Gère les routes et leur dispatch vers les contrôleurs appropriés
 */

class Router
{
    private $routes = [];

    /**
     * Ajouter une route
     */
    public function addRoute($path, $handler)
    {
        $this->routes[$path] = $handler;
    }

    /**
     * Ajouter plusieurs routes
     */
    public function addRoutes($routes)
    {
        foreach ($routes as $path => $handler) {
            $this->addRoute($path, $handler);
        }
    }

    /**
     * Dispatcher principal
     */
    public function dispatch($path)
    {
        // Recherche de la route exacte
        if (isset($this->routes[$path])) {
            return $this->executeHandler($this->routes[$path]);
        }

        // Recherche de routes avec paramètres
        foreach ($this->routes as $route => $handler) {
            $pattern = $this->buildPattern($route);
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Supprimer le match complet
                return $this->executeHandler($handler, $matches);
            }
        }

        // Route non trouvée
        throw new Exception("Route non trouvée: $path");
    }

    /**
     * Construire le pattern regex pour une route
     */
    private function buildPattern($route)
    {
        // Remplacer les paramètres par des groupes de capture
        $pattern = preg_replace('/\(\[0-9\]\+\)/', '([0-9]+)', $route);
        $pattern = preg_replace('/\(\[a-zA-Z0-9\]\+\)/', '([a-zA-Z0-9]+)', $route);
        
        // Ajouter les délimiteurs et ancres
        return '/^' . str_replace('/', '\/', $pattern) . '$/';
    }

    /**
     * Exécuter le handler de la route
     */
    private function executeHandler($handler, $params = [])
    {
        if (is_string($handler)) {
            // Format: "Controller@method"
            list($controller, $method) = explode('@', $handler);
            
            // Vérifier si le contrôleur existe
            if (!class_exists($controller)) {
                throw new Exception("Contrôleur non trouvé: $controller");
            }

            // Instancier le contrôleur
            $controllerInstance = new $controller();

            // Vérifier si la méthode existe
            if (!method_exists($controllerInstance, $method)) {
                throw new Exception("Méthode non trouvée: $method dans $controller");
            }

            // Appeler la méthode avec les paramètres
            return call_user_func_array([$controllerInstance, $method], $params);
        }

        // Handler personnalisé (closure)
        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }

        throw new Exception("Handler invalide");
    }

    /**
     * Obtenir toutes les routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Vérifier si une route existe
     */
    public function hasRoute($path)
    {
        return isset($this->routes[$path]);
    }

    /**
     * Générer une URL pour une route
     */
    public function generateUrl($route, $params = [])
    {
        if (!isset($this->routes[$route])) {
            throw new Exception("Route non trouvée: $route");
        }

        $url = $route;
        
        // Remplacer les paramètres dans l'URL
        foreach ($params as $key => $value) {
            $url = str_replace("($key)", $value, $url);
        }

        return $url;
    }

    /**
     * Rediriger vers une route
     */
    public function redirect($route, $params = [])
    {
        $url = $this->generateUrl($route, $params);
        header("Location: $url");
        exit;
    }

    /**
     * Rediriger vers une URL externe
     */
    public function redirectTo($url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * Rediriger en arrière
     */
    public function back()
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: $referer");
        exit;
    }

    /**
     * Middleware pour vérifier l'authentification
     */
    public function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
    }

    /**
     * Middleware pour vérifier les droits admin
     */
    public function requireAdmin()
    {
        $this->requireAuth();
        
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('home');
        }
    }

    /**
     * Middleware pour vérifier les permissions
     */
    public function requirePermission($permission)
    {
        $this->requireAuth();
        
        if (!isset($_SESSION['user_permissions']) || 
            !in_array($permission, $_SESSION['user_permissions'])) {
            $this->redirect('home');
        }
    }

    /**
     * Grouper les routes avec un préfixe
     */
    public function group($prefix, $routes)
    {
        foreach ($routes as $path => $handler) {
            $fullPath = $prefix . '/' . ltrim($path, '/');
            $this->addRoute($fullPath, $handler);
        }
    }

    /**
     * Grouper les routes avec un middleware
     */
    public function middleware($middleware, $routes)
    {
        foreach ($routes as $path => $handler) {
            $this->addRoute($path, function() use ($middleware, $handler) {
                // Exécuter le middleware
                if (is_callable($middleware)) {
                    call_user_func($middleware);
                }
                
                // Exécuter le handler
                return $this->executeHandler($handler);
            });
        }
    }
}
?> 