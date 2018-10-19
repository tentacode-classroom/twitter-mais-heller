<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Message;
use App\Entity\Friend;
use App\Entity\Likes;
use App\Entity\Retweet;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);


        $date1="0000-00-00";
        $date2="1999-05-01";
        $date3="1999-05-01";
        $date4="2000-05-07";

        $message1 = new Message();
        $message1->setContent("Test 123");
        $message1->setPostDate(new \DateTime('NOW'));

        $message2 = new Message();
        $message2->setContent("Voir si ça marche");
        $message2->setPostDate(new \DateTime('NOW'));

        $message3 = new Message();
        $message3->setContent("Juste pour être sûr");
        $message3->setPostDate(new \DateTime('NOW'));

        $user1 = new User();
        $user1->setEmail("admin@test.com");
        $user1->setPassword("admin");
        $user1->setFirstName("Jim");
        $user1->setLastName("Carrey");
        $user1->setProfilename("Dieu");
        $user1->setBirthday(new \DateTime($date1));
        $user1->setProfilePicture("photo1.png");
        $user1->setBannerPicture("Louloute");
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->addMessage($message1);//attention aux messages

        $user2 = new User();
        $user2->setEmail("anthony@test.com");
        $user2->setPassword("toto");
        $user2->setFirstName("Anthony");
        $user2->setLastName("Dubuis");
        $user2->setProfilename("Numéro 9");
        $user2->setBirthday(new \DateTime($date2));
        $user2->setProfilePicture("photo2.png");
        $user2->setBannerPicture("Louloute");
        $user2->setRoles(['ROLE_USER']);
        $user2->addMessage($message1);
        $user2->addMessage($message2);

        $user3 = new User();
        $user3->setEmail("florian@test.com");
        $user3->setPassword("floflo");
        $user3->setFirstName("Florian");
        $user3->setLastName("Armenoult");
        $user3->setProfilename("Flo");
        $user3->setBirthday(new \DateTime($date3));
        $user3->setProfilePicture("photo3.png");
        $user3->setBannerPicture("Louloute");
        $user3->setRoles(['ROLE_USER']);
        $user3->addMessage($message1);

        $user4 = new User();
        $user4->setEmail("louise@test.com");
        $user4->setPassword("loulou");
        $user4->setFirstName("Louise");
        $user4->setLastName("Baulan");
        $user4->setProfilename("Louloute");
        $user4->setBirthday(new \DateTime($date4));
        $user4->setProfilePicture("photo4.png");
        $user4->setBannerPicture("Louloute");
        $user4->setRoles(['ROLE_MOD']);
        $user4->addMessage($message1);
        $user4->addMessage($message2);
        $user4->addMessage($message3);


        $password = $this->encoder->encodePassword($user1, $user1->getPassword());
        $user1->setPassword($password);
        $password = $this->encoder->encodePassword($user2, $user2->getPassword());
        $user2->setPassword($password);
        $password = $this->encoder->encodePassword($user3, $user3->getPassword());
        $user3->setPassword($password);
        $password = $this->encoder->encodePassword($user4, $user4->getPassword());
        $user4->setPassword($password);

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);


        $friend1 = new Friend();
        $friend1->setFollower($user4);
        $friend1->setFollowing($user2);

        $friend2 = new Friend();
        $friend2->setFollower($user2);
        $friend2->setFollowing($user3);

        $friend3 = new Friend();
        $friend3->setFollower($user1);
        $friend3->setFollowing($user3);

        $friend4 = new Friend();
        $friend4->setFollower($user1);
        $friend4->setFollowing($user4);

        $retweet1 = new Retweet();
        $retweet1->setRetweeter($user4);
        $retweet1->setMessageRetweeted($message1);

        $retweet2 = new Retweet();
        $retweet2->setRetweeter($user2);
        $retweet2->setMessageRetweeted($message2);

        $retweet3 = new Retweet();
        $retweet3->setRetweeter($user3);
        $retweet3->setMessageRetweeted($message3);

        $like1 = new Likes();
        $like1->setLiker($user2);
        $like1->setMessageLiked($message2);

        $like2 = new Likes();
        $like2->setLiker($user3);
        $like2->setMessageLiked($message1);

        $like3 = new Likes();
        $like3->setLiker($user4);
        $like3->setMessageLiked($message1);

        $manager->persist($friend1);
        $manager->persist($friend2);
        $manager->persist($friend3);
        $manager->persist($friend4);

        $manager->persist($like1);
        $manager->persist($like2);
        $manager->persist($like3);

        $manager->persist($retweet1);
        $manager->persist($retweet2);
        $manager->persist($retweet3);

        $manager->flush();
    }
}
