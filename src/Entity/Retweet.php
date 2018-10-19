<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RetweetRepository")
 */
class Retweet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="retweets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $retweeter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Message", inversedBy="retweets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $messageRetweeted;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRetweeter(): ?User
    {
        return $this->retweeter;
    }

    public function setRetweeter(?User $retweeter): self
    {
        $this->retweeter = $retweeter;

        return $this;
    }

    public function getMessageRetweeted(): ?Message
    {
        return $this->messageRetweeted;
    }

    public function setMessageRetweeted(?Message $messageRetweeted): self
    {
        $this->messageRetweeted = $messageRetweeted;

        return $this;
    }
}
