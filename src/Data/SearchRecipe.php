<?php

namespace App\Data;

use App\Entity\CategoryRecipe;

class SearchRecipe
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
     * @var CategoryRecipe[]
     */
    public $categoriesRecipe = [];




}