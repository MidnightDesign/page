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
}