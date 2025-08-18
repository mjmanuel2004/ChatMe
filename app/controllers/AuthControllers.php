<?php

class AuthControllers extends Controller
{
    private $userModel;

    public function __construct()
    {
        require_once '../app/models/UserModel.php';
        $this->userModel = new UserModel();
    }

    /**
     * Login function
     * @return void
     */
    public function login(){
        $data = [];
        
        if(isset($_SESSION['success_message'])){
            $data['success_message'] = $_SESSION['success_message'];
            unset($_SESSION['success_message']);
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $identifiant = trim($_POST['identifiant'] ?? '');
            $password = trim($_POST['password'] ?? '');
            
            if(empty($identifiant) || empty($password)){
                $data['error_message'] = 'Veuillez remplir tous les champs';
            }else{
                $user = $this->userModel->findByEmailOrPseudo($identifiant);
                
                if($user && password_verify($password, $user['mot_de_passe'])){
                    $_SESSION['user_id'] = $user['id_utilisateur'];
                    $_SESSION['user_pseudo'] = $user['pseudo'];

                    $this->redirect('/feed');
                }else{
                    $data['error_message'] = 'Identifiant ou mot de passe incorrect';
                }

                

            }
            
        }

        $this->view('login', $data);

    }

    public function logout(){
        session_unset();
        session_destroy();
        $this->redirect('/login');
    }
}