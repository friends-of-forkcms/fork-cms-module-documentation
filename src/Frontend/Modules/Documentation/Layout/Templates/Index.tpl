{*
	variables that are available:
	- {$documentationCategories}: contains all categories, along with all questions inside a category
*}


{option:!documentationCategories}
	<div id="documentationIndex">
		<section class="mod">
			<div class="inner">
				<div class="bd content">
					<p>{$msgDocumentationNoItems}</p>
				</div>
			</div>
		</section>
	</div>
{/option:!documentationCategories}

{option:documentationCategories}
	<section id="documentationIndex" class="mod">
		<div class="inner">
			{option:allowMultipleCategories}
			<div class="hd">
				<ul>
					{iteration:documentationCategories}
						<li><a href="#{$documentationCategories.url}" title="{$documentationCategories.title}">{$documentationCategories.title}</a></li>
					{/iteration:documentationCategories}
				</ul>
			</div>
			{/option:allowMultipleCategories}
			<div class="bd">
				{iteration:documentationCategories}
					<section class="mod">
						<div class="inner">
							{option:allowMultipleCategories}
							<header class="hd">
								<h3 id="{$documentationCategories.url}"><a href="{$documentationCategories.full_url}" title="{$documentationCategories.title}">{$documentationCategories.title}</a></h3>
							</header>
							{/option:allowMultipleCategories}

							<div class="bd content">
								<ul>
									{iteration:documentationCategories.questions}
										<li><a href="{$documentationCategories.questions.full_url}">{$documentationCategories.questions.question}</a></li>
									{/iteration:documentationCategories.questions}
								</ul>
							</div>
						</div>
					</section>
				{/iteration:documentationCategories}
			</div>
		</div>
	</section>
{/option:documentationCategories}