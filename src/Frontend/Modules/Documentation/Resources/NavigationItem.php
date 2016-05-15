<?php

namespace Frontend\Modules\Documentation\Resources;

use Frontend\Modules\Documentation\Engine\Model;

/**
 * Class NavigationItem
 * @package Frontend\Modules\Documentation\Resources
 */
class NavigationItem
{
    /**
     * @var string
     */
    private $displayName;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string;
     */
    private $urlSlug;

    /**
     * @var string
     */
    private $fullUrl;

    /**
     * @var string
     */
    private $editUrl;

    /**
     * @var Navigation
     */
    private $children;

    /**
     * @var NavigationItem
     */
    private $parent;

    /**
     * @var bool
     */
    private $selected;

    /**
     * NavigationItem constructor.
     * @param string $displayName
     * @param string $fileName
     * @param string $type
     * @param string $path
     * @param string $urlSlug
     * @param string $fullUrl
     */
    public function __construct($displayName, $fileName, $type, $path, $urlSlug, $fullUrl)
    {
        $this->displayName = $displayName;
        $this->fileName = $fileName;
        $this->type = $type;
        $this->path = $path;
        $this->urlSlug = $urlSlug;
        $this->fullUrl = $fullUrl;
        $this->selected = false;
    }

    /**
     * Transform the NavigationItem to an array (for templating purposes)
     * @return array
     */
    public function toArray()
    {
        $itemArray = [
            'displayName' => $this->displayName,
            'fileName' => $this->fileName,
            'type' => $this->type,
            'path' => $this->path,
            'urlSlug' => $this->urlSlug,
            'fullUrl' => $this->fullUrl,
            'editUrl' => $this->editUrl,
            'children' => $this->children !== null ? $this->children->toArray() : [],
            'selected' => $this->selected,
        ];

        return $itemArray;
    }

    /**
     * Fetch the previous item in the navigation.
     * @return NavigationItem|null
     */
    public function getPreviousItem()
    {
        if (empty($this->parent) || empty($this->parent->getChildren())) {
            return null;
        }

        // Find the current index
        $parentChildrenItems = $this->parent->getChildren()->getItems();
        $currentIndex = array_search($this, $parentChildrenItems);

        // Find previous item
        if ($currentIndex !== false && isset($parentChildrenItems[$currentIndex - 1])) {
            return $parentChildrenItems[$currentIndex - 1];
        }

        return null;
    }

    /**
     * Fetch the next item in the navigation
     * @return NavigationItem|null
     */
    public function getNextItem()
    {
        if (empty($this->parent) || empty($this->parent->getChildren())) {
            return null;
        }

        // Find the current index
        $parentChildrenItems = $this->parent->getChildren()->getItems();
        $currentIndex = array_search($this, $parentChildrenItems);

        // Find previous item
        if ($currentIndex !== false && isset($parentChildrenItems[$currentIndex + 1])) {
            return $parentChildrenItems[$currentIndex + 1];
        }

        return null;
    }

    /**
     * @return NavigationItem
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param NavigationItem $parent
     * @return NavigationItem
     */
    public function setParent(NavigationItem $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @param NavigationItem $item
     */
    public function addChild(NavigationItem $item)
    {
        $this->children->addItem($item);
    }

    /**
     * @param Navigation $children
     * @return NavigationItem
     */
    public function setChildren(Navigation $children)
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isDir()
    {
        return $this->type === 'dir';
    }

    /**
     * @return bool
     */
    public function isFile()
    {
        return $this->type === 'file';
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getUrlSlug()
    {
        return $this->urlSlug;
    }

    /**
     * @return string
     */
    public function getFullUrl()
    {
        return $this->fullUrl;
    }

    /**
     * @return Navigation
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function getEditUrl()
    {
        return $this->editUrl;
    }

    /**
     * @param string $editUrl
     * @return NavigationItem
     */
    public function setEditUrl($editUrl)
    {
        $this->editUrl = $editUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return Model::getArticleData($this);
    }

    /**
     * @return bool
     */
    public function isSelected()
    {
        return $this->selected;
    }

    /**
     * @param bool $selected
     * @return NavigationItem
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;

        return $this;
    }

    /**
     * Get the original path to the file like on e.g. Github
     *
     * @return string
     */
    public function getOriginalFilePath()
    {
        $originalFilePath = '';
        if ($this->getParent()) {
            $guideFilename = $this->getParent()->getFileName();
            $originalFilePath .= rawurlencode($guideFilename) . '/';
        }
        $originalFilePath .= rawurlencode($this->getFileName());

        return $originalFilePath;
    }
}
