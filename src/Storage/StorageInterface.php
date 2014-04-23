<?php

namespace Midnight\Page\Storage;

use Midnight\Page\PageInterface;

interface StorageInterface
{
    /**
     * @param PageInterface $page
     *
     * @return void
     */
    public function save(PageInterface $page);

    /**
     * @param string $id
     *
     * @return PageInterface
     */
    public function load($id);

    /**
     * @return PageInterface[]
     */
    public function getAll();
}