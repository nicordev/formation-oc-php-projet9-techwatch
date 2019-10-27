<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class RssFeedExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('abstract', [$this, 'makeAbstract'])
        ];
    }

    public function makeAbstract(
        string $feed,
        int $length = 255
    ) {
        $noTagFeed = strip_tags($feed);
        $abstract = substr($noTagFeed, 0, $length);

        if (strlen($noTagFeed) > $length) {
            $abstract .= "...";
        }

        return $abstract;
    }
}
