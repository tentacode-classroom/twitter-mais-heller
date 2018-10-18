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
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('compareFollows', [$this, 'compareFollows']),
            new TwigFunction('compareLikes', [$this, 'compareLikes']),
            new TwigFunction('compareRetweets', [$this, 'compareRetweets']),
            new TwigFunction('getFriendMessages', [$this, 'getFriendMessages']),
            new TwigFunction('getMessages', [$this, 'getMessages']),
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
    
    public function compareLikes($user, $message)
    {
        $messageLikes = $message->getLikes();
        foreach ($messageLikes as $like) {
            if ($like->getLiker()==$user) {
                return true;
            }
        }
        return false;
    }

    public function compareRetweets($user, $message)
    {
        $messageRetweets = $message->getRetweets();

        foreach ($messageRetweets as $retweet) {
            if ($retweet->getRetweeter()==$user) {
                return true;
            }
        }
        return false;
    }


    public function getMessages($user)
    {
        $userMessages = $user->getMessages()->toArray();
        $userRetweets = $retweets = $user->getRetweets();

        $retweetArray = [];

        foreach ($userRetweets as $retweet) {
                $message = $retweet->getMessageRetweeted();
            if (!($message->getUser() == $user)) {
                $message->isRetweeted=true;
                array_push($retweetArray, $message);
            }
        }
            dump($retweetArray);

        $messages = array_merge($userMessages, $retweetArray);
            dump(count($messages));
        usort($messages, function ($a, $b) {
            return $a->getPostDate() < $b->getPostDate()  ? 1 : -1;
        });
        
        return $messages;
    }
}
