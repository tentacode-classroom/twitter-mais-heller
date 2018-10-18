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

        
        $date1="1999-05-01";
        $message1 = new Message();
        $message1->setContent("Test 123");
        $message1->setLikes(2);
        $message1->setRetweet(3);
        $message1->setPostDate(new \DateTime('NOW'));

        $user1 = new User();
        $user1->setEmail("test1@yolo.com");
        $user1->setPassword("abc123");
        $user1->setFirstName("Toto");
        $user1->setLastName("Toto");
        $user1->setUsername("TotoToto");
        $user1->setBirthday(new \DateTime($date1));
        $user1->addMessage($message1);

        $manager->persist($user1);
        $manager->flush();
    }
}
