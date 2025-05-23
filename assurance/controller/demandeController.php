<?php
require_once('../models/dbs.php');

class DemandeController {
    private $db;
    private $uploadDir;
    private const ALLOWED_EXTENSIONS = ['pdf', 'jpg', 'jpeg', 'png'];
    private const MAX_FILE_SIZE = 5242880; // 5 Mo

    public function __construct() {
        try {
            $this->db = Database::getInstance()->getConnection();
            $this->uploadDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

            if (!file_exists($this->uploadDir)) {
                if (!mkdir($this->uploadDir, 0755, true)) {
                    throw new Exception("Impossible de créer le répertoire d'upload");
                }
            }
        } catch (Exception $e) {
            throw new Exception("Erreur d'initialisation : " . $e->getMessage());
        }
    }

    public function creerDemande($data, $files = null) {
        try {
            $this->db->beginTransaction();

            // Nettoyage et validation des données
            $data = $this->sanitizeData($data);
            $this->validerDonnees($data);

            // Insertion de la demande principale
            $demandeId = $this->insererDemandePrincipale($data);

            // Insertion des détails spécifiques
            $this->insererDetailsSpecifiques($demandeId, $data);

            // Gestion des pièces jointes
            if ($files && !empty($files['pieces_jointes']['name'][0])) {
                $this->uploadPiecesJointes($demandeId, $files['pieces_jointes']);
            }

            $this->db->commit();
            return ['success' => true, 'message' => 'Demande créée avec succès', 'id' => $demandeId];
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erreur dans creerDemande : " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function sanitizeData($data) {
        $cleaned = [];
        foreach ($data as $key => $value) {
            $cleaned[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }
        return $cleaned;
    }

    private function validerDonnees($data) {
        $champsObligatoires = [
            'reference', 'type_assurance', 'nom_client', 'prenom_client',
            'email', 'telephone', 'date_naissance', 'formule',
            'date_soumission', 'date_effet_souhaitee'
        ];

        foreach ($champsObligatoires as $champ) {
            if (empty($data[$champ])) {
                throw new Exception("Le champ '$champ' est obligatoire.");
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("L'adresse email n'est pas valide.");
        }
    }

    private function insererDemandePrincipale($data) {
        $sql = "INSERT INTO demandes_assurance (
                    reference, type_assurance, nom_client, prenom_client,
                    email, telephone, date_naissance, formule,
                    date_soumission, date_effet_souhaitee, commentaires,
                    statut, created_at
                ) VALUES (
                    :reference, :type_assurance, :nom_client, :prenom_client,
                    :email, :telephone, :date_naissance, :formule,
                    :date_soumission, :date_effet_souhaitee, :commentaires,
                    'En attente', NOW()
                )";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':reference' => $data['reference'],
            ':type_assurance' => $data['type_assurance'],
            ':nom_client' => $data['nom_client'],
            ':prenom_client' => $data['prenom_client'],
            ':email' => $data['email'],
            ':telephone' => $data['telephone'],
            ':date_naissance' => $data['date_naissance'],
            ':formule' => $data['formule'],
            ':date_soumission' => $data['date_soumission'],
            ':date_effet_souhaitee' => $data['date_effet_souhaitee'],
            ':commentaires' => $data['commentaires'] ?? null
        ]);

        return $this->db->lastInsertId();
    }

    private function insererDetailsSpecifiques($demandeId, $data) {
        switch ($data['type_assurance']) {
            case 'Auto':
                $this->insererDetailsAuto($demandeId, $data);
                break;
            case 'Habitation':
                $this->insererDetailsHabitation($demandeId, $data);
                break;
            case 'Santé':
                $this->insererDetailsSante($demandeId, $data);
                break;
        }
    }

