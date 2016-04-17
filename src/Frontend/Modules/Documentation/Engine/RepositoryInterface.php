<?php

namespace Frontend\Modules\Documentation\Engine;

use Frontend\Modules\Documentation\Resources\Navigation;
use Frontend\Modules\Documentation\Resources\NavigationItem;

/**
 * Interface RepositoryInterface
 * @package Frontend\Modules\Documentation\Engine
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
