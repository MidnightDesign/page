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
     * @var BlockInterface[]
     */
    private $blocks;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
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
} 