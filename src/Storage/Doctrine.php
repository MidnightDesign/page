<?php

namespace Midnight\Page\Storage;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Midnight\Page\PageInterface;
use RuntimeException;

/**
 * Class Doctrine
 * @package Midnight\Page\Storage
 *
 * BROKEN!
 */
class Doctrine implements StorageInterface
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var string
     */
    private $className;

    /**
     * @param PageInterface $page
     *
     * @return void
     */
    public function save(PageInterface $page)
    {
        $documentManager = $this->getObjectManager();
        $documentManager->persist($page);
        $documentManager->flush();
    }

    /**
     * @throws RuntimeException
     * @return ObjectRepository
     */
    public function getRepository()
    {
        $className = $this->getClassName();
        if (!$className) {
            throw new RuntimeException('No target class set.');
        }
        return $this->getObjectManager()->getRepository($className);
    }

    /**
     * @throws RuntimeException
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        $objectManager = $this->objectManager;
        if (!$objectManager) {
            throw new RuntimeException('No object manager set.');
        }
        return $objectManager;
    }

    /**
     * @param ObjectManager $objectManager
     */
    public function setObjectManager($objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }

    /**
     * @param string $id
     *
     * @return PageInterface
     */
    public function load($id)
    {
        return $this->getRepository()->find($id);
    }
}