<?php

namespace Shcode\Stapler;

use Codesleeve\Stapler\Exceptions\InvalidUrlOptionException;
use Codesleeve\Stapler\Interfaces\Validator as ValidatorInterface;

class Validator implements ValidatorInterface
{
    /**
     * Validate the attachment options for an attachment type.
     * A url is required to have either an :id or an :id_partition interpolation.
     *
     * @param array $options
     */
    public function validateOptions(array $options)
    {
        switch ($options['storage']) {
            case 's3':
                $this->validateS3Options($options);
                break;
            case 'gcs':
                $this->validateGCSOptions($options);
                break;
            case 'filesystem':
            default:
                $this->validateFilesystemOptions($options);
        }
    }

    /**
     * Validate the attachment options for an attachment type when the storage
     * driver is set to 'filesystem'.
     *
     * @throws InvalidUrlOptionException
     *
     * @param array $options
     */
    protected function validateFilesystemOptions(array $options)
    {
        if (preg_match("/:id\b/", $options['url']) !== 1 && preg_match("/:id_partition\b/", $options['url']) !== 1 && preg_match("/:(secure_)?hash\b/", $options['url']) !== 1) {
            throw new InvalidUrlOptionException('Invalid Url: an id, id_partition, hash, or secure_hash interpolation is required.', 1);
        }
    }

    /**
     * Validate the attachment options for an attachment type when the storage
     * driver is set to 's3'.
     *
     * @throws InvalidUrlOptionException
     *
     * @param array $options
     */
    protected function validateS3Options(array $options)
    {
        if (!$options['s3_object_config']['Bucket']) {
            throw new InvalidUrlOptionException('Invalid Path: a bucket is required for s3 storage.', 1);
        }

        if (!$options['s3_client_config']['secret']) {
            throw new InvalidUrlOptionException('Invalid Path: a secret is required for s3 storage.', 1);
        }

        if (!$options['s3_client_config']['key']) {
            throw new InvalidUrlOptionException('Invalid Path: a key is required for s3 storage.', 1);
        }
    }

    /**
     * Validate the attachment options for an attachment type when the storage
     * driver is set to 's3'.
     *
     * @throws InvalidUrlOptionException
     *
     * @param array $options
     */
    protected function validateGCSOptions(array $options)
    {
        if (!$options['google_cloud_project_id']) {
            throw new InvalidUrlOptionException('Invalid Path: a google project id is required for gcs storage.', 1);
        }

        if (!$options['google_cloud_key_file']) {
            throw new InvalidUrlOptionException('Invalid Path: a google key file is required for gcs storage.', 1);
        }

        if (!$options['gcs_bucket']) {
            throw new InvalidUrlOptionException('Invalid Path: a bucket is required for gcs storage.', 1);
        }
    }

}
