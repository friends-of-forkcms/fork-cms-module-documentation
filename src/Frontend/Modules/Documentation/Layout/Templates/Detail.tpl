{*
    variables that are available:
    - {$articleData}: contains all categories, along with all questions inside a category
*}

{option:!articleData}
    <article>
        <div>
            <p>{$msgDocumentationNoItems}</p>
        </div>
    </article>
{/option:!articleData}

{option:articleData}
    <article>
        {option:articleEditLink}<a href="{$articleEditLink}" class="button">Edit on Github</a>{/option:articleEditLink}
        <div>
            {$articleData}
        </div>
    </article>

    <div class="pageNavigation">
        <ul>
            {option:prevLink}<li class="previousLink"><a href="{$prevLink.urlSlug}" rel="prev">&lsaquo; {$prevLink.displayName|ucfirst}</a></li>{/option:prevLink}
            {option:nextLink}<li class="nextLink"><a href="{$nextLink.urlSlug}" rel="next">&rsaquo; {$nextLink.displayName|ucfirst}</a></li>{/option:nextLink}
        </ul>
    </div>
{/option:articleData}