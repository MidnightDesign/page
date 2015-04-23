<?php

namespace MidnightTest\Page;

use Midnight\Block\BlockInterface;
use Midnight\Page\Page;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class PageTest extends PHPUnit_Framework_TestCase
{
    /** @var Page */
    private $page;

    public function setUp()
    {
        $this->page = new Page();
    }

    public function testNewInstanceHasNoBlocks()
    {
        $this->assertEmpty($this->page->getBlocks());
    }

    public function testNewInstanceHasNoId()
    {
        $this->assertNull($this->page->getId());
    }

    public function testCanSetId()
    {
        $this->page->setId('foo');
        $this->assertEquals('foo', $this->page->getId());
    }

    public function testCanAddBlock()
    {
        /** @var BlockInterface|PHPUnit_Framework_MockObject_MockObject $block */
        $block = $this->makeBlock();
        $this->page->addBlock($block);
        $blocks = $this->page->getBlocks();
        $this->assertCount(1, $blocks);
        $this->assertEquals($block, $blocks[0]);
    }

    public function testCanAddBlockAtOccupiedPosition()
    {
        $blockOne = $this->makeBlock();
        $blockTwo = $this->makeBlock();
        $blockThree = $this->makeBlock();
        $this->page->addBlock($blockOne, 0);
        $this->page->addBlock($blockTwo, 1);
        $this->page->addBlock($blockThree, 1);
        $blocks = $this->page->getBlocks();
        $this->assertSame($blockOne, $blocks[0]);
        $this->assertSame($blockThree, $blocks[1]);
        $this->assertSame($blockTwo, $blocks[2]);
    }

    public function testCanSetAndGetName()
    {
        $name = 'FooPage';
        $this->page->setName($name);
        $this->assertEquals($name, $this->page->getName());
    }

    public function testCanGetBlockById()
    {
        $blockId = 'testId';
        $block = $this->makeBlock();
        $block->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($blockId));
        $this->page->addBlock($block);
        $this->assertSame($block, $this->page->getBlock($blockId));
    }

    public function testGetBlockByIdReturnsNullIfIdDoesNotExist()
    {
        $block = $this->makeBlock();
        $block->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('foo'));
        $this->page->addBlock($block);
        $this->assertNull($this->page->getBlock('bar'));
    }

    public function testCanRemoveBlock()
    {
        $block = $this->makeBlock();
        $this->page->addBlock($block);
        $this->page->removeBlock($block);
        $this->assertEmpty($this->page->getBlocks());
    }

    public function testCanSetAndGetSlug()
    {
        $slug = 'test-slug';
        $this->page->setSlug($slug);
        $this->assertEquals($slug, $this->page->getSlug());
    }

    public function testMoveBlock()
    {
        $blockOne = $this->makeBlock();
        $blockTwo = $this->makeBlock();
        $this->page->addBlock($blockOne);
        $this->page->addBlock($blockTwo);
        $this->page->moveBlock($blockTwo, 0);
        $blocks = $this->page->getBlocks();
        $this->assertSame($blockTwo, $blocks[0]);
        $this->assertSame($blockOne, $blocks[1]);
    }

    public function testMoveBlockAfter()
    {
        $blockOne = $this->makeBlock();
        $blockTwo = $this->makeBlock();
        $blockThree = $this->makeBlock();
        $this->page->addBlock($blockOne);
        $this->page->addBlock($blockTwo);
        $this->page->addBlock($blockThree);
        $this->page->moveBlock($blockThree, 1);
        $blocks = $this->page->getBlocks();
        $this->assertSame($blockOne, $blocks[0]);
        $this->assertSame($blockThree, $blocks[1]);
        $this->assertSame($blockTwo, $blocks[2]);
    }

    public function testRemove()
    {
        $block = $this->makeBlock();
        $this->page->addBlock($block);
        $this->page->removeBlock($block);
        $this->assertEmpty($this->page->getBlocks());
    }

    /**
     * @return BlockInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private function makeBlock()
    {
        return $this->getMockBuilder(BlockInterface::class)->getMock();
    }
}
