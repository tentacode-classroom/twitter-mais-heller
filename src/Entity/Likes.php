<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LikesRepository")
 */
class Likes
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="likes")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $liker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Message", inversedBy="likes")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $messageLiked;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLiker(): ?User
    {
        return $this->liker;
    }

    public function setLiker(?User $liker): self
    {
        $this->liker = $liker;

        return $this;
    }

    public function getMessageLiked(): ?Message
    {
        return $this->messageLiked;
    }

    public function setMessageLiked(?Message $messageLiked): self
    {
        $this->messageLiked = $messageLiked;

        return $this;
    }
}
