<?php

namespace Frontend\Modules\Documentation\Engine;

use Frontend\Modules\Documentation\Resources\Navigation;
use Frontend\Modules\Documentation\Resources\NavigationItem;
use Symfony\Component\HttpFoundation\Request;

/**
 * GithubDocumentationAdapter
 *
 * @author Jesse Dobbelaere <jesse@dobbelae.re>
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
    public function getNavigation(): Navigation
    {
        return $this->githubDocs->getNavigation();
    }

    /**
     * Fetch the content of the article in the NavigationItem
     *
     * @param NavigationItem $navigationItem
     * @return string
     * @throws \Guzzle\Common\Exception\RuntimeException
     * @throws \Github\Exception\InvalidArgumentException
     */
    public function getArticleData(NavigationItem $navigationItem): string
    {
        return $this->githubDocs->getArticleData($navigationItem);
    }

    /**
     * Post-receive action for webhooks
     * @param Request $request
     * @return bool
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \LogicException
     */
    public function onWebhookPostReceive(Request $request): bool
    {
        return $this->githubDocs->onWebhookPostReceive($request);
    }
}
