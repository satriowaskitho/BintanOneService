<?php
// download_models.php
$modelsUrl = 'https://raw.githubusercontent.com/justadudewhohacks/face-api.js/master/weights/';
$files = [
    'ssd_mobilenetv1_model-weights_manifest.json',
    'ssd_mobilenetv1_model-shard1',
    'ssd_mobilenetv1_model-shard2',
    'face_landmark_68_model-weights_manifest.json',
    'face_landmark_68_model-shard1',
    'face_recognition_model-weights_manifest.json',
    'face_recognition_model-shard1',
    'face_recognition_model-shard2',
];

$dir = __DIR__ . '/public/models';
if (!is_dir($dir)) mkdir($dir, 0777, true);

foreach($files as $file) {
    if(!file_exists($dir.'/'.$file)) {
        file_put_contents($dir.'/'.$file, fopen($modelsUrl.$file, 'r'));
        echo "Downloaded: $file\n";
    }
}
echo "Models downloaded successfully.\n";
