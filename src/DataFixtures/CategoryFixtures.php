<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORIES = [
        "Personal",
        "Recruiting",
        "Financial plan",
        "Future plan"
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $categoryString) {
            $category = new Category();
            $category->setCategoryName($categoryString);
            $key =  array_search($categoryString, self::CATEGORIES);
            $this->addReference('category_' . $key, $category);
            $manager->persist($category);
        }
        $manager->flush();
    }
}
