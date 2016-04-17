<?php

namespace Frontend\Modules\Documentation\Resources;

/**
 * Class Navigation
 * @package Frontend\Modules\Documentation\Resources
 */
class Navigation
{
    /**
     * @var NavigationItem[]
     */
    private $items;

    /**
     * @param NavigationItem $item
     */
    public function addItem(NavigationItem $item)
    {
        $this->items[] = $item;
    }

    /**
     * @return NavigationItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return Navigation
     */
    public function setItems(array $items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Get the first navigationItem
     * @return NavigationItem|null
     */
    public function getFirstItem()
    {
        if (empty($this->items)) {
            return null;
        }

        // Use the reset function to "set the internal pointer of an array to its first element".
        return reset($this->items);
    }

    /**
     * @param $urlSlug
     * @return bool If the item was found in the navigation items.
     */
    public function hasItem($urlSlug)
    {
        return $this->getItem($urlSlug) !== null;
    }

    /**
     * @param $urlSlug
     * @return NavigationItem
     */
    public function getItem($urlSlug)
    {
        foreach ($this->items as $item) {
            if ($item->getUrlSlug() === $urlSlug) {
                return $item;
            }
        }

        return null;
    }


    /**
     * Transform the navigation to an array (for templating purposes)
     *
     * @return array
     */
    public function toArray()
    {
        $navigationArray = [];

        foreach ($this->items as $item) {
            $navigationArray[] = $item->toArray();
        }

        return $navigationArray;
    }
}
