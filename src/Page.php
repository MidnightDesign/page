<?php

namespace Midnight\Page;

use Midnight\Block\BlockContainerTrait;
use Midnight\Block\BlockInterface;

class Page implements PageInterface
{
    use BlockContainerTrait;
    
    /** @var string */
    private $id;
    /** @var string */
    private $name;
    /** @var string */
    private $slug;

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
}
