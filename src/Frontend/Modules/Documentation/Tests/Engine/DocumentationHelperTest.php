<?php

namespace Frontend\Modules\Documentation\Tests\Engine;

use Frontend\Modules\Documentation\Engine\DocumentationHelper;
use PHPUnit\Framework\TestCase;

/**
 * Class DocumentationHelperTest
 */
class DocumentationHelperTest extends TestCase
{
    /**
     * @dataProvider stringReplaceMarkdownStringProvider
     */
    public function testStringReplaceRelativeImagesIfNeeded($original, $expected): void
    {
        $original = DocumentationHelper::rewriteRelativeImageUrls($original, 'https://github.com/forkcms/forkcms/raw/master/docs');
        $this->assertEquals($expected, $original);
    }

    public function stringReplaceMarkdownStringProvider()
    {
        return [
            [
                '<img src="https://github.com/forkcms/forkcms/raw/master/docs/assets/installation_step2.png" alt="Installation step 2" style="max-width:100%;">',
                '<img src="https://github.com/forkcms/forkcms/raw/master/docs/assets/installation_step2.png" alt="Installation step 2" style="max-width:100%;">'
            ],
            [
                '<a href="./assets/installation_step2.png" target="_blank"><img src="./assets/installation_step2.png" alt="Installation step 2" style="max-width:100%;"></a>',
                '<a href="./assets/installation_step2.png" target="_blank"><img src="https://github.com/forkcms/forkcms/raw/master/docs/./assets/installation_step2.png" alt="Installation step 2" style="max-width:100%;"></a>'
            ]
        ];
    }
}
