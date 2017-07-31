<?php

namespace Shcode\Stapler\Storage;

use Codesleeve\Stapler\Interfaces\Storage as StorageInterface;
use Shcode\Stapler\Attachment;
use League\Flysystem\Filesystem;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

class GCS implements StorageInterface
{
    /**
     * The current attachedFile object being processed.
     *
     * @var \Codesleeve\Stapler\Attachment
     */
    public $attachedFile;

    /**
     * The AWS S3Client instance.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Constructor method.
     *
     * @param Attachment $attachedFile
     * @param GoogleStorageAdapter   $gcsAdapter
     */
    public function __construct(Attachment $attachedFile, Filesystem $filesystem)
    {
        $this->attachedFile = $attachedFile;
        $this->filesystem = $filesystem;
    }

    /**
     * Return the url for a file upload.
     *
     * @param string $styleName
     *
     * @return string
     */
    public function url($styleName)
    {
        return $this->filesystem->getAdapter()->getUrl($this->path($styleName));
    }

    /**
     * Return the key the uploaded file object is stored under within a bucket.
     *
     * @param string $styleName
     *
     * @return string
     */
    public function path($styleName)
    {
        return $this->attachedFile->getInterpolator()->interpolate($this->attachedFile->path, $this->attachedFile, $styleName);
    }

    /**
     * Remove an attached file.
     *
     * @param array $filePaths
     */
    public function remove(array $filePaths)
    {
        if ($filePaths) {
            $this->filesystem->delete($filePaths);
        }
    }

    /**
     * Move an uploaded file to it's intended destination.
     *
     * @param string $file
     * @param string $filePath
     */
    public function move($file, $filePath)
    {
        $this->filesystem->put($filePath, fopen($file, 'r+'));
    }
}
