<?php
session_start();

// Fonction pour trouver l'image associée aux informations
function findUserImage($nom, $prenom) {
    $uploadsDir = 'uploads/';
    $files = scandir($uploadsDir);
    $latestImage = null;
    $latestTime = 0;

    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {
            $filePath = $uploadsDir . $file;
            $fileTime = filemtime($filePath);
            if ($fileTime > $latestTime) {
                $latestTime = $fileTime;
                $latestImage = $file;
            }
        }
    }

    return $latestImage ? $uploadsDir . $latestImage : null;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Scanner QR Code</title>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        #reader {
            width: 400px;
            margin: 20px auto;
        }
        #result {
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            display: none;
            width: 100%;
        }
        .info-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }
        .info-details {
            flex: 1;
        }
        .info-item {
            margin: 10px 0;
        }
        .label {
            font-weight: bold;
        }
        #fileSelector {
            margin: 20px 0;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 10px 0;
        }
        .button:hover {
            background-color: #45a049;
        }
        .user-image {
            max-width: 200px;
            border-radius: 8px;
            margin-left: 20px;
        }
        .image-container {
            flex: 0 0 200px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Scanner QR Code</h2>
        
        <!-- Option pour uploader une image de QR code -->
        <div>
            <input type="file" id="fileSelector" accept="image/*">
        </div>

        <!-- Div pour le scanner de QR code -->
        <div id="reader"></div>

        <!-- Résultats -->
        <div id="result">
            <h3>Informations du QR Code:</h3>
            <div class="info-container">
                <div class="info-details" id="resultContent"></div>
                <div class="image-container">
                    <img id="userImage" class="user-image" style="display: none;">
                </div>
            </div>
        </div>
    </div>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Arrêter le scanner après une lecture réussie
            html5QrcodeScanner.clear();
            displayResult(decodedText);
        }

        function displayResult(text) {
            const resultDiv = document.getElementById('result');
            const resultContent = document.getElementById('resultContent');
            resultDiv.style.display = 'block';

            // Convertir le texte en format lisible
            const lines = text.split('\n');
            let formattedHtml = '';
            let nom = '';
            let prenom = '';
            
            lines.forEach(line => {
                if (line.includes(':')) {
                    const [label, value] = line.split(':');
                    if (label.trim() === 'Nom') nom = value.trim();
                    if (label.trim() === 'Prénom') prenom = value.trim();
                    
                    formattedHtml += `
                        <div class="info-item">
                            <span class="label">${label.trim()}:</span>
                            <span>${value.trim()}</span>
                        </div>`;
                }
            });

            resultContent.innerHTML = formattedHtml;

            // Faire une requête AJAX pour obtenir l'image
            fetch(`get_user_image.php?nom=${encodeURIComponent(nom)}&prenom=${encodeURIComponent(prenom)}`)
                .then(response => response.text())
                .then(imagePath => {
                    const userImage = document.getElementById('userImage');
                    if (imagePath) {
                        userImage.src = imagePath;
                        userImage.style.display = 'block';
                    }
                });
        }

        // Configuration du scanner
        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 10,
                qrbox: {width: 250, height: 250},
                experimentalFeatures: {
                    useBarCodeDetectorIfSupported: true
                }
            }
        );
        html5QrcodeScanner.render(onScanSuccess);

        // Gestion de l'upload de fichier
        document.getElementById('fileSelector').addEventListener('change', event => {
            const file = event.target.files[0];
            if (!file) {
                return;
            }

            const html5QrCode = new Html5Qrcode("reader");
            html5QrCode.scanFile(file, true)
                .then(decodedText => {
                    displayResult(decodedText);
                })
                .catch(err => {
                    console.error("Erreur lors de la lecture du QR code:", err);
                    alert("Erreur lors de la lecture du QR code. Veuillez réessayer avec une autre image.");
                });
        });
    </script>

    <div style="text-align: center; margin-top: 20px;">
        <a href="index.php" class="button">Retour au formulaire</a>
    </div>
</body>
</html>
