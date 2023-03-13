<?php

namespace App\DataFixtures;

use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatusFixtures extends Fixture
{
    public const STATUS = [
        "Decision started",
        "First decision",
        "Decision in conflict",
        "Final decision",
        "Decision cancelled",
        "Decision taken",
        "Late decision",
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::STATUS as $statusString) {
            $status = new Status();
            $status->setName($statusString);
            $key =  array_search($statusString, self::STATUS);
            $this->addReference('status_' . $key, $status);
            $manager->persist($status);
        }
        $manager->flush();
    }
}
