<?php

namespace Midnight\Page;

use Midnight\Block\BlockInterface;
use Midnight\Block\BlockList;
use Midnight\Block\BlockListInterface;
use Midnight\Block\Exception\BlockNotFoundException;

class Page implements PageInterface, BlockListInterface
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var BlockListInterface
     */
    private $blockList;
    /**
     * @var string
     */
    private $slug;

    public function __construct()
    {
        $this->blockList = new BlockList();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param BlockInterface $block
     * @param null           $position
     */
    public function addBlock(BlockInterface $block, $position = null)
    {
        $this->blockList->add($block, $position);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $blockId
     *
     * @return BlockInterface
     */
    public function getBlock($blockId)
    {
        foreach ($this->getBlocks() as $block) {
            if ((string)$block->getId() === $blockId) {
                return $block;
            }
        }
        return null;
    }

    /**
     * @return BlockInterface[]
     */
    public function getBlocks()
    {
        return $this->blockList->getAll();
    }

    /**
     * @param BlockInterface $block
     *
     * @return void
     */
    public function removeBlock(BlockInterface $block)
    {
        $this->blockList->remove($block);
    }

    /**
     * @param BlockInterface $block
     * @param BlockInterface $otherBlock
     * @param string         $beforeOrAfter
     *
     * @throws BlockNotFoundException
     * @return void
     */
    public function moveBlock($block, $otherBlock, $beforeOrAfter)
    {
        $position = null;
        $blocks = $this->blockList->getAll();
        foreach ($blocks as $index => $block) {
            if ($block === $otherBlock) {
                $position = $index;
                continue;
            }
        }
        if (null === $position) {
            throw new BlockNotFoundException('The reference block could not be found in this page.');
        }
        if ($beforeOrAfter === self::AFTER) {
            $position++;
        }
        $this->blockList->setPosition($block, $position);
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @param BlockInterface $block
     * @param int|null       $position
     *
     * @return void
     */
    public function add(BlockInterface $block, $position = null)
    {
        $this->blockList->add($block, $position);
    }

    /**
     * @param BlockInterface $block
     * @param int            $position
     *
     * @return void
     */
    public function setPosition(BlockInterface $block, $position)
    {
        $this->blockList->setPosition($block, $position);
    }

    /**
     * @return BlockInterface[]
     */
    public function getAll()
    {
        return $this->blockList->getAll();
    }

    /**
     * @param BlockInterface $block
     *
     * @return void
     */
    public function remove(BlockInterface $block)
    {
        $this->blockList->remove($block);
    }
}
