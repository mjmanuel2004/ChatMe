<?php
echo "<h1>Test ChatMe</h1>";
echo "<p>PHP fonctionne correctement !</p>";
echo "<p>Version PHP : " . phpversion() . "</p>";
echo "<p>Extensions chargées :</p>";
echo "<ul>";
foreach (get_loaded_extensions() as $ext) {
    echo "<li>$ext</li>";
}
echo "</ul>";

// Test de connexion à la base de données
try {
    $pdo = new PDO(
        "mysql:host=db;dbname=chatme_db;charset=utf8mb4",
        "chatme_user",
        "chatme_password",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    echo "<p style='color: green;'>✅ Connexion à la base de données réussie !</p>";
    
    // Test d'une requête simple
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM Utilisateur");
    $result = $stmt->fetch();
    echo "<p>Nombre d'utilisateurs dans la base : " . $result['count'] . "</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Erreur de connexion à la base de données : " . $e->getMessage() . "</p>";
}

// Informations sur le serveur
echo "<h2>Informations serveur</h2>";
echo "<p>Document Root : " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>Script Filename : " . $_SERVER['SCRIPT_FILENAME'] . "</p>";
echo "<p>Request URI : " . $_SERVER['REQUEST_URI'] . "</p>";
?> 