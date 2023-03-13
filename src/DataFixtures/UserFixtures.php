<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        // Create admin user
        $user = new User();
        $user->setEmail('admin@makesense.com');
        $user->setFirstname('Jean-Le-Grand');
        $user->setLastname('Bokassa');
        $user->setPassword($this->passwordHasher->hashPassword($user, '123456789'));
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setNationality($this->getReference('nationality_' . $faker->numberBetween(0, 2)));
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setAvatar('jean.jpg');
        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setEmail('david@makesense.com');
        $user->setFirstname('David');
        $user->setLastname('Faure');
        $user->setPassword($this->passwordHasher->hashPassword($user, '123456789'));
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setNationality($this->getReference('nationality_' . $faker->numberBetween(0, 2)));
        $user->setAvatar('david.png');
        $manager->persist($user);
        $manager->flush();
        // Create fake user with loop
        for ($j = 0; $j <= 10; $j++) {
            $user = new User();
            $user->setCreatedAt(new DateTimeImmutable());
            $user->setEmail('user' . $j . '@makesense.com');
            $user->setPassword($this->passwordHasher->hashPassword($user, '123456789'));
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setAvatar('default-avatar-' . $faker->numberBetween(0, 3) . '.png');
            $user->setNationality($this->getReference('nationality_' . $faker->numberBetween(0, 2)));
            $this->addReference('user_' . $j, $user);
            $manager->persist($user);
            $manager->flush();
        }
        // Create fake user with faker
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setCreatedAt(new DateTimeImmutable());
            $user->setEmail($faker->email);
            $user->setPassword($this->passwordHasher->hashPassword($user, '123456789'));
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setNationality($this->getReference(
                'nationality_' . $faker->numberBetween(0, 2)
            ));
            $user->setAvatar('homer-simpson.jpg');
            $this->addReference('fake_user_' . $i, $user);
            $manager->persist($user);
            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return [
            NationalityFixtures::class,
        ];
    }
}
