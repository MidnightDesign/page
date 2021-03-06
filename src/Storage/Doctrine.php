<?php

namespace Midnight\Page\Storage;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Midnight\Page\PageInterface;

/**
 * Class Doctrine
 * @package Midnight\Page\Storage
 */
class Doctrine extends AbstractStorage implements StorageInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var string
     */
    private $className = 'Midnight\Page\Page';

    /**
     * @param ObjectManager $objectManager
     */
    public function setObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param PageInterface $page
     *
     * @return void
     */
    public function save(PageInterface $page)
    {
        $this->ensureSlug($page);
        $objectManager = $this->getObjectManager();
        $objectManager->persist($page);
        $objectManager->flush();
    }

    /**
     * @return ObjectManager
     */
    private function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * @param string $id
     *
     * @return PageInterface
     */
    public function load($id)
    {
        $page = $this->getRepository()->find($id);
        return $page;
    }

    /**
     * @return PageInterface[]
     */
    public function getAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @return ObjectRepository
     */
    private function getRepository()
    {
        return $this->getObjectManager()->getRepository($this->className);
    }

    /**
     * @param PageInterface $page
     *
     * @return void
     */
    public function delete(PageInterface $page)
    {
        $objectManager = $this->getObjectManager();
        $objectManager->remove($page);
        $objectManager->flush();
    }

    /**
     * @param string $slug
     *
     * @return PageInterface
     */
    public function loadBySlug($slug)
    {
        return $this->getRepository()->findOneBy(array('slug' => $slug));
    }
}