    private function insererDetailsAuto($demandeId, $data) {
        $sql = "INSERT INTO details_auto (
                    demande_id, marque_vehicule, modele_vehicule,
                    annee_vehicule, immatriculation
                ) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $demandeId,
            $data['marque_vehicule'],
            $data['modele_vehicule'],
            $data['annee_vehicule'],
            $data['immatriculation']
        ]);
    }

    private function insererDetailsHabitation($demandeId, $data) {
        $sql = "INSERT INTO details_habitation (
                    demande_id, type_logement, superficie, adresse_bien
                ) VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $demandeId,
            $data['type_logement'],
            $data['superficie'],
            $data['adresse_bien']
        ]);
    }

    private function insererDetailsSante($demandeId, $data) {
        $sql = "INSERT INTO details_sante (
                    demande_id, situation_familiale,
                    nombre_beneficiaires, profession
                ) VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $demandeId,
            $data['situation_familiale'],
            $data['nombre_beneficiaires'],
            $data['profession']
        ]);
    }

    public function getAllDemandes($start = 0, $limit = 10) {
        try {
            $sql = "SELECT id, reference, nom_client, prenom_client, email, telephone, type_assurance, formule, date_soumission 
                    FROM demandes_assurance 
                    ORDER BY date_soumission DESC 
                    LIMIT :start, :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':start', $start, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur dans getAllDemandes : " . $e->getMessage());
            return [];
        }
    }
    
    public function getDemandesLength() {
        try {
            $sql = "SELECT COUNT(*) as total FROM demandes_assurance";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Erreur dans getDemandesLength : " . $e->getMessage());
            return 0;
        }
    }

 
public function deleteDemande($id) {
    try {
        // Suppression des pièces jointes associées
        $sql = "SELECT chemin_fichier FROM pieces_jointes WHERE demande_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $files = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($files as $file) {
            if (file_exists($file['chemin_fichier'])) {
                unlink($file['chemin_fichier']);
            }
        }

        // Suppression des pièces jointes dans la base de données
        $sql = "DELETE FROM pieces_jointes WHERE demande_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        // Suppression des détails spécifiques (Auto, Habitation, Santé)
        $sql = "DELETE FROM details_auto WHERE demande_id = :id;
                DELETE FROM details_habitation WHERE demande_id = :id;
                DELETE FROM details_sante WHERE demande_id = :id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        // Suppression de la demande principale
        $sql = "DELETE FROM demandes_assurance WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return true;
    } catch (Exception $e) {
        error_log("Erreur dans deleteDemande : " . $e->getMessage());
        return false;
    }
}

public function getDemandeById($id) {
    try {
        $sql = "SELECT * FROM demandes_assurance WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Erreur dans getDemandeById : " . $e->getMessage());
        return false;
    }
}
public function updateDemande($id, $data) {
    try {
        $sql = "UPDATE demandes_assurance 
                SET reference = :reference, type_assurance = :type_assurance, 
                    nom_client = :nom_client, prenom_client = :prenom_client, 
                    email = :email, telephone = :telephone, 
                    date_naissance = :date_naissance, formule = :formule, 
                    date_effet_souhaitee = :date_effet_souhaitee, 
                    commentaires = :commentaires 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':reference' => $data['reference'],
            ':type_assurance' => $data['type_assurance'],
            ':nom_client' => $data['nom_client'],
            ':prenom_client' => $data['prenom_client'],
            ':email' => $data['email'],
            ':telephone' => $data['telephone'],
            ':date_naissance' => $data['date_naissance'],
            ':formule' => $data['formule'],
            ':date_effet_souhaitee' => $data['date_effet_souhaitee'],
            ':commentaires' => $data['commentaires'] ?? null,
            ':id' => $id
        ]);

        return true;
    } catch (Exception $e) {
        error_log("Erreur dans updateDemande : " . $e->getMessage());
        return false;
    }
}
    private function uploadPiecesJointes($demandeId, $files) {
        foreach ($files['name'] as $key => $name) {
            if ($files['error'][$key] === 0) {
                $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
                    throw new Exception("Le format du fichier $name n'est pas autorisé");
                }

                if ($files['size'][$key] > self::MAX_FILE_SIZE) {
                    throw new Exception("Le fichier $name dépasse la taille maximale autorisée");
                }

                $newName = uniqid() . '_' . $name;
                $destination = $this->uploadDir . $newName;

                if (move_uploaded_file($files['tmp_name'][$key], $destination)) {
                    $sql = "INSERT INTO pieces_jointes (
                                demande_id, nom_fichier, chemin_fichier,
                                type_fichier, taille
                            ) VALUES (?, ?, ?, ?, ?)";

                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([
                        $demandeId,
                        $name,
                        $destination,
                        $files['type'][$key],
                        $files['size'][$key]
                    ]);
                }
            }
        }
    }
}
?>