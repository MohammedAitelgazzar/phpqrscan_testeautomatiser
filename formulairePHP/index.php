<?php
session_start();

// Créer le dossier uploads s'il n'existe pas
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Formulaire de Contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="file"] {
            padding: 10px 0;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .preview-image {
            max-width: 200px;
            margin-top: 10px;
            display: none;
        }
    </style>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</head>
<body>
    <h2>Formulaire de Contact</h2>
    <form action="generate_qr.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        
        <div class="form-group">
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="telephone">Téléphone:</label>
            <input type="tel" id="telephone" name="telephone" required>
        </div>

        <div class="form-group">
            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*" onchange="previewImage(this)">
            <img id="imagePreview" src="#" alt="Aperçu de l'image" class="preview-image">
        </div>
        
        <input type="submit" value="Générer QR Code">
    </form>
</body>
</html>
