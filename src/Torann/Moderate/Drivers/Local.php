<?php

namespace Torann\Moderate\Drivers;

use Exception;

class Local extends AbstractDriver
{
    /**
     * {@inheritdoc}
     */
    public function getList()
    {
        $path = $this->getConfig('path');

        // Multiple locale support by appending the
        // current locale key to blacklist filename.
        if ($this->getLocal() !== null) {
            $path = preg_replace('/(\.[^.]+)$/', sprintf('_%s$1', $this->getLocal()), $path);
        }

        if (file_exists($path) === false) {
            // Ignore missing blacklist files
            if ($this->getConfig('ignore_missing', true)) {
                return [];
            }

            throw new Exception("Black list file is missing [{$path}]");
        }

        return json_decode(file_get_contents($path), true);
    }
}
