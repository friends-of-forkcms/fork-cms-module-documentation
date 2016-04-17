<?php

namespace Frontend\Modules\Documentation\Engine;

/**
 * Class DocumentationHelper
 * @package Frontend\Modules\Documentation\Engine
 */
class DocumentationHelper
{
    /**
     * Convert directory to URL
     * Rule: Documentation files have underscore to seperate words
     *
     * @param string $dir
     * @return string
     */
    public static function directoryToUrl($dir)
    {
        return str_replace(' ', '-', $dir);
    }

    /**
     * Convert filename to label
     * Rule: Documentation files have underscore to seperate words
     *
     * @param string $filename
     * @return string
     */
    public static function filenameToLabel($filename)
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
    public static function filenameToUrl($filename)
    {
        return str_replace('_', '-', $filename);
    }

    /**
     * Convert url to directory
     * Rule: Documentation files have underscore to seperate words
     *
     * @param string $url
     * @return string
     */
    public static function urlToDirectory($url)
    {
        return str_replace('-', ' ', $url);
    }

    /**
     * Convert url to filename
     * Rule: Documentation files have underscore to seperate words
     *
     * @param string $url
     * @return string
     */
    public static function urlToFilename($url)
    {
        return str_replace('-', '_', $url);
    }

    /**
     * Extract the extension from filename
     *
     * @param $name
     * @return string
     */
    public static function extractExtensionFromName($name)
    {
        $ext = substr(strrchr($name, '.'), 1);
        return $ext;
    }

    /**
     * Cleanup filename
     *
     * @param $name
     * @return string
     */
    public static function cleanupName($name)
    {
        // Remove digits used for sorting (1.documentation, 1- documentation, 1 documentation, ...)
        $sortNumberRegex = '/^\d+(.|-)/';
        $name = trim(preg_replace($sortNumberRegex, '', $name));

        // Remove any extension (.md)
        $extension = self::extractExtensionFromName($name);
        $name = str_replace('.' . $extension, '', $name);

        return $name;
    }
}
