<?php

namespace App\Data;

use App\Entity\Category;

class SearchProduct
{

    /**
     *
     * @var integer
     */
    public $page = 1;

    /**
     *
     * @var string
     */
    public $q = '';

    /**
     *
     * @var Category[]
     */
    public $categories = [];




}