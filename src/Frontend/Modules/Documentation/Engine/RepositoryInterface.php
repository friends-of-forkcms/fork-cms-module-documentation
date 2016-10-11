<?php

namespace Frontend\Modules\Documentation\Engine;

use Frontend\Modules\Documentation\Resources\Navigation;
use Frontend\Modules\Documentation\Resources\NavigationItem;

/**
 * RepositoryInterface
 *
 * @author Jesse Dobbelaere <jesse@dobbelae.re>
 */
interface RepositoryInterface
{
    /**
     * @return Navigation
     */
    public function getNavigation();

    /**
     * @param NavigationItem $navigationItem
     * @return string
     */
    public function getArticleData(NavigationItem $navigationItem);
}
