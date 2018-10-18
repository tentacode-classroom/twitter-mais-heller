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
            new TwigFilter('date_diff', [$this, 'datediffFilter']),
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

    public function datediffFilter(\Twig_Environment $env, $date, $now = null)
    {
        // Convert both dates to DateTime instances.
        $date = $this->dateFromString($env, $date, false);
        $now = $this->dateFromString($env, $now, false);
 
        // Get the difference between the two DateTime objects.
        $diff = $date->diff($now);
 
        return $diff;
    }

     
    public function dateFromString($env, $date, $timezone)
    {
        // determine the timezone
        if (!$timezone) {
            $defaultTimezone = $env->getExtension('core')->getTimezone();
        } elseif (!$timezone instanceof DateTimeZone) {
            $defaultTimezone = new DateTimeZone($timezone);
        } else {
            $defaultTimezone = $timezone;
        }
 
        // immutable dates
        if ($date instanceof DateTimeImmutable) {
            return false !== $timezone ? $date->setTimezone($defaultTimezone) : $date;
        }
 
        if ($date instanceof DateTime || $date instanceof DateTimeInterface) {
            $date = clone $date;
            if (false !== $timezone) {
                $date->setTimezone($defaultTimezone);
            }
 
            return $date;
        }
 
        $date = new DateTime($date, $defaultTimezone);
        if (false !== $timezone) {
            $date->setTimezone($defaultTimezone);
        }
 
        return $date;
    }
 
}
