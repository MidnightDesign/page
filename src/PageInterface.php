<?php

namespace Midnight\Page;

use Midnight\Block\BlockContainerInterface;
use Midnight\Block\BlockInterface;

interface PageInterface extends BlockContainerInterface
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
