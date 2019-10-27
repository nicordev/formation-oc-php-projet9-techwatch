<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwitterExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('show_twit_list', [$this, 'generateTwitListTag']),
            new TwigFunction('get_twitter_script', [$this, 'getTwitterScript'])
        ];
    }

    public function generateTwitListTag(
        string $target,
        ?int $width = null,
        ?int $height = null,
        bool $darkMode = false,
        bool $includeScript = false
    ) {
        $attributes = [];
        $script = "";

        if ($width) {
            $attributes[] = "data-width=\"{$width}\"";
        }
        if ($height) {
            $attributes[] = "data-height=\"{$height}\"";
        }
        if ($darkMode) {
            $attributes[] = 'data-theme="dark"';
        }
        $attributes[] = "href=\"https://twitter.com/{$target}?ref_src=twsrc%5Etfw\"";
        $attributes = implode(" ", $attributes);

        if ($includeScript) {
            $script = $this->getTwitterScript();
        }

        return "<a class=\"twitter-timeline\" {$attributes}>Tweets by {$target}</a>{$script}";
    }

    public function getTwitterScript()
    {
        return '<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>';
    }
}
