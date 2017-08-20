<?php

namespace Frontend\Modules\Documentation\Engine;

use Frontend\Modules\Documentation\Resources\Navigation;
use Frontend\Modules\Documentation\Resources\NavigationItem;

/**
 * DocumentationInterface
 *
 * @author Jesse Dobbelaere <jesse@dobbelae.re>
 */
interface DocumentationInterface
{
    /**
     * @return Navigation
     */
    public function getNavigation(): Navigation;

    /**
     * @param NavigationItem $navigationItem
     * @return string
     */
    public function getArticleData(NavigationItem $navigationItem): string;
}
