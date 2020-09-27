<?php

namespace Giberti\EmojiExtension;

use Giberti\EmojiExtension\Parser\EmojiParser;
use Giberti\EmojiExtension\Element;
use Giberti\EmojiExtension\Renderer;
use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Extension\ExtensionInterface;

final class EmojiExtension implements ExtensionInterface
{
    private $alternateMapping;

    public function __construct(?array $alternateMapping = null)
    {
        $this->alternateMapping = $alternateMapping;
    }

    public function register(ConfigurableEnvironmentInterface $environment)
    {
        $environment->addInlineRenderer(Element\Span::class, new Renderer\Span(), 0);
        $environment->addInlineParser(new EmojiParser($this->alternateMapping), 0);
    }


}
