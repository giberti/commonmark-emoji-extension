<?php
declare(strict_types=1);

namespace Giberti\EmojiExtension\Renderer;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

class Span implements NodeRendererInterface
{
    public function render(Node $inline, ChildNodeRendererInterface $htmlRenderer)
    {
        $attributes = array_merge(
            $inline->data->export()['attributes'],
            [
                'class' => 'emoji',
            ]
        );

        return new HtmlElement(
            'span',
            $attributes,
            $htmlRenderer->renderNodes(
                $inline->children()
            ),
            false
        );
    }
}
