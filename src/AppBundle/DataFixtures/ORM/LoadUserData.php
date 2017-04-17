<?php
// src/BlogBundle/DataFixtures/ORM/LoadUserData.php

namespace BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user@domain.com');
        $user->setPlainPassword('user');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_USER'));

        $manager->persist($user);
        $manager->flush();

    }
}