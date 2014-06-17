<?php

namespace MidnightTest\Page;

use Midnight\Page\Page;
use PHPUnit_Framework_TestCase;

class PageTest extends PHPUnit_Framework_TestCase
{
    public function testNewInstanceHasNoBlocks()
    {
        $page = new Page();
        $this->assertEmpty($page->getBlocks());
    }

    public function testNewInstanceHasNoId()
    {
        $page = new Page();
        $this->assertNull($page->getId());
    }

    public function testCanSetId()
    {
        $page = new Page();
        $page->setId('foo');
        $this->assertEquals('foo', $page->getId());
    }

    public function testCanAddBlock()
    {
        $page = new Page();
        $block = $this->getMock('Midnight\Block\BlockInterface');
        $page->addBlock($block);
        $blocks = $page->getBlocks();
        $this->assertCount(1, $blocks);
        $this->assertEquals($block, $blocks[0]);
    }

    public function testCanAddBlockAtOccupiedPosition()
    {
        $page = new Page();
        $blockOne = $this->getMock('Midnight\Block\BlockInterface');
        $blockTwo = $this->getMock('Midnight\Block\BlockInterface');
        $blockThree = $this->getMock('Midnight\Block\BlockInterface');
        $page->addBlock($blockOne, 0);
        $page->addBlock($blockTwo, 1);
        $page->addBlock($blockThree, 1);
        $blocks = $page->getBlocks();
        $this->assertSame($blockOne, $blocks[0]);
        $this->assertSame($blockThree, $blocks[1]);
        $this->assertSame($blockTwo, $blocks[2]);
    }

    public function testCanSetAndGetName()
    {
        $page = new Page();
        $name = 'FooPage';
        $page->setName($name);
        $this->assertEquals($name, $page->getName());
    }

    public function testCanGetBlockById()
    {
        $page = new Page();
        $blockId = 'testId';
        $block = $this->getMock('Midnight\Block\BlockInterface');
        $block->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($blockId));
        $page->addBlock($block);
        $this->assertSame($block, $page->getBlock($blockId));
    }

    public function testGetBlockByIdReturnsNullIfIdDoesNotExist()
    {
        $page = new Page();
        $block = $this->getMock('Midnight\Block\BlockInterface');
        $block->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('foo'));
        $page->addBlock($block);
        $this->assertNull($page->getBlock('bar'));
    }

    public function testCanRemoveBlock()
    {
        $page = new Page();
        $block = $this->getMock('Midnight\Block\BlockInterface');
        $page->addBlock($block);
        $page->removeBlock($block);
        $this->assertEmpty($page->getBlocks());
    }

    public function testCanSetAndGetSlug()
    {
        $page = new Page();
        $slug = 'test-slug';
        $page->setSlug($slug);
        $this->assertEquals($slug, $page->getSlug());
    }
}
