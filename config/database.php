<?php
/**
 * Configuration de la base de données
 * Paramètres de connexion à MySQL
 */

// Configuration de la base de données
define('DB_HOST', 'db');  // Nom du service Docker
define('DB_NAME', 'chatme_db');
define('DB_USER', 'chatme_user');
define('DB_PASS', 'chatme_password');
define('DB_CHARSET', 'utf8mb4');

// Options PDO
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
]);

/**
 * Classe Database pour gérer la connexion
 */
class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, DB_OPTIONS);
        } catch (PDOException $e) {
            throw new Exception("Erreur de connexion à la base de données: " . $e->getMessage());
        }
    }

    /**
     * Singleton pour obtenir l'instance de la base de données
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Obtenir la connexion PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Exécuter une requête préparée
     */
    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Erreur d'exécution de requête: " . $e->getMessage());
        }
    }

    /**
     * Récupérer une seule ligne
     */
    public function fetchOne($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Récupérer plusieurs lignes
     */
    public function fetchAll($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Insérer une ligne et retourner l'ID
     */
    public function insert($sql, $params = [])
    {
        $this->query($sql, $params);
        return $this->connection->lastInsertId();
    }

    /**
     * Mettre à jour des lignes
     */
    public function update($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Supprimer des lignes
     */
    public function delete($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Commencer une transaction
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Valider une transaction
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * Annuler une transaction
     */
    public function rollback()
    {
        return $this->connection->rollback();
    }

    /**
     * Vérifier si la connexion est active
     */
    public function isConnected()
    {
        try {
            $this->connection->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

// Fonction helper pour obtenir la base de données
function db()
{
    return Database::getInstance();
}
?> 