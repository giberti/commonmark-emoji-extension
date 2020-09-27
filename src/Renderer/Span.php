<?php
declare(strict_types=1);

namespace Giberti\EmojiExtension\Renderer;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

class Span implements InlineRendererInterface
{
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        $attributes = [
            'class' => 'emoji',
        ];
        $title = $inline->getData('title');
        if ($title) {
            $attributes['title'] = $title;
        }

        return new HtmlElement(
            'span',
            $attributes,
            $htmlRenderer->renderInlines(
                $inline->children()
            )
        );
    }
}
