<?php

namespace App\DataFixtures;

use App\Entity\Associated;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AssociatedFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //$faker = Factory::create();
        for ($i = 0; $i < 30; $i++) {
            for ($j = 0; $j < 5; $j++) {
                $associated = new Associated();
                $associated->setStatus('Impacted');
                $associated->setUser($this->getReference('user_' . $j));
                $associated->setDecision($this->getReference('decision_' . $i));
                $associated->setIsChecked(false);
                $manager->persist($associated);
                $manager->flush();
            }
            for ($k = 5; $k <= 9; $k++) {
                $associated = new Associated();
                $associated->setStatus('Expert');
                $associated->setUser($this->getReference('user_' . $k));
                $associated->setDecision($this->getReference('decision_' . $i));
                $associated->setIsChecked(false);
                $manager->persist($associated);
                $manager->flush();
            }
        }
    }

    public function getDependencies()
    {
        return [
            DecisionFixtures::class,
        ];
    }
}
