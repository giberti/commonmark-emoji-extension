# CommonMark Emoji Extension

An extension to provide GitHub and Slack style emoji for the [League CommonMark package](https://commonmark.thephpleague.com/).
By default, it will substitute official [unicode CLDR short names](http://unicode.org/emoji/charts/full-emoji-list.html) for the emoji, but can also use aliases to map common language to the official name.
The generated output wraps the emoji in a `<span>` to permit additional styling and provides a `title` attribute for accessibility.

## Quality

[![Build Status](https://travis-ci.com/giberti/commonmark-emoji-extension.svg?branch=master)](https://travis-ci.com/giberti/commonmark-emoji-extension)

### Installing

```
composer require giberti/commonmark-emoji-extension
```

## Usage

```php
use Giberti\EmojiExtension\EmojiExtension;
```

### Basic

To use, add a new instance of `EmojiExtension` to the CommonMark environment and use as you would normally.

```php
// Get a configured instance of the converter
$environment = new \League\CommonMark\Environment\Environment();
$environment->addExtension(new \League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension());
$environment->addExtension(new \Giberti\EmojiExtension\EmojiExtension());
$converter = new \League\CommonMark\MarkdownConverter($environment);

// <p>I can haz <span class="emoji" title="hot_beverage">☕</span>?</p>
echo $converter->convert('I can haz :hot_beverage:?')->getContent();
```

### Providing Aliases

GitHub and Slack both shortcuts that do not map directly to the official Unicode CLDR Short Name. Mappings can be injected at the time the instance of `EmojiExtension` is created.

The Aliases should be passed as an associative array with the key being the new alias and the value being the CLDR Short Name equivalent.

```php
$aliases = [
    ':coffee:' => ':hot_beverage:',
    ':smile:' => ':grinning_face_with_smiling_eyes:',
    // ... any other aliases you wish to support
];

// Get a configured instance of the converter
$environment = new \League\CommonMark\Environment\Environment();
$environment->addExtension(new \League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension());
$environment->addExtension(new \Giberti\EmojiExtension\EmojiExtension($aliases));
$converter = new \League\CommonMark\MarkdownConverter($environment);

// <p>I can haz <span class="emoji" title="coffee">☕</span>?</p>
echo $converter->convert('I can haz :coffee:?')->getContent();
```
