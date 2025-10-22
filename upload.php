<?php
require 's3-config.php';

$fileName = 'example.txt'; // file to upload
$filePath = '/var/www/html/NewPortfolio2/example.txt'; // path to your file

try {
    $result = $s3->putObject([
        'Bucket' => $bucket,
        'Key'    => $fileName,
        'SourceFile' => $filePath,
        'ACL'    => 'public-read', // optional
    ]);

    echo "✅ File uploaded successfully! File URL: " . $result['ObjectURL'];

} catch (Aws\Exception\AwsException $e) {
    echo "❌ Upload failed: " . $e->getMessage();
}
?>
