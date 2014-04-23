<?php

namespace Midnight\Page;

use Midnight\Block\BlockInterface;

interface PageInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param string $id
     *
     * @return void
     */
    public function setId($id);

    /**
     * @return BlockInterface[]
     */
    public function getBlocks();

    /**
     * @param BlockInterface $block
     *
     * @return void
     */
    public function addBlock(BlockInterface $block);
}