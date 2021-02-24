<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use  Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-Fr');
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName($faker->sentence());
            $category->setSlug($faker->word(10, true));
            $manager->persist($category);
            $manager->flush();
        }
        for ($i = 0; $i < 100; $i++) {
            $post = new Post();
            $post->setTitle($faker->text(50));
            $post->setDescription($faker->paragraph(1));
            $post->setContent($faker->paragraph(100,true));
            $post->setCategory($category);
            $manager->persist($post);
            $manager->flush();
        }
    }
}
