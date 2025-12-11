<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$servername = "localhost";
$username = "root";          
$password = "root";          
$dbname = "LaureatsINPT";    

$tableParcours = "PARCOURS_PROFESSIONNEL";
$tableEntreprise = "ENTREPRISE";
$tablePoste = "POSTE";

// Get POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Check for required data
if (empty($data['laureatId']) || empty($data['entreprise']) || empty($data['poste']) || empty($data['dateDebut'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Données manquantes (laureatId, entreprise, poste ou dateDebut).']);
    exit;
}

// Les données reçues de JavaScript
$laureatId = $data['laureatId'];
$entrepriseNom = trim($data['entreprise']);
$posteTitre = trim($data['poste']);
$dateDebut = $data['dateDebut'];
$dateFin = $data['dateFin'] ?? null;
$localisation = $data['localisation'] ?? null;

try {
    // 1. CONNEXION
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->beginTransaction();

    // 2. Gérer ENTREPRISE
    $stmt = $conn->prepare("SELECT id_entreprise FROM {$tableEntreprise} WHERE nom = ?");
    $stmt->execute([$entrepriseNom]);
    $entrepriseRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $entrepriseId = $entrepriseRow ? $entrepriseRow['id_entreprise'] : null;

    if (!$entrepriseId) {
        $stmt = $conn->prepare("INSERT INTO {$tableEntreprise} (nom) VALUES (?)");
        $stmt->execute([$entrepriseNom]);
        $entrepriseId = $conn->lastInsertId();
    }

    // 3. Gérer POSTE 
    $stmt = $conn->prepare("SELECT id_poste FROM {$tablePoste} WHERE titre = ?");
    $stmt->execute([$posteTitre]);
    $posteRow = $stmt->fetch(PDO::FETCH_ASSOC);
    $posteId = $posteRow ? $posteRow['id_poste'] : null;
    
    if (!$posteId) {
        $stmt = $conn->prepare("INSERT INTO {$tablePoste} (titre) VALUES (?)");
        $stmt->execute([$posteTitre]);
        $posteId = $conn->lastInsertId();
    }
    
    // 4. Insérer le PARCOURS PROFESSIONNEL
    $stmt = $conn->prepare("
        INSERT INTO {$tableParcours} (id_laureat, id_entreprise, id_poste, date_debut, date_fin, localisation) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $laureatId,
        $entrepriseId,
        $posteId,
        $dateDebut,
        $dateFin,
        $localisation
    ]);

    $conn->commit();
    echo json_encode(['status' => 'success', 'message' => 'Parcours professionnel ajouté avec succès!']);

} catch(PDOException $e) {
    if (isset($conn)) $conn->rollBack();
    http_response_code(500);
    // Important pour le débogage: Affiche l'erreur exacte
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la sauvegarde: ' . $e->getMessage()]); 
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erreur serveur: ' . $e->getMessage()]); 
}
?>