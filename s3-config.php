
<?php
declare(strict_types=1);

/**
 * Requires AWS SDK:
 *   composer require aws/aws-sdk-php
 *
 * Uses default credential provider chain (EC2 Instance Profile/IAM role, or env).
 * ENV:
 *   AWS_REGION (e.g., ap-southeast-1)
 *   S3_BUCKET  (bucket name where messages are backed up as JSON)
 */

require_once __DIR__ . '/vendor/autoload.php';

use Aws\S3\S3Client;

$awsRegion = getenv('AWS_REGION') ?: 'ap-southeast-1';
$s3Bucket  = getenv('S3_BUCKET') ?: null;

$s3 = new S3Client([
  'version' => 'latest',
  'region'  => $awsRegion,
  // No explicit credentials: SDK will use IAM role / env if present
]);

/**
 * Store JSON data as an object if $s3Bucket is set.
 * $key example: "contact-messages/2025/10/24/123.json"
 */
function s3_put_json(?S3Client $s3, ?string $bucket, string $key, array $data): void {
  if (!$s3 || !$bucket) return;
  $s3->putObject([
    'Bucket'      => $bucket,
    'Key'         => $key,
    'Body'        => json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
    'ContentType' => 'application/json; charset=utf-8',
  ]);
}
