<?php

namespace Frontend\Modules\Documentation\Engine;

use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class DocumentationHelper
 * @package Frontend\Modules\Documentation\Engine
 */
class DocumentationHelper
{
    /**
     * Convert filename to label
     * Rule: Documentation files have underscore to seperate words
     *
     * @param string $filename
     * @return string
     */
    public static function filenameToLabel($filename): string
    {
        return str_replace('_', ' ', $filename);
    }

    /**
     * Convert filename to url
     * Rule: Documentation files have underscore to seperate words
     *
     * @param string $filename
     * @return string
     */
    public static function filenameToUrl($filename): string
    {
        return str_replace(array(' ', '_'), '-', $filename);
    }

    /**
     * Extract the extension from filename
     *
     * @param $name
     * @return string
     */
    public static function extractExtensionFromName($name): string
    {
        return substr(strrchr($name, '.'), 1);
    }

    /**
     * Cleanup filename to remove unnecessary artifacts like sorting numbers, directory structures or extensions.
     *
     * @param $name
     * @return string
     */
    public static function cleanupName($name): string
    {
        // Urldecode it
        $name = rawurldecode($name);

        // Remove directory structures ../../../ only if we're not in the first level.
        // @todo Fix this so we can hop to a higher level, instead of only supporting two level deep navigation.
        $cleanupRegex = '/^(..\/)*(?!..\/)(?=.*\/.*md)/';
        $name = trim(preg_replace($cleanupRegex, '', $name));

        // Remove digits and %20 spaces used for sorting (1.documentation, 1- documentation, 1 documentation, ...)
        $cleanupRegex = '/(^|(?<=\/))\d+(\.|-|\s*)\s*/';
        $name = trim(preg_replace($cleanupRegex, '', $name));

        // Remove any extension (.md)
        $name = str_replace('.md', '', $name);

        return $name;
    }

    /**
     * @param string $article
     * @return string
     */
    public static function rewriteInternalLinksToFriendlyUrl(string $article): string
    {
        $markdownArticleMatches = [];

        // Find all matches in the article that start with a number
        preg_match_all('/(?<=href=")(..\/)*\d+(\.|-|(%20)*)(%20)*\s*.*\.md/mi', $article, $markdownArticleMatches);

        foreach ($markdownArticleMatches[0] as $item) {
            $friendlyUrl = self::filenameToUrl(self::cleanupName($item));

            if (preg_match('/^(..\/)+/', $item)) {
                $friendlyUrl = FrontendNavigation::getUrlForBlock('Documentation', 'Detail') . "/$friendlyUrl";
            }

            $article = str_replace($item, $friendlyUrl, $article);
        }

        return $article;
    }

    /**
     * Makes relative image in the html document, absolute.
     * @param string $article
     * @param string $baseUrl
     * @return string
     */
    public static function rewriteRelativeImageUrls(string $article, string $baseUrl = ''): string
    {
        $fs = new Filesystem();

        // Remove trailing slash from baseUrl
        $baseUrl = rtrim($baseUrl, '/');

        // https://regex101.com/r/Bosw7B/1
        $regex = '/img +src=[\'"](.*?)[\'"]/';

        return preg_replace_callback($regex, function($matches) use ($fs, $baseUrl) {
            [$imgNode, $imgSrcPath] = $matches;

            if ($fs->isAbsolutePath($imgSrcPath)) {
                // Nothing to do here
                return $imgNode;
            }

            return str_replace($imgSrcPath, "$baseUrl/$imgSrcPath", $imgNode);
        }, $article);
    }
}
