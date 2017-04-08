<?php

namespace App\system\databases;
class  MigrationFilter implements \App\system\FileFilter {
    private $migrationName;

    function __construct($migrationName) {
        $this->migrationName = $migrationName;
    }

    /**
     * @param \SplFileInfo $file
     *
     * @return bool
     */
    public function filter(\SplFileInfo $file) {
        if ($file->isDir()) {
            return TRUE;
        } elseif ($file->getBasename(".php") == $this->migrationName) {
            return TRUE;
        } else {
            return FALSE;
        }
        // TODO: Implement filter() method.
    }
}