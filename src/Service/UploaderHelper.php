<?php


namespace App\Service;


use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{

    public const GIFT_IMAGE = 'gift';

    private $filesystem;

    private $logger;

    public function __construct(
        FilesystemInterface $publicUploadsFilesystem,
        LoggerInterface $logger
    ) {
        $this->filesystem = $publicUploadsFilesystem;
        $this->logger = $logger;
    }

    public function uploadImage(File $file, string $type, ?string $existingFilename): string
    {
        if ($file instanceof UploadedFile) {
            $originalFilename = $file->getClientOriginalName();
        } else {
            $originalFilename = $file->getFilename();
        }

        $newFilename = Urlizer::urlize(pathinfo($originalFilename, PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $file->guessExtension();

        $stream = fopen($file->getPathname(), 'r');
        $result = $this->filesystem->writeStream(
            $type .'/'. $newFilename,
            $stream
        );

        if ($result === false) {
            throw new \Exception(sprintf('Could not write uploaded file "%s"', $newFilename));
        }

        if (is_resource($stream)) {
            fclose($stream);
        }

        if ($existingFilename) {
            try {
                $result = $this->filesystem->delete($type . '/' . $existingFilename);
                if ($result === false) {
                    throw new \Exception(sprintf('Could not delete old uploaded file "%s"', $existingFilename));
                }
            } catch (FileNotFoundException $e) {
                $this->logger->alert(sprintf('Old uploaded file "%s" was missing when trying to delete', $existingFilename));
            }

        }

        return $newFilename;
    }

    public function deleteImage(string $filename, string $type): void
    {
        try {
            $result = $this->filesystem->delete($type . '/' . $filename);
            if ($result === false) {
                throw new Exception(sprintf('Could not delete old uploaded file "%s"', $filename));
            }
        } catch (FileNotFoundException $e) {
            $this->logger->alert(sprintf('Old uploaded file "%s" was missing when trying to delete', $filename));
        }
    }

    public function getPublicPath(string $path): string
    {
        return 'uploads/' . $path;
    }

}