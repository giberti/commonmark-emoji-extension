<?php
declare(strict_types=1);

namespace Giberti\EmojiExtension\Parser;

use Giberti\EmojiData\Mappings;
use Giberti\EmojiExtension\Element\Span;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

class EmojiParser implements InlineParserInterface
{
    private const REGEX = ':[_-a-zA-Z0-9]{2,}:';

    private $alternateMapping;

    public function __construct(?array $alternateMapping = [])
    {
        $this->alternateMapping = $alternateMapping;
    }

    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex(self::REGEX);
    }

    /**
     * @inheritDoc
     */
    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();
        $previousState = $cursor->saveState();

        $handle = $inlineContext->getFullMatch();
        $emoji = $this->getEmoji($handle);
        if (!$emoji) {
            $cursor->restoreState($previousState);

            return false;
        }

        $span = new Span();
        $span->data->set(
            'attributes',
            [
                'class' => 'emoji',
                'title' => $this->getTitle($handle),
            ]
        );
        $span->appendChild(new Text($emoji));

        $cursor->advanceBy($inlineContext->getFullMatchLength());
        $inlineContext->getContainer()->appendChild($span);

        return true;
    }

    protected function getTitle(string $handle): string
    {
        return substr($handle, 1, strlen($handle) - 2);
    }

    protected function getEmoji(string $handle): ?string
    {
        $handle = str_replace('-', '_', $handle);
        if ($this->alternateMapping) {
            $handle = isset($this->alternateMapping[$handle])
                ? $this->alternateMapping[$handle]
                : $handle;
        }

        return Mappings::getEmojiFromMarkdown($handle);
    }
}
