<?php
// Fonction pour trouver l'image associée aux informations
function findUserImage() {
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

    return $latestImage ? $uploadsDir . $latestImage : '';
}

// Retourner le chemin de l'image
echo findUserImage();
?>
