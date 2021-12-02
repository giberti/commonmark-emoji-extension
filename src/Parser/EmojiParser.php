<?php
declare(strict_types=1);

namespace Giberti\EmojiExtension\Parser;

use Giberti\EmojiData\Mappings;
use Giberti\EmojiExtension\Element\Span;
use League\CommonMark\Inline\Element\Text;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\InlineParserContext;

class EmojiParser implements InlineParserInterface
{
    private const REGEX = '/^:[_-a-zA-Z0-9]{2,}:/i';

    private $alternateMapping;

    public function __construct(?array $alternateMapping = [])
    {
        $this->alternateMapping = $alternateMapping;
    }

    /**
     * @inheritDoc
     */
    public function getCharacters(): array
    {
        return [':'];
    }

    /**
     * @inheritDoc
     */
    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();

        $previousState = $cursor->saveState();
        $handle = $cursor->match(self::REGEX);
        if (!$handle) {
            $cursor->restoreState($previousState);

            return false;
        }

        $emoji = $this->getEmoji($handle);
        if (!$emoji) {
            $cursor->restoreState($previousState);

	    return false;
        }

        $node = new Span($this->getTitle($handle));
        $node->appendChild(new Text($emoji));
        $inlineContext->getContainer()->appendChild($node);

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
