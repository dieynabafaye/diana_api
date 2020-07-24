<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Commune;
use App\Entity\Departement;
use App\Repository\RegionRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $repo;
    private $encoder;
    public function __construct(RegionRepository $repo, UserPasswordEncoderInterface $encoder){
    $this->repo=$repo;
    $this->encoder=$encoder;

    }
    
    public function load(ObjectManager $manager)
    {
        $regions=$this->repo->findAll();
        $faker = Factory::create('fr_FR');
        //Insertion des Regions
        foreach($regions as $region){

            $departement=new Departement();
            $departement->setCode($faker->postcode)
                        ->setNom($faker->city)
                        ->setRegion($region);
            $manager->persist( $departement);
            //Pour chaque Département, on insére 10 Communes
            for ($i=0; $i <10 ; $i++) {
                $commune=new Commune();
                $commune->setCode($faker->postcode)
                        ->setNom($faker->city)
                        ->setDepartement($departement);
                $manager->persist($commune);
            }
        }
        $manager->flush();

        $profils =["ADMIN","FORMATEUR" ,"APPRENANT" ,"CM"];
        foreach ($profils as $key => $libelle) {
            $profil =new Profil() ;
            $profil ->setLibelle ($libelle );
            $manager ->persist($profil);
            $manager ->flush();
            for ($i=1; $i <=3 ; $i++) {
                $user = new User();
                $user ->setProfil ($profil);
                $user ->setLogin (strtolower ($libelle ).$i);
                $user ->setNomComplet($faker->name());
                //Génération des Users
                $password = $this->encoder->encodePassword ($user, 'pass_1234' );
                $user ->setPassword ($password );

            $manager ->persist($user);

            }
            $manager ->flush();
        }

    }
}
