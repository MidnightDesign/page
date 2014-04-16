<?php

namespace Midnight\Page;

interface PageInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param string $id
     * @return void
     */
    public function setId($id);
}