<?php

use Giberti\EmojiExtension\EmojiExtension;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use PHPUnit\Framework\TestCase;

class EmojiExtensionTest extends TestCase
{
    protected function getParser(): CommonMarkConverter
    {
        $mapping = [
            ':coffee:' => ':hot_beverage:',
            ':wave:' => ':waving_hand:',
        ];
        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new EmojiExtension($mapping));

        return new CommonMarkConverter([], $environment);
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function getSourceAndExpectedOutputs(): array
    {
        return [
            'simple' => [
                ':airplane:',
                '<p><span class="emoji" title="airplane">✈️</span></p>',
            ],
            'inline' => [
                'I need :coffee: in the morning.',
                '<p>I need <span class="emoji" title="coffee">☕</span> in the morning.</p>',
            ],
            'multiple' => [
                'Going for :coffee:, see ya later :wave:',
                '<p>Going for <span class="emoji" title="coffee">☕</span>, see ya later <span class="emoji" title="wave">👋</span></p>',
            ],
            'repeating emoji' => [
                ':coffee: :coffee: :coffee: :wave:',
                '<p><span class="emoji" title="coffee">☕</span> <span class="emoji" title="coffee">☕</span> <span class="emoji" title="coffee">☕</span> <span class="emoji" title="wave">👋</span></p>',
            ],
            'inline-code-ignored' => [
                '`:wave:`',
                '<p><code>:wave:</code></p>',
            ],
            'inline code ignores single emoji' => [
                '`:wave:`',
                '<p><code>:wave:</code></p>',
            ],
            'inline code ignores multiple emoji' => [
                '`:wave: :wave: :wave:`',
                '<p><code>:wave: :wave: :wave:</code></p>',
            ],
            'code block ignores emoji' => [
                "```php\necho ':wave:';\n```",
                "<pre><code class=\"language-php\">echo ':wave:';\n</code></pre>",
            ],
            'emoji appear in headings' => [
                '# Heading :brain:',
                '<h1>Heading <span class="emoji" title="brain">🧠</span></h1>',
            ],
            'non-valid emoji just appear as normal strings' => [
                'Just :show-the-unaltered-value: inline.',
                '<p>Just :show-the-unaltered-value: inline.</p>',
            ],
            'text is not swallowed' => [
                ":smile: you're on candid :camera:",
                '<p>:smile: you\'re on candid <span class="emoji" title="camera">📷</span></p>',
            ],
            'text is not swallowed when using colon as punctuation' => [
                "line: 1\nlost line: 2\nother text\nthen :hot_beverage:",
                "<p>line: 1\nlost line: 2\nother text\nthen " . '<span class="emoji" title="hot_beverage">☕</span></p>',
            ]
        ];
    }

    /**
     * @dataProvider getSourceAndExpectedOutputs
     * @param $source
     * @param $expected
     */
    public function test($source, $expected) {
        $parser = $this->getParser();
        $this->assertEquals(
            $expected . PHP_EOL,
            $parser->convertToHtml($source),
            'Parser generated unexpected output for: "' . $source . '"'
        );
    }
}
