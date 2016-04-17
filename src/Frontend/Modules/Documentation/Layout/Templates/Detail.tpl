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
{/option:articleData}