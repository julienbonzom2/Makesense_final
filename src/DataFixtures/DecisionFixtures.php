<?php

namespace App\DataFixtures;

use App\Entity\Decision;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class DecisionFixtures extends Fixture implements DependentFixtureInterface
{
    public SluggerInterface $slugger;
    public const RATES = [
        "S",
        "M",
        "L",
        "XL"
    ];

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $index = 0;
        for ($i = 0; $i < 70; $i++) {
            $index++;
            $decision = new Decision();
            $decision->setTitle('decision_' . $i);
            $decision->setSlug($this->slugger->slug($decision->getTitle()));
            if ($i == 10 || $i == 20 || $i == 30 || $i == 40 || $i == 50 || $i == 60) {
                $index = 0;
            }
            $decision->setAuthor($this->getReference('user_' . $index));
            $decision->setCategory($this->getReference('category_' . $faker->numberBetween(0, 3)));
            if ($i < 10) {
                $decision->setStatus($this->getReference('status_0'));
            } elseif ($i < 20) {
                $decision->setStatus($this->getReference('status_1'));
            } elseif ($i < 30) {
                $decision->setStatus($this->getReference('status_2'));
            } elseif ($i < 40) {
                $decision->setStatus($this->getReference('status_3'));
            } elseif ($i < 50) {
                $decision->setStatus($this->getReference('status_4'));
            } elseif ($i < 60) {
                $decision->setStatus($this->getReference('status_5'));
            } else {
                $decision->setStatus($this->getReference('status_6'));
            }
            $decision->setDescription($faker->paragraph(20));
            $decision->setProblem($faker->paragraph(20));
            $decision->setEffortRate(self::RATES[$faker->numberBetween(0, 3)]);
            $decision->setImpactRate(self::RATES[$faker->numberBetween(0, 3)]);
            $decision->setProfit($faker->paragraph(5));
            $decision->setDrawback($faker->paragraph(5));
            $decision->setCreatedAt(DateTimeImmutable::createFromMutable(($faker->dateTimeBetween('-90 days', 'now'))));
            $this->addReference('decision_' . $i, $decision);
            $manager->persist($decision);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
            StatusFixtures::class
        ];
    }
}
