<?php

namespace Neos\classes\Jobs;

class ClearCacheProductCard extends BaseJob
{
    public function execute()
    {
        if (!$this->params['id']) {
            $this->setStatus(4,'Wrong params passed');
        }
        $dir = NPATH_CACHE . '/product/' . (int) $this->params['id'];
        if (is_dir($dir)) {
            $files = glob($dir . '/*');
            $count = count($files);
            foreach($files as $file) {
                unlink($file);
            }
            if (rmdir($dir)) {
                $this->setStatus(3, 'Files found: ' . $count);
            } else {
                $this->setStatus(4, 'Couldn\'t remove directory');
            }
            return true;
        }

        $this->setStatus(4, 'Directory not found');
    }
}