<?php

namespace Frontend\Modules\Documentation\Engine;

use Frontend\Modules\Documentation\Resources\Navigation;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Request;

/**
 * In this file we store all generic functions that we will be using in the documentation module
 *
 * @author Jesse Dobbelaere <jesse@dobbelae.re>
 */
class Model
{
    /**
     * Get the navigation from the documentation
     *
     * @return Navigation
     */
    public static function getNavigation()
    {
        $repositoryDocs = new GithubDocumentationAdapter(GithubDocumentation::getInstance());
        $navigation = $repositoryDocs->getNavigation();

        return $navigation;
    }

    /**
     * @param $navigationItem
     * @return string
     */
    public static function getArticleData($navigationItem)
    {
        $repositoryDocs = new GithubDocumentationAdapter(GithubDocumentation::getInstance());
        $articleData = $repositoryDocs->getArticleData($navigationItem);

        return $articleData;
    }

    /**
     * Post-receive action for webhooks
     * @param Request $request
     * @return bool
     */
    public static function onWebhookPostReceive(Request $request)
    {
        $repositoryDocs = new GithubDocumentationAdapter(GithubDocumentation::getInstance());
        return $repositoryDocs->onWebhookPostReceive($request);
    }

    /**
     * Clear the documentation cache
     */
    public static function clearCache()
    {
        $finder = new Finder();
        $fs = new Filesystem();
        $documentationCacheFolder = FRONTEND_PATH . '/Cache/Documentation';

        if ($fs->exists($documentationCacheFolder)) {
            $content = $finder->in($documentationCacheFolder);
            foreach ($content as $file) {
                $fs->remove($file->getRealpath());
            }
        }
    }
}
