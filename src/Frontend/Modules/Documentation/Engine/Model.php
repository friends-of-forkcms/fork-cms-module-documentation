<?php

namespace Frontend\Modules\Documentation\Engine;

use Frontend\Modules\Documentation\Resources\Navigation;
use Symfony\Component\Filesystem\Exception\IOException;
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
    public static function getNavigation(): Navigation
    {
        $repositoryDocs = new GithubDocumentationAdapter(GithubDocumentation::getInstance());
        return $repositoryDocs->getNavigation();
    }

    /**
     * @param $navigationItem
     * @return string
     */
    public static function getArticleData($navigationItem): string
    {
        $repositoryDocs = new GithubDocumentationAdapter(GithubDocumentation::getInstance());
        return $repositoryDocs->getArticleData($navigationItem);
    }

    /**
     * Post-receive action for webhooks
     * @param Request $request
     * @return bool
     */
    public static function onWebhookPostReceive(Request $request): bool
    {
        $repositoryDocs = new GithubDocumentationAdapter(GithubDocumentation::getInstance());
        return $repositoryDocs->onWebhookPostReceive($request);
    }

    /**
     * Clear the documentation cache
     */
    public static function clearCache(): void
    {
        $finder = new Finder();
        $fs = new Filesystem();
        $documentationCacheFolder = FRONTEND_PATH . '/Cache/Documentation';

        if ($fs->exists($documentationCacheFolder)) {
            $cacheFiles = $finder->in($documentationCacheFolder)->getIterator();
            foreach ($cacheFiles as $file) {
                try {
                    $fs->remove($file->getRealPath());
                } catch (IOException $e) {
                    // Silently ignore
                }
            }
        }
    }
}
