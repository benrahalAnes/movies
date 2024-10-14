<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $movie1 = new Movie();
        $movie1->setTitle('Dark Knight');
        $movie1->setReleaseYear(2008);
        $movie1->setDescription('This is description of Dark Knight');
        $movie1->setImagePath('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSYAcCQTp6jMR-GP6N8-lpccALnMtVyeX6LqA&s');
        #add actors to movie
        $movie1->addActor($this->getReference('actor_1'));
        $movie1->addActor($this->getReference('actor_2'));
        
        $manager->persist($movie1);
        

        $movie2 = new Movie();
        $movie2->setTitle('Avengers: Endgame');
        $movie2->setReleaseYear(2019);
        $movie2->setDescription('This is description of Avengers: Endgame');
        $movie2->setImagePath('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSYAcCQTp6jMR-GP6N8-lpccALnMtVyeX6LqA&s');
        
        $movie2->addActor($this->getReference('actor_3'));
        $movie2->addActor($this->getReference('actor_4'));

        $manager->persist($movie2);

        $manager->flush();

        
    }
}
