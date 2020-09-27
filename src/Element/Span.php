<?php
declare(strict_types=1);

namespace Giberti\EmojiExtension\Element;

use League\CommonMark\Inline\Element\AbstractInline;

class Span extends AbstractInline
{
    public function __construct($title)
    {
        $this->data['title'] = $title;
    }

    /**
     * @inheritDoc
     */
    public function isContainer(): bool
    {
        return true;
    }
}
