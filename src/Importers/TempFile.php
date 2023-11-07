<?php

namespace Dcat\EasyExcel\Importers;

use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;

trait TempFile
{
    protected $tempFolder;

    protected $tempFile;

    /**
     * @param  FilesystemOperator  $filesystem
     * @param  string  $filePath
     * @return string
     *
     * @throws FilesystemException
     */
    public function moveFileToTemp($filesystem, string $filePath)
    {
        $this->tempFile = $this->generateTempPath($filePath);

        file_put_contents($this->tempFile, $filesystem->read($filePath));

        return $this->tempFile;
    }

    protected function removeTempFile()
    {
        if ($this->tempFile && is_file($this->tempFile)) {
            @unlink($this->tempFile);
        }
    }

    /**
     * @param  string  $filePath
     * @return string
     */
    private function generateTempPath(string $filePath)
    {
        $extension = pathinfo($filePath)['extension'] ?? null;

        return $this->getTempFolder()
            .'/'
            .uniqid(microtime(true).static::generateRandomString())
            .($extension ? ".{$extension}" : '');
    }

    /**
     * @return string
     */
    private function getTempFolder()
    {
        return sys_get_temp_dir();
    }
}
