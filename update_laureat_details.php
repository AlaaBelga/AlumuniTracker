<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$servername = "localhost:8889"; 
$username = "root"; 
$password = "root"; 
$dbname = "LaureatsINPT"; 
$tableLaureat = "LAUREAT"; 

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (empty($data['id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID du lauréat manquant.']);
    exit;
}

try {
    $conn = new PDO("mysql:host=localhost;port=8889;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour mettre à jour les champs de la table LAUREAT
    $stmt = $conn->prepare("
        UPDATE {$tableLaureat}
        SET nom = ?, prenom = ?, filiere = ?, annee_diplome = ?, email = ?, telephone = ?
        WHERE id_laureat = ?
    ");

    $stmt->execute([
        $data['nom'],
        $data['prenom'],
        $data['filiere'],
        $data['annee'],
        $data['email'] ?? null,
        $data['telephone'] ?? null,
        $data['id']
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Profil mis à jour avec succès!']);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erreur BDD: ' . $e->getMessage()]);
}
?>