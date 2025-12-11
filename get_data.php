<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$servername = "localhost:8889";
$username = "root"; 
$password = "root"; 
$dbname = "LaureatsINPT";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Requête avec 3 JOINTURES pour récupérer les noms d'entreprise et de poste
    $stmt = $conn->prepare("
        SELECT 
            l.id_laureat AS id, 
            l.nom, 
            l.prenom, 
            l.filiere, 
            l.annee_diplome AS annee, 
            l.email, 
            l.telephone, 
            GROUP_CONCAT(
                CONCAT_WS('||', 
                    p.id_parcours, -- AJOUT: ID unique du parcours
                    e.nom,         -- e.nom (Nom de l'entreprise)
                    t.titre,       -- t.titre (Titre du poste)
                    p.date_debut,  -- p.date_debut
                    p.date_fin,    -- p.date_fin
                    p.localisation -- p.localisation
                ) 
                ORDER BY p.date_debut DESC SEPARATOR ';;'
            ) AS parcours_data
        FROM LAUREAT l 
        LEFT JOIN PARCOURS_PROFESSIONNEL p ON l.id_laureat = p.id_laureat 
        LEFT JOIN ENTREPRISE e ON p.id_entreprise = e.id_entreprise
        LEFT JOIN POSTE t ON p.id_poste = t.id_poste
        GROUP BY l.id_laureat
    ");
    $stmt->execute();
    
    $laureats = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $laureat = [
            'id' => $row['id'], 
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'filiere' => $row['filiere'],
            'annee' => $row['annee'], 
            'email' => $row['email'] ?? 'Non renseigné',
            'telephone' => $row['telephone'] ?? 'Non renseigné',
            'parcours' => []
        ];
        
        if (!empty($row['parcours_data'])) {
            $parcoursItems = explode(';;', $row['parcours_data']);
            foreach ($parcoursItems as $item) {
                if (!empty($item)) {
                    $parts = explode('||', $item);
                    // MISE À JOUR: Décalage des index pour inclure l'ID du parcours
                    $laureat['parcours'][] = [
                        'id' => $parts[0] ?? null,
                        'entreprise' => $parts[1] ?? '',
                        'poste' => $parts[2] ?? '',
                        'dateDebut' => $parts[3] ?? '',
                        'dateFin' => $parts[4] ?? 'Présent',
                        'localisation' => $parts[5] ?? ''
                    ];
                }
            }
        }
        
        $laureats[] = $laureat;
    }
    
    echo json_encode(['status' => 'success', 'data' => $laureats]);
    
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la connexion à la base de données: ' . $e->getMessage()]);
}
?>