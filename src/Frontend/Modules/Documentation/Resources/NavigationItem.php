<?php

namespace Frontend\Modules\Documentation\Resources;

use Frontend\Modules\Documentation\Engine\DocumentationHelper;
use Frontend\Modules\Documentation\Engine\Model;

/**
 * NavigationItem
 *
 * @author Jesse Dobbelaere <jesse@dobbelae.re>
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
    public function toArray(): array
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
    public function getPreviousItem(): ?NavigationItem
    {
        if (empty($this->parent) || empty($this->parent->getChildren())) {
            return null;
        }

        // Find the current index
        $parentChildrenItems = $this->parent->getChildren()->getItems();
        $currentIndex = array_search($this, $parentChildrenItems, true);

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
    public function getNextItem(): ?NavigationItem
    {
        if (empty($this->parent) || empty($this->parent->getChildren())) {
            return null;
        }

        // Find the current index
        $parentChildrenItems = $this->parent->getChildren()->getItems();
        $currentIndex = array_search($this, $parentChildrenItems, true);

        // Find previous item
        if ($currentIndex !== false && isset($parentChildrenItems[$currentIndex + 1])) {
            return $parentChildrenItems[$currentIndex + 1];
        }

        return null;
    }

    /**
     * @return NavigationItem
     */
    public function getParent(): NavigationItem
    {
        return $this->parent;
    }

    /**
     * @param NavigationItem $parent
     * @return NavigationItem
     */
    public function setParent(NavigationItem $parent): NavigationItem
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @param NavigationItem $item
     */
    public function addChild(NavigationItem $item): void
    {
        $this->children->addItem($item);
    }

    /**
     * @param Navigation $children
     * @return NavigationItem
     */
    public function setChildren(Navigation $children): NavigationItem
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isDir(): bool
    {
        return $this->type === 'dir';
    }

    /**
     * @return bool
     */
    public function isFile(): bool
    {
        return $this->type === 'file';
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getUrlSlug(): string
    {
        return $this->urlSlug;
    }

    /**
     * @return string
     */
    public function getFullUrl(): string
    {
        return $this->fullUrl;
    }

    /**
     * @return Navigation
     */
    public function getChildren(): Navigation
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function getEditUrl(): string
    {
        return $this->editUrl;
    }

    /**
     * @param string $editUrl
     * @return NavigationItem
     */
    public function setEditUrl($editUrl): NavigationItem
    {
        $this->editUrl = $editUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        $article = Model::getArticleData($this);

        // Cleanup 01.%20article.md links to a friendly url format
        $article = DocumentationHelper::rewriteInternalLinksToFriendlyUrl($article);

        return $article;
    }

    /**
     * @return bool
     */
    public function isSelected(): bool
    {
        return $this->selected;
    }

    /**
     * @param bool $selected
     * @return NavigationItem
     */
    public function setSelected($selected): NavigationItem
    {
        $this->selected = $selected;

        return $this;
    }

    /**
     * Get the original path to the file like on e.g. Github
     *
     * @return string
     */
    public function getOriginalFilePath(): string
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
