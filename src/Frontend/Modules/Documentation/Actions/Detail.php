<?php

namespace Frontend\Modules\Documentation\Actions;

use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\Documentation\Engine\Model;
use Frontend\Modules\Documentation\Resources\Navigation;
use Frontend\Modules\Documentation\Resources\NavigationItem;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use MatthiasMullie\Scrapbook\Adapters\Flysystem;

/**
 * This is the detail-action of the documentation module
 *
 * @author Jesse Dobbelaere <jesse@dobbelae.re>
 */
class Detail extends FrontendBaseBlock
{
    /**
     * @var Navigation
     */
    private $navigation;

    /**
     * @var NavigationItem
     */
    private $articleItem;

    /**
     * @var string
     */
    private $articleHtml;

    /**
     * Execute the extra
     */
    public function execute(): void
    {
        parent::execute();

        $this->getData();
        $this->loadTemplate();
        $this->parse();
    }

    /**
     * Load the data, don't forget to validate the incoming data
     */
    private function getData(): void
    {
        // Get our navigation
        $this->navigation = Model::getNavigation();

        // Get parameters
        $guideUrlSlug = $this->url->getParameter(1);
        $articleUrlSlug = $this->url->getParameter(2);

        // Throw a 404 if guide parameter is null, or it doesn't exist in our navigation tree.
        if ($guideUrlSlug === null || !$this->navigation->hasItem($guideUrlSlug)) {
            $this->redirect(FrontendNavigation::getURL(404));
        }

        // Redirect to first article in our navigation if we didn't specify an article in the url
        $guideItem = $this->navigation->getItem($guideUrlSlug);
        if ($articleUrlSlug === null) {
            $firstArticle = $guideItem->getChildren()->getFirstItem();

            // Redirect to first article in the guide
            $this->redirect($firstArticle->getFullUrl());
        }

        // Check if the article parameter exists in our navigation, else do a 404.
        if (!$guideItem->getChildren()->hasItem($articleUrlSlug)) {
            $this->redirect(FrontendNavigation::getURL(404));
        } else {
            $this->articleItem = $guideItem->getChildren()->getItem($articleUrlSlug);
        }

        // Get the html output of the article
        $this->getArticleHtml($guideUrlSlug, $articleUrlSlug);
    }

    /**
     * @param $guideUrlSlug
     * @param $articleUrlSlug
     */
    private function getArticleHtml($guideUrlSlug, $articleUrlSlug): void
    {
        // Init cache
        $adapter = new Local(FRONTEND_CACHE_PATH.'/Documentation/', LOCK_EX);
        $filesystem = new Filesystem($adapter);
        $cache = new Flysystem($filesystem);

        $cacheKey = 'article_' . md5($guideUrlSlug . '-' . $articleUrlSlug);
        $this->articleHtml = $cache->get($cacheKey);

        if (empty($this->articleHtml)) {
            $this->articleHtml = $this->articleItem->getHtml();
            $cache->set($cacheKey, $this->articleHtml);
        }
    }

    /**
     * Parse the data into the template
     */
    private function parse(): void
    {
        $this->template->assign('articleData', $this->articleHtml);
        $this->template->assign('articleEditLink', $this->articleItem->getEditUrl());

        $prevLink = $this->articleItem->getPreviousItem();
        $nextLink = $this->articleItem->getNextItem();
        $this->template->assign('prevLink', $prevLink !== null ? $prevLink->toArray() : []);
        $this->template->assign('nextLink', $nextLink !== null ? $nextLink->toArray() : []);
    }
}
