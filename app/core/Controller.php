<?php
/**
 * Classe de base pour tous les contrôleurs
 * Fournit les méthodes communes et l'initialisation
 */

abstract class Controller
{
    protected $db;
    protected $user;

    /**
     * Constructeur du contrôleur
     */
    public function __construct()
    {
        // Initialiser la connexion à la base de données
        $this->db = Database::getInstance();
        
        // Récupérer les informations de l'utilisateur connecté
        $this->user = $this->getCurrentUser();
    }

    /**
     * Afficher une vue
     */
    protected function view($view, $data = [])
    {
        // Extraire les données pour les rendre disponibles dans la vue
        extract($data);
        
        // Chemin vers le fichier de vue
        $viewPath = "../app/views/{$view}.php";
        
        // Vérifier si le fichier de vue existe
        if (!file_exists($viewPath)) {
            throw new Exception("Vue non trouvée: {$view}");
        }
        
        // Démarrer la capture de sortie
        ob_start();
        
        // Inclure la vue
        include $viewPath;
        
        // Récupérer le contenu et nettoyer le buffer
        $content = ob_get_clean();
        
        // Afficher le contenu
        echo $content;
    }

    /**
     * Afficher une vue avec layout
     */
    protected function viewWithLayout($view, $data = [], $layout = 'default')
    {
        // Extraire les données
        extract($data);
        
        // Capturer le contenu de la vue
        ob_start();
        $this->view($view, $data);
        $content = ob_get_clean();
        
        // Inclure le layout
        $layoutPath = "../app/views/layouts/{$layout}.php";
        
        if (!file_exists($layoutPath)) {
            throw new Exception("Layout non trouvé: {$layout}");
        }
        
        include $layoutPath;
    }

    /**
     * Retourner une réponse JSON
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Rediriger vers une URL
     */
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    /**
     * Rediriger en arrière
     */
    protected function back()
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: $referer");
        exit;
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    protected function isAdmin()
    {
        return $this->isAuthenticated() && 
               isset($_SESSION['user_role']) && 
               $_SESSION['user_role'] === 'admin';
    }

    /**
     * Récupérer l'utilisateur connecté
     */
    protected function getCurrentUser()
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        $userId = $_SESSION['user_id'];
        
        try {
            $sql = "SELECT * FROM Utilisateur WHERE id_utilisateur = ? AND statut = 'actif'";
            return $this->db->fetchOne($sql, [$userId]);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Vérifier les permissions de l'utilisateur
     */
    protected function hasPermission($permission)
    {
        if (!$this->isAuthenticated()) {
            return false;
        }

        // Pour l'instant, on vérifie juste le rôle admin
        // Vous pouvez étendre cette logique selon vos besoins
        return $this->isAdmin();
    }

    /**
     * Valider les données d'entrée
     */
    protected function validate($data, $rules)
    {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? '';
            
            // Règles de validation
            if (strpos($rule, 'required') !== false && empty($value)) {
                $errors[$field] = "Le champ $field est requis";
                continue;
            }
            
            if (strpos($rule, 'email') !== false && !empty($value)) {
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "Le champ $field doit être une adresse email valide";
                }
            }
            
            if (strpos($rule, 'min:') !== false) {
                preg_match('/min:(\d+)/', $rule, $matches);
                $min = $matches[1];
                if (strlen($value) < $min) {
                    $errors[$field] = "Le champ $field doit contenir au moins $min caractères";
                }
            }
            
            if (strpos($rule, 'max:') !== false) {
                preg_match('/max:(\d+)/', $rule, $matches);
                $max = $matches[1];
                if (strlen($value) > $max) {
                    $errors[$field] = "Le champ $field ne peut pas dépasser $max caractères";
                }
            }
        }
        
        return $errors;
    }

    /**
     * Nettoyer les données d'entrée
     */
    protected function sanitize($data)
    {
        $clean = [];
        
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $clean[$key] = trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
            } else {
                $clean[$key] = $value;
            }
        }
        
        return $clean;
    }

    /**
     * Générer un token CSRF
     */
    protected function generateCsrfToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }

    /**
     * Vérifier un token CSRF
     */
    protected function verifyCsrfToken($token)
    {
        return isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Logger une action
     */
    protected function logAction($action, $details = '')
    {
        if (!$this->isAuthenticated()) {
            return;
        }

        try {
            $sql = "INSERT INTO Piste_Audit (id_utilisateur_acteur, type_action, details_action) 
                    VALUES (?, ?, ?)";
            $this->db->insert($sql, [
                $_SESSION['user_id'],
                $action,
                $details
            ]);
        } catch (Exception $e) {
            // En cas d'erreur, on ne fait rien pour ne pas interrompre le flux
        }
    }

    /**
     * Envoyer une notification
     */
    protected function sendNotification($userId, $type, $sourceId = null)
    {
        try {
            $sql = "INSERT INTO Notification (id_destinataire, type_notification, id_source) 
                    VALUES (?, ?, ?)";
            $this->db->insert($sql, [$userId, $type, $sourceId]);
        } catch (Exception $e) {
            // Gérer l'erreur selon vos besoins
        }
    }

    /**
     * Paginer les résultats
     */
    protected function paginate($query, $params = [], $page = 1, $perPage = 10)
    {
        // Compter le total
        $countQuery = "SELECT COUNT(*) as total FROM ($query) as subquery";
        $total = $this->db->fetchOne($countQuery, $params)['total'];
        
        // Calculer l'offset
        $offset = ($page - 1) * $perPage;
        
        // Ajouter la pagination à la requête
        $paginatedQuery = $query . " LIMIT $perPage OFFSET $offset";
        $results = $this->db->fetchAll($paginatedQuery, $params);
        
        // Calculer les informations de pagination
        $totalPages = ceil($total / $perPage);
        
        return [
            'data' => $results,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages,
                'has_next' => $page < $totalPages,
                'has_prev' => $page > 1
            ]
        ];
    }
}
?> 