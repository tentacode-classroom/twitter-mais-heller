<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class HellerExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('compareFollows', [$this, 'compareFollows']),
            new TwigFunction('getFriendMessages', [$this, 'getFriendMessages']),
        ];
    }

    public function compareFollows($userId, $followArray)
    {
        foreach ($followArray as $follow) {
            if (($follow->getFollower()->getId())==$userId->getId()) {
                return true;
            }
        }
        return false;
    }

    public function getFriendMessages($userId, $followArray)
    {

        $messageArray = [];

        foreach ($followArray as $follow) {
                $messages = $follow->getFollowing()->getMessages()->toArray();
            foreach ($messages as $message) {
                 array_push($messageArray, $message);
            }
        }

            usort($messageArray, function ($a, $b) {
                return $a->getPostDate() < $b->getPostDate()  ? 1 : -1;
            });

         return $messageArray;
    }
}
