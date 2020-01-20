<?php

namespace App\DataFixtures;

use App\Entity\Bookin;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BookinFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        /*Creer 3 categorie*/

        for ($i=0; $i <=3 ; $i++) { 
            
            $category = new Category();

            $category->setTitle($faker->sentence())
                     ->setDescription($faker->sentence());

                $manager->persist($category);



        /*Creer  des bookin dans la categorie au moins 5*/

            for ($j=0; $j <=mt_rand(3, 5) ; $j++) { 
                    
                $bookin = new Bookin();

                $content = '<p>'. join($faker->paragraphs(2), '</p><p>'). '</p>';

                    $bookin->setTitle($faker->sentence())
                            ->setContent($faker->sentence())
                            ->setImage($faker->imageUrl($width = 350, $height = 200))
                            ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                            ->setCategory($category);

                  $manager->persist($bookin);


            /*Creation des commentaires */
            
                for ($l=0; $l <= mt_rand(2, 7) ; $l++) { 
                    $comment = new Comment();

                    $content = '<p>'. join($faker->paragraphs(3), '</p><p>'). '</p>';
                    $days = (new \DateTime())->diff($bookin->getCreatedAt())->days;

                    $comment->setAuteur($faker->name)
                            ->setContent($faker->sentence())
                            ->setCreatedAt($faker->dateTimeBetween('-'. $days. 'days'))
                            ->setBookin($bookin);

                    $manager->persist($comment);

                }
            }
        }
        

        $manager->flush();
    }
}
