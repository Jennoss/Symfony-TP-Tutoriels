<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setFirstname('Admin');
        $admin->setLastname('User');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setVerified(true);
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@example.com');
        $user->setFirstname('Normal');
        $user->setLastname('User');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'user123'));
        $user->setVerified(true);
        $manager->persist($user);

        $bannedUser = new User();
        $bannedUser->setEmail('banned@example.com');
        $bannedUser->setFirstname('Banned');
        $bannedUser->setLastname('User');
        $bannedUser->setRoles(['ROLE_USER']);
        $bannedUser->setBanned(true);
        $bannedUser->setPassword($this->passwordHasher->hashPassword($bannedUser, 'banned123'));
        $bannedUser->setVerified(true);
        $manager->persist($bannedUser);

        $manager->flush();
    }
}