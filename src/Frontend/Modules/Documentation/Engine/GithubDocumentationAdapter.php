<?php

namespace Frontend\Modules\Documentation\Engine;

use Frontend\Modules\Documentation\Resources\Navigation;
use Frontend\Modules\Documentation\Resources\NavigationItem;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GithubDocumentationAdapter
 * @package Frontend\Modules\Documentation\Engine
 */
class GithubDocumentationAdapter implements DocumentationInterface
{
    /**
     * @var GithubDocumentation
     */
    private $githubDocs;

    /**
     * GithubDocumentationAdapter constructor.
     *
     * @param GithubDocumentation $githubDocs
     */
    public function __construct(GithubDocumentation $githubDocs)
    {
        $this->githubDocs = $githubDocs;
    }

    /**
     * Fetch the whole navigation object
     *
     * @return Navigation
     */
    public function getNavigation()
    {
        return $this->githubDocs->getNavigation();
    }

    /**
     * Fetch the content of the article in the NavigationItem
     *
     * @param NavigationItem $navigationItem
     * @return string
     */
    public function getArticleData(NavigationItem $navigationItem)
    {
        return $this->githubDocs->getArticleData($navigationItem);
    }

    /**
     * Post-receive action for webhooks
     * @param Request $request
     * @return bool
     */
    public function onWebhookPostReceive(Request $request)
    {
        return $this->githubDocs->onWebhookPostReceive($request);
    }
}
