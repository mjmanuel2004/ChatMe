<?php
/**
 * Classe de base pour tous les modèles
 * Fournit les méthodes communes pour l'interaction avec la base de données
 */

abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];

    /**
     * Constructeur du modèle
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Trouver un enregistrement par son ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->fetchOne($sql, [$id]);
    }

    /**
     * Trouver tous les enregistrements
     */
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->fetchAll($sql);
    }

    /**
     * Trouver des enregistrements avec des conditions
     */
    public function where($column, $value, $operator = '=')
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} ?";
        return $this->db->fetchAll($sql, [$value]);
    }

    /**
     * Trouver un enregistrement avec des conditions
     */
    public function whereFirst($column, $value, $operator = '=')
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} ? LIMIT 1";
        return $this->db->fetchOne($sql, [$value]);
    }

    /**
     * Créer un nouvel enregistrement
     */
    public function create($data)
    {
        // Filtrer les données selon les champs fillable
        $data = $this->filterFillable($data);
        
        if (empty($data)) {
            throw new Exception("Aucune donnée valide à insérer");
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        return $this->db->insert($sql, $data);
    }

    /**
     * Mettre à jour un enregistrement
     */
    public function update($id, $data)
    {
        // Filtrer les données selon les champs fillable
        $data = $this->filterFillable($data);
        
        if (empty($data)) {
            throw new Exception("Aucune donnée valide à mettre à jour");
        }

        $setClause = [];
        foreach (array_keys($data) as $column) {
            $setClause[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $setClause);
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";
        
        $data['id'] = $id;
        return $this->db->update($sql, $data);
    }

    /**
     * Supprimer un enregistrement
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return $this->db->delete($sql, [$id]);
    }

    /**
     * Paginer les résultats
     */
    public function paginate($page = 1, $perPage = 10, $conditions = [])
    {
        $whereClause = '';
        $params = [];
        
        if (!empty($conditions)) {
            $whereParts = [];
            foreach ($conditions as $column => $value) {
                $whereParts[] = "{$column} = ?";
                $params[] = $value;
            }
            $whereClause = 'WHERE ' . implode(' AND ', $whereParts);
        }
        
        $query = "SELECT * FROM {$this->table} {$whereClause} ORDER BY {$this->primaryKey} DESC";
        
        return $this->db->paginate($query, $params, $page, $perPage);
    }

    /**
     * Rechercher des enregistrements
     */
    public function search($term, $columns = [])
    {
        if (empty($columns)) {
            $columns = $this->fillable;
        }
        
        $searchParts = [];
        $params = [];
        
        foreach ($columns as $column) {
            $searchParts[] = "{$column} LIKE ?";
            $params[] = "%{$term}%";
        }
        
        $whereClause = implode(' OR ', $searchParts);
        $sql = "SELECT * FROM {$this->table} WHERE {$whereClause}";
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Compter les enregistrements
     */
    public function count($conditions = [])
    {
        $whereClause = '';
        $params = [];
        
        if (!empty($conditions)) {
            $whereParts = [];
            foreach ($conditions as $column => $value) {
                $whereParts[] = "{$column} = ?";
                $params[] = $value;
            }
            $whereClause = 'WHERE ' . implode(' AND ', $whereParts);
        }
        
        $sql = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";
        $result = $this->db->fetchOne($sql, $params);
        
        return $result['total'];
    }

    /**
     * Trouver ou créer un enregistrement
     */
    public function firstOrCreate($data)
    {
        // Essayer de trouver l'enregistrement
        $conditions = array_intersect_key($data, array_flip($this->fillable));
        $existing = $this->whereFirst(array_keys($conditions)[0], array_values($conditions)[0]);
        
        if ($existing) {
            return $existing;
        }
        
        // Créer un nouvel enregistrement
        $id = $this->create($data);
        return $this->find($id);
    }

    /**
     * Mettre à jour ou créer un enregistrement
     */
    public function updateOrCreate($searchData, $updateData)
    {
        // Essayer de trouver l'enregistrement
        $conditions = array_intersect_key($searchData, array_flip($this->fillable));
        $existing = $this->whereFirst(array_keys($conditions)[0], array_values($conditions)[0]);
        
        if ($existing) {
            // Mettre à jour
            $this->update($existing[$this->primaryKey], $updateData);
            return $this->find($existing[$this->primaryKey]);
        }
        
        // Créer un nouvel enregistrement
        $mergedData = array_merge($searchData, $updateData);
        $id = $this->create($mergedData);
        return $this->find($id);
    }

    /**
     * Filtrer les données selon les champs fillable
     */
    protected function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Masquer les champs sensibles
     */
    protected function hideFields($data)
    {
        if (empty($this->hidden)) {
            return $data;
        }
        
        return array_diff_key($data, array_flip($this->hidden));
    }

    /**
     * Exécuter une requête personnalisée
     */
    public function query($sql, $params = [])
    {
        return $this->db->query($sql, $params);
    }

    /**
     * Exécuter une requête et récupérer une ligne
     */
    public function queryOne($sql, $params = [])
    {
        return $this->db->fetchOne($sql, $params);
    }

    /**
     * Exécuter une requête et récupérer plusieurs lignes
     */
    public function queryAll($sql, $params = [])
    {
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Commencer une transaction
     */
    public function beginTransaction()
    {
        return $this->db->beginTransaction();
    }

    /**
     * Valider une transaction
     */
    public function commit()
    {
        return $this->db->commit();
    }

    /**
     * Annuler une transaction
     */
    public function rollback()
    {
        return $this->db->rollback();
    }

    /**
     * Obtenir le nom de la table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Obtenir la clé primaire
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * Obtenir les champs fillable
     */
    public function getFillable()
    {
        return $this->fillable;
    }

    /**
     * Définir les champs fillable
     */
    public function setFillable($fillable)
    {
        $this->fillable = $fillable;
        return $this;
    }

    /**
     * Obtenir les champs cachés
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Définir les champs cachés
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
        return $this;
    }
}
?> 