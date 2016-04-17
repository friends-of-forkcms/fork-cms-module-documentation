<?php

namespace Frontend\Modules\Documentation\Engine;

use Frontend\Modules\Documentation\Resources\Navigation;
use Frontend\Modules\Documentation\Resources\NavigationItem;

/**
 * Interface DocumentationInterface
 * @package Frontend\Modules\Documentation\Engine
 */
interface DocumentationInterface
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
