<?php

namespace App\DataFixtures;

use App\Entity\Nationality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NationalityFixtures extends Fixture
{
    public const NATIONALITIES = [
        'french' => ['français', 'la-france.png'],
        'english' => ['anglais', 'royaume-uni.png'],
        'espagnol' => ['espagnol', 'espagne.png'],
    ];


    public function load(ObjectManager $manager): void
    {
        $index = 0;
        foreach (self::NATIONALITIES as $key => $value) {
            $nationality = new Nationality();
            $nationality->setName($key);
            $nationality->setLanguage($value[0]);
            $nationality->setFlag($value[1]);
            $this->addReference('nationality_' . $index, $nationality);
            $manager->persist($nationality);
            $manager->flush();
            $index = $index + 1;
        }
    }
}
