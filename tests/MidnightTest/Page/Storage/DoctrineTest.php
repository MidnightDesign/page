<?php

namespace MidnightTest\Page\Storage;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Midnight\Page\PageInterface;
use Midnight\Page\Storage\Doctrine;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class DoctrineTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Doctrine
     */
    private $storage;
    /**
     * @var PageInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $page;
    /**
     * @var ObjectManager|PHPUnit_Framework_MockObject_MockObject
     */
    private $objectManager;
    /**
     * @var ObjectRepository|PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;
    /**
     * @var string
     */
    private $slug = 'myPageSlug';

    public function setUp()
    {
        $this->page = $this->getMock('Midnight\Page\PageInterface');

        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->repository
            ->expects($this->any())
            ->method('findAll')
            ->will($this->returnValue(array($this->page)));

        $this->objectManager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->objectManager
            ->expects($this->any())
            ->method('getRepository')
            ->with('Midnight\Page\Page')
            ->will($this->returnValue($this->repository));

        $this->storage = new Doctrine();
        $this->storage->setObjectManager($this->objectManager);
    }

    public function testSave()
    {
        $this->objectManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->page);
        $this->objectManager
            ->expects($this->once())
            ->method('flush');
        $this->storage->save($this->page);
    }

    public function testLoad()
    {
        $page = $this->page;
        $storage = $this->storage;
        $storage->save($page);

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($page->getId())
            ->will($this->returnValue($this->page));

        $this->assertInstanceOf('Midnight\Page\PageInterface', $storage->load($page->getId()));
    }

    public function testDelete()
    {
        $page = $this->page;
        $objectManager = $this->objectManager;
        $objectManager
            ->expects($this->once())
            ->method('remove')
            ->with($page);
        $objectManager
            ->expects($this->once())
            ->method('flush');
        $this->storage->delete($page);
    }

    public function testLoadBySlug()
    {
        $page = $this->page;
        $this->repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(array('slug' => $this->slug))
            ->will($this->returnValue($page));
        $this->assertSame($page, $this->storage->loadBySlug($this->slug));
    }
}
