<?php

namespace Midnight\Page\Storage;

use Midnight\Page\PageInterface;
use Midnight\Page\Util\Urlizer;

abstract class AbstractStorage implements StorageInterface
{
    /**
     * @param PageInterface $page
     * @param int           $suffix
     */
    protected function ensureSlug(PageInterface $page, $suffix = 0)
    {
        if (!$page->getSlug()) {
            $name = $page->getName();
            if ($suffix > 1) {
                $name .= ' ' . $suffix;
            }
            $slug = Urlizer::urlize($name);
            $pages = $this->getAll();
            foreach ($pages as $p) {
                if ($p->getSlug() === $slug) {
                    $this->ensureSlug($page, $suffix + 1);
                    return;
                }
            }
            $page->setSlug($slug);
        }
    }
}
