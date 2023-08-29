<?php

namespace App\Components\Storage;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserProfileImageUploader
{
    public function __construct(
        #[Autowire(param: 'upload_folder')]
        private string $targetDirectory,
        private SluggerInterface $slugger
    ) {
    }

    public function upload(UploadedFile $file, ?string $oldFileName): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        $oldFileNamePath = $this->getTargetDirectory() . '/' . $oldFileName;

        try {
            $file->move($this->getTargetDirectory(), $fileName);
            if ($oldFileName) unlink($this->getTargetDirectory() . '/' . $oldFileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}