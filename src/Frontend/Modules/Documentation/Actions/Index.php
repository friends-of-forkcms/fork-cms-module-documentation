<?php

namespace Frontend\Modules\Documentation\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Modules\Documentation\Engine\Model;
use Frontend\Modules\Documentation\Resources\Navigation;
use Frontend\Core\Engine\Navigation as FrontendNavigation;

/**
 * This is the index-action of the documentation module
 *
 * @author Jesse Dobbelaere <jesse@dobbelae.re>
 */
class Index extends FrontendBaseBlock
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
        parent::execute();

        $this->getData();
        $this->loadTemplate();
        $this->parse();
    }

    /**
     * Load the data, don't forget to validate the incoming data
     */
    private function getData()
    {
        // Get our navigation
        $this->navigation = Model::getNavigation();

        // Redirect to first navigation item
        $firstArticle = $this->navigation->getFirstItem()->getChildren()->getFirstItem();
        $this->redirect($firstArticle->getFullUrl());
    }

    /**
     * Parse the data into the template
     */
    private function parse()
    {
    }
}
