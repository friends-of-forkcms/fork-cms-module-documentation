<?php

namespace Frontend\Modules\Documentation\Engine;

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
        return str_replace(array(' ', '_'), '-', $filename);
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
