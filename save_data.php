<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Configuration MAMP
$servername = "localhost:8889"; 
$username = "root";
$password = "root";
$dbname = "LaureatsINPT";

// Get POST data
$input = file_get_contents('php://input');
$laureatsData = json_decode($input, true);

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    foreach ($laureatsData as $laureat) {
        // Insertion / Mise à jour du LAUREAT
        // Utilise id_laureat et annee_diplome, retire profile_pic_url
        $stmt = $conn->prepare("
            INSERT INTO LAUREAT (id_laureat, nom, prenom, filiere, annee_diplome, email, telephone) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            nom=VALUES(nom), prenom=VALUES(prenom), filiere=VALUES(filiere), 
            annee_diplome=VALUES(annee_diplome), email=VALUES(email), telephone=VALUES(telephone)
        ");
        
        $stmt->execute([
            $laureat['id'],
            $laureat['nom'],
            $laureat['prenom'],
            $laureat['filiere'],
            $laureat['annee'],
            $laureat['email'] ?? '',
            $laureat['telephone'] ?? ''
        ]);
    }
    
    echo json_encode(['status' => 'success', 'message' => 'Data saved successfully (Parcours disabled)']);
    
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
}
?>