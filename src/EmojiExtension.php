<?php

namespace Giberti\EmojiExtension;

use Giberti\EmojiExtension\Parser\EmojiParser;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Environment\EnvironmentBuilderInterface;

final class EmojiExtension implements ExtensionInterface
{
    private $alternateMapping;

    public function __construct(?array $alternateMapping = null)
    {
        $this->alternateMapping = $alternateMapping;
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addInlineParser(new EmojiParser($this->alternateMapping), 0);
        $environment->addRenderer(Element\Span::class, new Renderer\Span(), 0);
    }
}
