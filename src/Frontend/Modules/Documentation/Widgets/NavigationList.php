<?php

namespace Frontend\Modules\Documentation\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Modules\Documentation\Engine\Model;
use Frontend\Modules\Documentation\Resources\Navigation;

/**
 * NavigationList
 *
 * @author Jesse Dobbelaere <jesse@dobbelae.re>
 */
class NavigationList extends FrontendBaseWidget
{
    /**
     * @var Navigation
     */
    private $navigation;

    /**
     * Execute the extra
     */
    public function execute()
    {
        // Call parent
        parent::execute();

        $this->loadTemplate();
        $this->getData();
        $this->parse();
    }

    /**
     * Fetch data
     */
    private function getData()
    {
        // Fetch navigation
        $this->navigation = Model::getNavigation();

        // Get URL parameters
        $guideUrlSlug = $this->URL->getParameter(1);
        $articleUrlSlug = $this->URL->getParameter(2);

        // Set the current guide & article in the URL as active
        if ($guideUrlSlug !== null && $articleUrlSlug) {
            if ($this->navigation->hasItem($guideUrlSlug)) {
                $guideItem = $this->navigation->getItem($guideUrlSlug)->setSelected(true);

                if ($guideItem->getChildren()->hasItem($articleUrlSlug)) {
                    $guideItem->getChildren()->getItem($articleUrlSlug)->setSelected(true);
                };
            }
        }
    }

    /**
     * Parse the data into the template
     */
    private function parse()
    {
        $this->tpl->assign('widgetDocNavList', $this->navigation->toArray());
    }
}
