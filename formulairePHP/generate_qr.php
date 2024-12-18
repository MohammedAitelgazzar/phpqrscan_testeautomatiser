<?php
session_start();

// Récupération des données du formulaire
$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$email = $_POST['email'] ?? '';
$telephone = $_POST['telephone'] ?? '';

// Gestion de l'upload de l'image
$image_path = '';
if(isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    $temp_name = $_FILES['photo']['tmp_name'];
    $original_name = $_FILES['photo']['name'];
    $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    
    if(in_array($extension, $allowed_extensions)) {
        $new_filename = uniqid() . '.' . $extension;
        $destination = $upload_dir . $new_filename;
        
        if(move_uploaded_file($temp_name, $destination)) {
            $image_path = $destination;
        }
    }
}

// Création du texte pour le QR code
$qrText = "Nom: $nom\nPrénom: $prenom\nEmail: $email\nTéléphone: $telephone";

// URL de l'API GoQR.me pour le QR Code
$qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrText);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Résultat et QR Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }
        .container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .data-section, .qr-section {
            width: 45%;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .data-item {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
        }
        img {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
        }
        .user-photo {
            max-width: 200px;
            border-radius: 8px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <h2>Informations et QR Code</h2>
    
    <div class="container">
        <div class="data-section">
            <h3>Données saisies :</h3>
            <?php if($image_path): ?>
                <div class="data-item">
                    <span class="label">Photo :</span><br>
                    <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Photo utilisateur" class="user-photo">
                </div>
            <?php endif; ?>
            <div class="data-item">
                <span class="label">Nom:</span> <?php echo htmlspecialchars($nom); ?>
            </div>
            <div class="data-item">
                <span class="label">Prénom:</span> <?php echo htmlspecialchars($prenom); ?>
            </div>
            <div class="data-item">
                <span class="label">Email:</span> <?php echo htmlspecialchars($email); ?>
            </div>
            <div class="data-item">
                <span class="label">Téléphone:</span> <?php echo htmlspecialchars($telephone); ?>
            </div>
        </div>
        
        <div class="qr-section">
            <h3>QR Code :</h3>
            <img src="<?php echo $qrCodeUrl; ?>" alt="QR Code">
        </div>
    </div>
    
    <p><a href="index.php">Retour au formulaire</a></p>
</body>
</html>
