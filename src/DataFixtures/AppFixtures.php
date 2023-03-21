<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Generator;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        // User
        $users = [];
        for($i=1; $i <= 10; $i++) {
            $user = new User();
            $user->setFullName($this->faker->name());
            $user->setPseudo(mt_rand(0,1) === 1 ? $this->faker->firstName() : null);
            $user->setEmail($this->faker->safeEmail());
            $user->setRoles(['ROLE_USER']);
            $user->setPlainPassword('password');
            $users[] = $user;
            $manager->persist($user);
        }
        $manager->flush();

        // Article
        $articles = [];
        for ($i=1; $i <= 50; $i++) { 
            $article = new Article();
            $article->setName($this->faker->word())->setPrice(mt_rand(0, 100))->setUser($users[mt_rand(0, count($users)-1)]);
            $articles[] = $article;
            $manager->persist($article);
        }
        $manager->flush();
    }
}
