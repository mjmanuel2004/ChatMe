<?php
/**
 * Point d'entrée principal de l'application ChatMe
 * Gère le routage et l'initialisation de l'application
 */

// Démarrage de la session
session_start();

// Configuration des erreurs (à désactiver en production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclusion des fichiers de configuration
require_once '../config/database.php';
require_once '../config/routes.php';
require_once '../app/core/Router.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Model.php';

// Autoloader pour les contrôleurs et modèles
spl_autoload_register(function ($class) {
    // Contrôleurs
    $controllerPath = "../app/controllers/{$class}.php";
    if (file_exists($controllerPath)) {
        require_once $controllerPath;
        return;
    }
    
    // Modèles
    $modelPath = "../app/models/{$class}.php";
    if (file_exists($modelPath)) {
        require_once $modelPath;
        return;
    }
});

// Récupération de l'URL demandée
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = dirname($_SERVER['SCRIPT_NAME']);
$path = str_replace($basePath, '', $requestUri);
$path = parse_url($path, PHP_URL_PATH);

// Nettoyage du chemin
$path = trim($path, '/');
if (empty($path)) {
    $path = 'home';
}

// Initialisation du routeur
$router = new Router();

// Enregistrement des routes
$router->addRoute('', 'HomeController@index');
$router->addRoute('home', 'HomeController@index');
$router->addRoute('login', 'AuthController@login');
$router->addRoute('register', 'AuthControllers@register');
$router->addRoute('logout', 'AuthController@logout');
$router->addRoute('profile', 'UserController@profile');
$router->addRoute('feed', 'PostController@feed');
$router->addRoute('post/create', 'PostController@create');
$router->addRoute('messages', 'MessageController@index');
$router->addRoute('search', 'SearchController@index');

// Gestion des routes dynamiques pour les profils utilisateurs
$router->addRoute('user/([0-9]+)', 'UserController@show');

// Exécution du routeur
try {
    $router->dispatch($path);
} catch (Exception $e) {
    // Page 404 personnalisée
    http_response_code(404);
    include '../app/views/errors/404.php';
}
?>
