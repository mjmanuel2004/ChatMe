<?php

class UserModel extends Model
{
    protected $table = 'Utilisateur';
    protected $primaryKey = 'id_utilisateur';
    protected $allowedFields = ['pseudo', 'email', 'mot_de_passe', 'nom', 'prenom', 'photo_profil_url', 'biographie', 'date_creation_compte', 'statut'];

    public function findByEmailOrPseudo($idenifiant)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? OR pseudo = ?";
        return $this->db->fetchOne($sql, [$idenifiant, $idenifiant]);
    }

    public function findByEmail($email){
        return $this->whereFirst('email',$email);
    }

    public function findByPseudo($pseudo){
        return $this->whereFirst('pseudo',$pseudo);
    }
}

