<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $postDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="messages")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Likes", mappedBy="messageLiked", cascade={"persist", "remove"})
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Retweet", mappedBy="messageRetweeted", cascade={"persist", "remove"})
     */
    private $retweets;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->retweets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPostDate(): ?\DateTimeInterface
    {
        return $this->postDate;
    }

    public function setPostDate(\DateTimeInterface $postDate): self
    {
        $this->postDate = $postDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Likes[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLikes(Likes $likes): self
    {
        if (!$this->likes->contains($likes)) {
            $this->likes[] = $likes;
            $likes->setMessageLiked($this);
        }

        return $this;
    }

    public function removeLikes(Likes $likes): self
    {
        if ($this->likes->contains($likes)) {
            $this->likes->removeElement($likes);
            // set the owning side to null (unless already changed)
            if ($likes->getMessageLiked() === $this) {
                $likes->setMessageLiked(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Retweet[]
     */
    public function getRetweets(): Collection
    {
        return $this->retweets;
    }

    public function addRetweet(Retweet $retweet): self
    {
        if (!$this->retweets->contains($retweet)) {
            $this->retweets[] = $retweet;
            $retweet->setMessageRetweeted($this);
        }

        return $this;
    }

    public function removeRetweet(Retweet $retweet): self
    {
        if ($this->retweets->contains($retweet)) {
            $this->retweets->removeElement($retweet);
            // set the owning side to null (unless already changed)
            if ($retweet->getMessageRetweeted() === $this) {
                $retweet->setMessageRetweeted(null);
            }
        }

        return $this;
    }
}
