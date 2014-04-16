<?php

namespace Midnight\Page\Storage;

use Midnight\Page\PageInterface;

class Filesystem implements StorageInterface
{
    /**
     * @var string
     */
    private $directory;

    function __construct($directory)
    {
        $this->setDirectory($directory);
    }

    /**
     * @param PageInterface $page
     * @return void
     */
    public function save(PageInterface $page)
    {
        $id = $page->getId();
        if (!$id) {
            $page->setId(uniqid());
            $id = $page->getId();
        }
        file_put_contents($this->buildPath($id), serialize($page));
    }

    /**
     * @param string $id
     * @return string
     */
    private function buildPath($id)
    {
        return $this->getDirectory() . DIRECTORY_SEPARATOR . $id;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     */
    public function setDirectory($directory)
    {
        $directory = realpath($directory);
        $this->directory = $directory;
    }

    /**
     * @param string $id
     * @return PageInterface
     */
    public function load($id)
    {
        return unserialize(file_get_contents($this->buildPath($id)));
    }
}