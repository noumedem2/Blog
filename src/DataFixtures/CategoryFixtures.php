<?php

namespace App\DataFixtures;

use App\Entity\Category;
use  Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $faker = Factory::create('fr-Fr');
        for ($i=0; $i < 100; $i++) { 
            $category = new Category();
            $category->setName( $faker->sentence());
            $category->setSlug($faker->word(10,true));
            $manager->persist($category);
            $manager->flush();
        }
     
    }
}
