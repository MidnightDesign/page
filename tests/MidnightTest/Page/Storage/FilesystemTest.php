<?php

namespace MidnightTest\Page\Storage;

use Midnight\Block\BlockInterface;
use Midnight\Block\Storage\StorageInterface;
use Midnight\Page\Page;
use Midnight\Page\PageInterface;
use Midnight\Page\Storage\Filesystem;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use RuntimeException;

class FilesystemTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private static $directory;
    /**
     * @var Filesystem
     */
    private $storage;
    /**
     * @var StorageInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $blockStorage;
    /**
     * @var PageInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $page;
    /**
     * @var BlockInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $block;
    /**
     * @var string
     */
    private $pageId = 'myPageId';

    public static function setUpBeforeClass()
    {
        set_error_handler(function () {
            // Ignore
        });
        self::$directory = __DIR__ . '/generated';
        mkdir(self::$directory);
    }

    public static function tearDownAfterClass()
    {
        self::delDir(self::$directory);
        restore_error_handler();
    }

    public function setUp()
    {
        $this->blockStorage = $this->getMock('Midnight\Block\Storage\StorageInterface');

        $this->storage = new Filesystem(self::$directory, $this->blockStorage);

        $this->block = $this->getMock('Midnight\Block\BlockInterface');

        $this->page = $this->getMock('Midnight\Page\PageInterface');
        $this->page
            ->expects($this->any())
            ->method('getBlocks')
            ->will($this->returnValue([$this->block]));
        $this->page
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($this->pageId));
    }

    public function tearDown()
    {
        self::emptyDir(self::$directory);
        chmod(self::$directory, 0777);
    }

    public function testSave()
    {
        $this->storage->save($this->page);
    }

    public function testIdIsAutoGenerated()
    {
        $this->page = $this->getMock('Midnight\Page\PageInterface');
        $this->page
            ->expects($this->exactly(2))
            ->method('getId')
            ->will($this->onConsecutiveCalls(null, $this->pageId));
        $this->page
            ->expects($this->once())
            ->method('setId');
        $this->page
            ->expects($this->any())
            ->method('getBlocks')
            ->will($this->returnValue([$this->block]));
        $this->storage->save($this->page);
    }

    public function testDirectoryIsCreatedIfItDoesNotExist()
    {
        $storage = $this->storage;
        $dir = self::$directory . '/sub';
        $storage->setDirectory($dir);
        $storage->save($this->page);
        $this->assertFileExists($dir);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testExceptionIsThrownIfDirectoryCouldNotBeCreated()
    {
        chmod(self::$directory, 0000);
        $dir = self::$directory . '/sub';
        $this->storage->setDirectory($dir);
        $this->storage->save($this->page);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testExceptionIsThrownIfDirectoryIsNotReadable()
    {
        $dir = self::$directory . '/sub';
        mkdir($dir);
        chmod($dir, 0000);
        $this->storage->setDirectory($dir);
        $this->storage->save($this->page);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testExceptionIsThrownIfDirectoryIsNotWritable()
    {
        $dir = self::$directory . '/sub';
        mkdir($dir);
        chmod($dir, 0444);
        $this->storage->setDirectory($dir);
        $this->storage->save($this->page);
    }

    public function testLoad()
    {
        $storage = $this->storage;
        $page = $this->page;
        $storage->save($page);
        $this->assertInstanceOf('Midnight\Page\PageInterface', $storage->load($page->getId()));
    }

    public function testGetAll()
    {
        $this->storage->save($this->page);
        $this->assertCount(1, $this->storage->getAll());
    }

    public function testDelete()
    {
        $page = $this->page;
        $storage = $this->storage;
        $storage->save($page);
        $storage->delete($page);
        $this->assertNull($storage->load($page->getId()));
    }

    public function testLoadBySlug()
    {
        $page = new Page();
        $slug = 'my-slug';
        $page->setSlug($slug);
        $this->storage->save($page);
        $this->assertEquals($page->getId(), $this->storage->loadBySlug($slug)->getId());
    }

    public function testLoadBySlugReturnsNullIfPageIsNotFound()
    {
        $this->assertNull($this->storage->loadBySlug('does-not-exist'));
    }

    private static function delDir($dir)
    {
        self::emptyDir($dir);
        return rmdir($dir);
    }

    private static function emptyDir($dir)
    {
        chmod($dir, 0777);
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delDir("$dir/$file") : unlink("$dir/$file");
        }
    }
}
