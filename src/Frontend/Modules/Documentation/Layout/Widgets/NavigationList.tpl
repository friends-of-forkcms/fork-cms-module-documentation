{*
	variables that are available:
	- {$widgetDocNavList}: contains an array with all posts for the category, each element contains data about the post
*}

{option:widgetDocNavList}
    <ul>
        {iteration:widgetDocNavList}
            <li {option:widgetDocNavList.selected}class="selected"{/option:widgetDocNavList.selected}>
                <a href="{$widgetDocNavList.fullUrl}" title="{$widgetDocNavList.displayName}">{$widgetDocNavList.displayName|ucfirst}</a>

                {option:widgetDocNavList.selected}
                    {option:widgetDocNavList.children}
                        <ul>
                            {iteration:widgetDocNavList.children}
                                <li {option:widgetDocNavList.children.selected}class="selected"{/option:widgetDocNavList.children.selected}>
                                    <a href="{$widgetDocNavList.children.fullUrl}" title="{$widgetDocNavList.children.displayName}">{$widgetDocNavList.children.displayName|ucfirst}</a>
                                </li>
                            {/iteration:widgetDocNavList.children}
                        </ul>
                    {/option:widgetDocNavList.children}
                {/option:widgetDocNavList.selected}

            </li>
        {/iteration:widgetDocNavList}
    </ul>
{/option:widgetDocNavList}