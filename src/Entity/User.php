<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *  fields={"email"},
 *  message="Cette adresse e-mail a déjà été utilisée pour un autre compte"
 * )
 * @UniqueEntity(
 *  fields={"profileName"},
 *  message="Ce pseudo a déjà été utilisé pour un autre compte"
 * )
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $profileName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=190, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="user", cascade={"persist"}))
     */
    private $messages;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please, upload a picture.")
     * @Assert\File(mimeTypes={ "image/jpeg", "image/jpg", "image/png" })
     */
    private $profilePicture;

    /**
     * @ORM\Column(type="simple_array")
     */
    private $roles = ['ROLE_USER'];

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Please, upload a picture.")
     * @Assert\File(mimeTypes={ "image/jpeg", "image/jpg", "image/png" })
     */
    private $bannerPicture;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Friend", mappedBy="follower", cascade={"persist", "remove"})
     */
    private $follower;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->friend = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function setProfileName(string $profileName): self
    {
        $this->profileName = $profileName;

        return $this;
    }

    public function getProfileName(): ?string
    {
        return $this->profileName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    public function setProfilePicture($profilePicture)
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }



    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized, array('allowed_classes' => false));
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getBannerPicture()
    {
        return $this->bannerPicture;
    }

    public function setBannerPicture($bannerPicture): self
    {
        $this->bannerPicture = $bannerPicture;

        return $this;
    }

    public function __toString(){
        return "yolo";
    }

    public function addFriend(Friend $friend): self
    {
        if (!$this->friend->contains($friend)) {
            $this->friend[] = $friend;
        }

        return $this;
    }

    public function removeFriend(Friend $friend): self
    {
        if ($this->friend->contains($friend)) {
            $this->friend->removeElement($friend);
        }

        return $this;
    }

    public function getFollower(): ?Friend
    {
        return $this->follower;
    }

    public function setFollower(?Friend $follower): self
    {
        $this->follower = $follower;

        // set (or unset) the owning side of the relation if necessary
        $newFollower = $follower === null ? null : $this;
        if ($newFollower !== $follower->getFollower()) {
            $follower->setFollower($newFollower);
        }

        return $this;
    }
}
