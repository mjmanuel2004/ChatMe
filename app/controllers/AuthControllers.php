<?php

class AuthControllers extends Controller{

    private $userModel;

    public function __construct(){
        parent::__construct();
        require_once '../app/models/userModel.php';
        $this->userModel = new userModel();
    }
    
    public function register(){
        $data = ['errors' => []];
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            //Nettoyage des données

            $pseudo = trim($_POST['pseudo'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');

            //Validation des données

            if(empty($pseudo)||(empty($email))||(empty($mot_de_passe))||(empty($nom))||(empty($prenom))){
                $data['errors'][] = 'Veuillez remplir tous les champs';
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $data['errors'][] = 'Email invalide';
            }
            if(strlen($mot_de_passe) < 8){
                $data['errors'][] = 'Le mot de passe doit contenir au moins 8 caractères';
            }
            if($this->userModel->findbyEmail($email)){
                $data['errors'][] = 'Email déjà utilisé';
            }
            if($this->userModel->findbyPseudo($pseudo)){
                $data['errors'][] = 'Pseudo déjà utilisé';
            }
            if(empty($data['errors'])){
                //Hashage du mot de passe
                $mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT);
                $this->userModel->create([
                    'pseudo' => $pseudo,
                    'email' => $email,
                    'mot_de_passe' => $mot_de_passe,
                    'nom' => $nom,
                    'prenom' => $prenom,
                ]);
                //Redirection vers la page de connexion avec message de succès
                $this->view('login', ['success' => 'Inscription réussie ! Vous pouvez maintenant vous connecter.']);
            }
        }

        $this->view('register', $data);

    }
}


?>