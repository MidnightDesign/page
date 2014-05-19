<?php

namespace Midnight\Page;

use Midnight\Block\BlockInterface;

interface PageInterface
{
    const BEFORE = 'before';
    const AFTER = 'after';

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

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $blockId
     *
     * @return BlockInterface
     */
    public function getBlock($blockId);

    /**
     * @param BlockInterface $block
     *
     * @return void
     */
    public function removeBlock(BlockInterface $block);

    /**
     * @param BlockInterface $block
     * @param BlockInterface $otherBlock
     * @param string         $beforeOrAfter
     *
     * @return void
     */
    public function moveBlock($block, $otherBlock, $beforeOrAfter);

    /**
     * @param $slug
     *
     * @return void
     */
    public function setSlug($slug);

    /**
     * @return string
     */
    public function getSlug();
}
