<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// S3 configuration
$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'ap-southeast-2', // Your bucket region
    'credentials' => [
        'key'    => 'AKIAX36PVLHDHVHI4ZMK',
        'secret' => 'f3gDq512ysg6SJ7mMxR08P7O1L7WCzmDQf6gK4ps',
    ]
]);

$bucket = 'portfolioandrew123'; // your S3 bucket name
?>
