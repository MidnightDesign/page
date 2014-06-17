<?php

namespace Midnight\Page\Storage;

use InvalidArgumentException;
use Midnight\Block\Storage\StorageInterface as BlockStorageInterface;
use Midnight\Page\PageInterface;

class Filesystem extends AbstractStorage implements StorageInterface
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var BlockStorageInterface
     */
    private $blockStorage;

    function __construct($directory, BlockStorageInterface $blockStorage)
    {
        $this->blockStorage = $blockStorage;
        $this->setDirectory($directory);
    }

    /**
     * @param PageInterface $page
     *
     * @return void
     */
    public function save(PageInterface $page)
    {
        $this->ensureSlug($page);
        $id = $page->getId();
        if (!$id) {
            $page->setId(uniqid());
            $id = $page->getId();
        }
        foreach ($page->getBlocks() as $block) {
            $this->blockStorage->save($block);
        }
        file_put_contents($this->buildPath($id), serialize($page));
    }

    /**
     * @param string $id
     *
     * @return string
     */
    private function buildPath($id)
    {
        //@codeCoverageIgnoreStart
        if (!$id) {
            throw new InvalidArgumentException('No ID given.');
        }
        //@codeCoverageIgnoreEnd
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
        if (!file_exists($directory)) {
            @mkdir($directory, 0777, true);
        }
        if (!file_exists($directory)) {
            throw new \RuntimeException(sprintf('Couldn\'t create "%s".', $directory));
        }
        if (!is_readable($directory)) {
            throw new \RuntimeException(sprintf('"%s" is not readable.', $directory));
        }
        if (!is_writable($directory)) {
            throw new \RuntimeException(sprintf('"%s" is not writable.', $directory));
        }
        $directory = realpath(($directory));
        $this->directory = $directory;
    }

    /**
     * @param string $id
     *
     * @return PageInterface
     */
    public function load($id)
    {
        $path = $this->buildPath($id);
        if (!file_exists($path)) {
            return null;
        }
        /** @var PageInterface $page */
        $page = unserialize(file_get_contents($path));

        $blocks = array();
        foreach ($page->getBlocks() as $block) {
            $blocks[] = $this->blockStorage->load($block->getId());
        }
        $pageRefl = new \ReflectionObject($page);
        //@codeCoverageIgnoreStart
        if ($pageRefl->hasProperty('blocks')) {
            $blocksRefl = $pageRefl->getProperty('blocks');
            $blocksRefl->setAccessible(true);
            $blocksRefl->setValue($page, $blocks);
        }
        //@codeCoverageIgnoreEnd

        return $page;
    }

    /**
     * @return PageInterface[]
     */
    public function getAll()
    {
        $pages = array();
        $dir = $this->getDirectory();
        $handle = opendir($dir);
        while ($file = readdir($handle)) {
            if (is_file($dir . DIRECTORY_SEPARATOR . $file)) {
                $pages[] = $this->load($file);
            }
        }
        return $pages;
    }

    /**
     * @param PageInterface $page
     *
     * @return void
     */
    public function delete(PageInterface $page)
    {
        $path = $this->buildPath($page->getId());
        unlink($path);
        //@codeCoverageIgnoreStart
        if (file_exists($path)) {
            throw new \RuntimeException(sprintf('Could not delete %s', $path));
        }
        //@codeCoverageIgnoreEnd
    }

    /**
     * @param string $slug
     *
     * @return PageInterface
     */
    public function loadBySlug($slug)
    {
        foreach ($this->getAll() as $page) {
            if ($page->getSlug() === $slug) {
                return $page;
            }
        }
        return null;
    }
}
