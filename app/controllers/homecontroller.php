<?php
/**
 * Contrôleur principal pour les pages d'accueil et statiques
 * Gère l'affichage de la page d'accueil et des pages d'information
 */

class HomeController extends Controller
{
    /**
     * Constructeur du contrôleur
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Page d'accueil principale
     * Affiche la landing page avec les fonctionnalités de ChatMe
     */
    public function index()
    {
        // Données pour la page d'accueil
        $data = [
            'title' => 'ChatMe - Réseau Social',
            'description' => 'Connectez-vous avec le monde sur ChatMe, la plateforme sociale moderne',
            'features' => [
                [
                    'icon' => 'fas fa-comments',
                    'title' => 'Messagerie instantanée',
                    'description' => 'Échangez des messages en temps réel'
                ],
                [
                    'icon' => 'fas fa-share-alt',
                    'title' => 'Partage de contenu',
                    'description' => 'Partagez vos moments avec votre communauté'
                ],
                [
                    'icon' => 'fas fa-users',
                    'title' => 'Réseau social',
                    'description' => 'Créez et développez votre réseau'
                ],
                [
                    'icon' => 'fas fa-bell',
                    'title' => 'Notifications',
                    'description' => 'Restez informé des activités importantes'
                ],
                [
                    'icon' => 'fas fa-shield-alt',
                    'title' => 'Sécurité',
                    'description' => 'Vos données sont protégées'
                ],
                [
                    'icon' => 'fas fa-mobile-alt',
                    'title' => 'Responsive',
                    'description' => 'Utilisez ChatMe sur tous vos appareils'
                ]
            ],
            'stats' => [
                'users' => '10K+',
                'messages' => '50K+',
                'posts' => '5K+',
                'satisfaction' => '99%'
            ]
        ];

        // Affichage de la vue d'accueil
        $this->view('accueil', $data);
    }

    /**
     * Page À propos
     * Informations sur l'entreprise et l'équipe
     */
    public function about()
    {
        $data = [
            'title' => 'À propos - ChatMe',
            'description' => 'Découvrez l\'histoire et l\'équipe derrière ChatMe',
            'team' => [
                [
                    'name' => 'MONSAN Josue',
                    'role' => 'Développeur Principal',
                    'bio' => 'Passionné de développement web et de technologies modernes'
                ],
                [
                    'name' => 'Équipe ChatMe',
                    'role' => 'Développement & Design',
                    'bio' => 'Une équipe dédiée à créer la meilleure expérience utilisateur'
                ]
            ]
        ];

        $this->view('about', $data);
    }

    /**
     * Page Contact
     * Formulaire de contact et informations de contact
     */
    public function contact()
    {
        $data = [
            'title' => 'Contact - ChatMe',
            'description' => 'Contactez l\'équipe ChatMe pour toute question ou support'
        ];

        // Traitement du formulaire de contact si soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleContactForm();
        }

        $this->view('contact', $data);
    }

    /**
     * Page Conditions d'utilisation
     */
    public function terms()
    {
        $data = [
            'title' => 'Conditions d\'utilisation - ChatMe',
            'description' => 'Conditions d\'utilisation de la plateforme ChatMe'
        ];

        $this->view('terms', $data);
    }

    /**
     * Page Politique de confidentialité
     */
    public function privacy()
    {
        $data = [
            'title' => 'Politique de confidentialité - ChatMe',
            'description' => 'Comment ChatMe protège vos données personnelles'
        ];

        $this->view('privacy', $data);
    }

    /**
     * Traitement du formulaire de contact
     */
    private function handleContactForm()
    {
        // Validation des données du formulaire
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        $errors = [];

        // Validation
        if (empty($name)) {
            $errors[] = 'Le nom est requis';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Une adresse email valide est requise';
        }

        if (empty($subject)) {
            $errors[] = 'Le sujet est requis';
        }

        if (empty($message)) {
            $errors[] = 'Le message est requis';
        }

        // Si pas d'erreurs, traitement du message
        if (empty($errors)) {
            // Ici vous pourriez envoyer un email ou sauvegarder en base
            // Pour l'instant, on simule un succès
            $_SESSION['success_message'] = 'Votre message a été envoyé avec succès !';
        } else {
            $_SESSION['error_message'] = implode('<br>', $errors);
        }

        // Redirection vers la page de contact
        header('Location: /contact');
        exit;
    }

    /**
     * Page d'erreur 404 personnalisée
     */
    public function notFound()
    {
        http_response_code(404);
        $data = [
            'title' => 'Page non trouvée - ChatMe',
            'description' => 'La page que vous recherchez n\'existe pas'
        ];

        $this->view('errors/404', $data);
    }

    /**
     * Page d'erreur 500 (erreur serveur)
     */
    public function serverError()
    {
        http_response_code(500);
        $data = [
            'title' => 'Erreur serveur - ChatMe',
            'description' => 'Une erreur s\'est produite sur le serveur'
        ];

        $this->view('errors/500', $data);
    }

    /**
     * API pour récupérer les statistiques en temps réel
     * Utilisée pour les mises à jour AJAX
     */
    public function getStats()
    {
        // Vérification que c'est une requête AJAX
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            http_response_code(403);
            return;
        }

        // Simulation de statistiques en temps réel
        $stats = [
            'users' => rand(10000, 15000) . '+',
            'messages' => rand(50000, 100000) . '+',
            'posts' => rand(5000, 10000) . '+',
            'satisfaction' => '99%'
        ];

        // Retour en JSON
        header('Content-Type: application/json');
        echo json_encode($stats);
    }

    /**
     * Page de maintenance
     * Affichée quand le site est en maintenance
     */
    public function maintenance()
    {
        $data = [
            'title' => 'Maintenance - ChatMe',
            'description' => 'ChatMe est actuellement en maintenance'
        ];

        $this->view('maintenance', $data);
    }
}
?>
