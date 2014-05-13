<?php

namespace Midnight\Page;

use Midnight\Block\BlockInterface;

class Page implements PageInterface
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
     * @var BlockInterface[]
     */
    private $blocks = array();

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
     */
    public function addBlock(BlockInterface $block)
    {
        $this->blocks[] = $block;
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
     * @return BlockInterface[]
     */
    public function getBlocks()
    {
        if (empty($this->blocks)) {
            $this->blocks = array();
        }
        return $this->blocks;
    }

    /**
     * @param string $blockId
     *
     * @return BlockInterface
     */
    public function getBlock($blockId)
    {
        foreach ($this->getBlocks() as $block) {
            if ($block->getId() === $blockId) {
                return $block;
            }
        }
        return null;
    }

    /**
     * @param BlockInterface $block
     *
     * @return void
     */
    public function removeBlock(BlockInterface $block)
    {
        $blocks = $this->getBlocks();
        $keys = array_keys($blocks, $block);
        foreach ($keys as $k) {
            unset($blocks[$k]);
        }
        $this->blocks = $blocks;
    }
}
