<?php

namespace Dam\Core\FileSystem;

use Dam\Core\Tika;
use Ximdex\Core\FileSystem\FileSystem as XFileSystem;

class FileSystem extends XFileSystem
{
    /**
     * Undocumented function
     *
     * @param callable $callback
     * @param string $path
     * @return void
     */
    public function findFiles(callable $callback, string $path = ''): void
    {
        foreach ($this->getIn($path) as $file) {
            if (!$this->isFile($file)) {
                $this->findFiles($callback, $file);
                continue;
            }

            $callback($this->filedata($file));
        }
    }

    public function fileData(string $path)
    {
        $tmp = $this->tmp($path);
        $tika = (new Tika)->setFile($tmp)->getMetadata();
        foreach ($tika as $key => $value) {
            if (in_array($key, ['created_at', 'updated_at']) && is_null($value)) {
                unset($tika[$key]);
            }
        }
        \Storage::delete($tmp);
        return array_merge(parent::fileData($path), $tika);
    }
}
