<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Message;
use Symfony\Component\Validator\Constraints\Date;

class UserFixtures extends Fixture
{
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
        $message1->setRetweet(3);
        $message1->setPostDate(new \DateTime('NOW'));

        $message2 = new Message();
        $message2->setContent("Voir si ça marche");
        $message2->setRetweet(98);
        $message2->setPostDate(new \DateTime('NOW'));

        $message3 = new Message();
        $message3->setContent("Juste pour être sûr");
        $message3->setRetweet(98);
        $message3->setPostDate(new \DateTime('NOW'));

        $user1 = new User();
        $user1->setEmail("admin@test.com");
        $user1->setPassword("admin");
        $user1->setFirstName("Jim");
        $user1->setLastName("Carrey");
        $user1->setProfilename("Dieu");
        $user1->setBirthday(new \DateTime($date1));
        $user1->setProfilePicture("https://a57.foxnews.com/static.foxnews.com/foxnews.com/content/uploads/2018/09/0/0/morgan-freeman_042511.jpg?ve=1&tl=1");
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
        $user2->setProfilePicture("Louloute");
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
        $user3->setProfilePicture("Louloute");
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
        $user4->setProfilePicture("Louloute");
        $user4->setBannerPicture("Louloute");
        $user4->setRoles(['ROLE_MOD']);
        $user4->addMessage($message1);
        $user4->addMessage($message2);
        $user4->addMessage($message3);

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        $manager->persist($user4);
        $manager->flush();
    }
}
