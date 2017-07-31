<?php

namespace Shcode\Stapler\Factories;

use Google\Cloud\Storage\StorageClient;
use Shcode\Stapler\Attachment as AttachedFile;
use Codesleeve\Stapler\Storage\Filesystem;
use Codesleeve\Stapler\Storage\S3;
use Shcode\Stapler\Storage\GCS;
use Codesleeve\Stapler\Stapler;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

class Storage
{
    /**
     * Build a storage instance.
     *
     * @param AttachedFile $attachment
     *
     * @return \Codesleeve\Stapler\Storage\StorageableInterface
     */
    public static function create(AttachedFile $attachment)
    {
        switch ($attachment->storage) {
            case 'filesystem':
                return new Filesystem($attachment);
                break;

            case 's3':
                $s3Client = Stapler::getS3ClientInstance($attachment);

                return new S3($attachment, $s3Client);
                break;

            case 'gcs':
                $storageClient = new StorageClient([
                    'projectId' => $attachment->google_cloud_project_id,
                    'keyFilePath' => $attachment->google_cloud_key_file,
                ]);

                $bucket = $storageClient->bucket($attachment->gcs_bucket);
                $adapter = new GoogleStorageAdapter($storageClient, $bucket);
                $filesystem = new \League\Flysystem\Filesystem($adapter);
                return new GCS($attachment, $filesystem);
                break;

            default:
                return new Filesystem($attachment);
                break;
        }
    }
}
