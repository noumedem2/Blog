<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use DateTime;
use  Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $users = $this->userFixtures($manager);
        $categories = $this->categoryFixtures($manager, $users);
        $posts =  $this->postFixtures($manager, $categories, $users);
        $this->commentFixtures($manager, $users, $posts);
    }
    private function userFixtures(ObjectManager $manager)
    {
        $faker = Factory::create('fr-Fr');
        $tuser = [];
        # Fisrt User
        $user1 = new User();
        $user1->setFisrtName("Jane");
        $user1->setLastName("Doe");
        $user1->setEmail("janedoe@example.com");
        $user1->setPassword(
            $this->encoder->encodePassword($user1, "secret")
        );
        $user1->setCreatedAt(new DateTime('now'));
        $user1->setUpdatedAt(new DateTime('now'));
        $tuser[] = $user1;
        $manager->persist($user1);
        # Second User
        $user2 = new User();
        $user2->setFisrtName("John");
        $user2->setLastName("Doe");
        $user2->setEmail("johnedoe@example.com");
        $user2->setPassword(
            $this->encoder->encodePassword($user2, "secret")
        );
        $user2->setCreatedAt(new DateTime('now'));
        $user2->setUpdatedAt(new DateTime('now'));
        $tuser[] = $user2;
        $manager->persist($user2);
        # Other Users
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setFisrtName($faker->name());
            $user->setLastName($faker->name());
            $user->setEmail($faker->email());
            $user->setPassword(
                $this->encoder->encodePassword($user, "secret")
            );
            $user->setCreatedAt(new DateTime('now'));
            $user->setUpdatedAt(new DateTime('now'));
            $tuser[] = $user;
            $manager->persist($user);
        }
        $manager->flush();
        return $tuser;
    }
    private function categoryFixtures(ObjectManager $manager, array $users)
    {
        $tcategory = [];
        $faker = Factory::create('fr-Fr');
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName($faker->text(10));
            $category->setSlug($faker->slug());
            $category->setCreatedAt(new DateTime('now'));
            $category->setUpdatedAt(new DateTime('now'));
            $category->setUser($faker->randomElement($users));
            $tcategory[] = $category;
            $manager->persist($category);
        }
        $manager->flush();
        return $tcategory;
    }
    public function postFixtures(ObjectManager $manager, array $categories, array $users)
    {
        $posts = [];
        $faker = Factory::create('fr-Fr');
        for ($i = 0; $i < 100; $i++) {
            $post = new Post();
            $post->setTitle($faker->text(50));
            $post->setDescription($faker->paragraph(1));
            $post->setContent($faker->paragraph(100, true));
            $post->setCategory($faker->randomElement($categories));
            $post->setUser($faker->randomElement($users));
            $post->setCreatedAt(new DateTime('now'));
            $post->setUpdatedAt(new DateTime('now'));
            $posts[] = $post;
            $manager->persist($post);
            $manager->flush();
        }
        return $posts;
    }
    private function commentFixtures(ObjectManager $manager, array $users, array $posts)
    {
        $faker = Factory::create('fr-Fr');
        for ($i = 0; $i < 20; $i++) {
            $comment = new Comment();
            $comment->setContent($faker->text(100));
            $comment->setCreatedAt(new DateTime('now'));
            $comment->setUpdatedAt(new DateTime('now'));
            $comment->setUser($faker->randomElement($users));
            $comment->setPost($faker->randomElement($posts));
            $manager->persist($comment);
        }
        $manager->flush();
        return $comment;
    }
}
