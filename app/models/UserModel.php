<?php
class userModel extends Model{
    protected $table = 'Utilisateur';
    protected $primaryKey = 'id_utilisateur';
    protected $fillable = ['pseudo', 'email', 'mot_de_passe', 'nom', 'prenom', 'photo_profil_url','biographie', 'date_creation_compte', 'statut' ];

    public function findbyEmail($email){
        return $this->whereFirst('email', $email);
    }

    public function findbyPseudo($pseudo){
        return $this->whereFirst('pseudo', $pseudo);
    }
}
?>