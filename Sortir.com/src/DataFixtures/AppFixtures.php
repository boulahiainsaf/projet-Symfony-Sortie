<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\VilleRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// class AppFixtures extends Fixture implements DependentFixtureInterface
class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    private CampusRepository $campusRepository;
    private VilleRepository $villeRepository;
    private ParticipantRepository $participantRepository;
    private LieuRepository $lieuRepository;
    private EtatRepository $etatRepository;


    public function __construct(
        UserPasswordHasherInterface $hasher,
        CampusRepository $campusRepository,
        VilleRepository $villeRepository,
        ParticipantRepository $participantRepository,
        LieuRepository $lieuRepository,
        EtatRepository $etatRepository)
    {
        $this->hasher = $hasher;
        $this->campusRepository = $campusRepository;
        $this->villeRepository = $villeRepository;
        $this->participantRepository = $participantRepository;
        $this->lieuRepository = $lieuRepository;
        $this->etatRepository = $etatRepository;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = \Faker\Factory::create('fr_FR');

        // load Campus
        $campusArray = ['Amiens', 'Nantes', 'Rennes', 'Paris'];
        foreach($campusArray as $campusName){
            $campus = new Campus();
            $campus->setNom($campusName);
            $manager->persist($campus);
        }
        $manager->flush();

        // load Admin
        $campus = $this->campusRepository->findAll();
        $admin = new Participant();
        $admin->setNom('Dupont');
        $admin->setPrenom('Alfred');
        $admin->setPseudo('admin');
        $admin->setTelephone($faker->numerify('06########'));
        $admin->setEmail('admin@sortir.com');
        $admin->setMotPasse($this->hasher->hashPassword($admin, 'Admin123_'));
        $admin->setAdministrateur(true);
        $admin->setActif(true);
        $admin->setCampus($faker->randomElement($campus));
        $manager->persist($admin);
        $manager->flush();

        // load one User
        $campus = $this->campusRepository->findAll();
        $user = new Participant();
        $user->setNom('Dupont');
        $user->setPrenom('Alfred');
        $user->setPseudo('user');
        $user->setTelephone($faker->numerify('06########'));
        $user->setEmail('user@sortir.com');
        $user->setMotPasse($this->hasher->hashPassword($admin, 'User123_'));
        $user->setAdministrateur(false);
        $user->setActif(true);
        $user->setCampus($faker->randomElement($campus));
        $manager->persist($user);
        $userMe = $user;
        $manager->flush();

                // load Utilisateurs
        $campus = $this->campusRepository->findAll();
        for ($i = 1; $i <= 30; $i++) {
            $user = new Participant();
            $user->setNom($faker->lastName());
            $user->setPrenom($faker->firstName());
            $user->setPseudo($faker->unique()->userName());
            $user->setTelephone($faker->numerify('06########'));
            $user->setEmail($faker->email());
            $user->setMotPasse($this->hasher->hashPassword($user, $faker->regexify("^(?=.[0-9])(?=.[a-z])(?=.[A-Z])(?=.[!@#&()–[{}]:;',?*~$^+=<>]).{8,30}$)")));
            $user->setAdministrateur(false);
            $user->setActif($faker->boolean($chanceOfGettingTrue = 90));
            $user->setCampus($faker->randomElement($campus));
            $manager->persist($user);
        }
        $manager->flush();



        // load Etat
        $etats = ['Créée', 'Ouverte', 'Clôturée', 'Activité en cours', 'Passée', 'Annulée', 'Historisée'];
        foreach($etats as $libelle){
            $etat = new Etat();
            $etat->setLibelle($libelle);
            $manager->persist($etat);
        }
        $manager->flush();


        // load Villes
        for($i=1; $i<=20; $i++){
            $ville = new Ville();
            $ville->setNom($faker->city());
            $ville->setCodePostal($faker->postcode());
            $manager->persist($ville);
        }
        $manager->flush();

        // load Lieu
        $ville = $this->villeRepository->findAll();
        for($i=1; $i<=40; $i++){
            $lieu = new Lieu();
            $lieu->setNom($faker->company());
            $lieu->setRue($faker->streetAddress());
            $lieu->setLatitude($faker->latitude($min = -90, $max = 90));
            $lieu->setLongitude($faker->longitude($min = -180, $max = 180));
            $lieu->setVille($faker->randomElement($ville));
            $manager->persist($lieu);
        }
        $manager->flush();

        // load Sortie
        $organisateur = $this->participantRepository->findAll();
        $lieu = $this->lieuRepository->findAll();
        $etat = $this->etatRepository->findAll();
        for($i=1; $i<=100; $i++){
            $sortie = new Sortie();
            $sortie->setNom($faker->sentence($nbWords = 4, $variableNbWords = true));
            $sortie->setDateHeureDebut($faker->dateTimeBetween('+1 days', '+1 month', 'Europe/Paris'));
            $sortie->setDuree($faker->numberBetween($min = 1, $max = 72));
            $sortie->setDateLimiteInscription($faker->dateTimeBetween('-1 days', $sortie->getDateHeureDebut(), 'Europe/Paris'));
            $sortie->setNbInscriptionsMax($faker->numberBetween($min = 3, $max = 25));
            $sortie->setInfosSortie($faker->paragraph($nbSentences = 5, $variableNbSentences = true));
            $sortie->setOrganisateur($faker->randomElement($organisateur));
            $sortie->setCampus($sortie->getOrganisateur()->getCampus());
            $sortie->setLieu($faker->randomElement($lieu));
            $sortie->setEtat($faker->randomElement($etat));
            $manager->persist($sortie);
        }
        $manager->flush();

        // load Sorties crées par userMe
        $lieu = $this->lieuRepository->findAll();
        $etat = $this->etatRepository->findAll();
        for($i=1; $i<=7; $i++){
            $sortieMe = new Sortie();
            $sortieMe->setNom($faker->sentence($nbWords = 4, $variableNbWords = true));
            $sortieMe->setDateHeureDebut($faker->dateTimeBetween('+1 days', '+1 month', 'Europe/Paris'));
            $sortieMe->setDuree($faker->numberBetween($min = 1, $max = 72));
            $sortieMe->setDateLimiteInscription($faker->dateTimeBetween('-1 days', $sortie->getDateHeureDebut(), 'Europe/Paris'));
            $sortieMe->setNbInscriptionsMax($faker->numberBetween($min = 3, $max = 25));
            $sortieMe->setInfosSortie($faker->paragraph($nbSentences = 5, $variableNbSentences = true));
            $sortieMe->setOrganisateur($userMe);
            $sortieMe->setCampus($sortieMe->getOrganisateur()->getCampus());
            $sortieMe->setLieu($faker->randomElement($lieu));
            $dateNow = new DateTime();
            /*
            if($sortieMe->getDateHeureDebut()>$dateNow->modify('+'.$sortieMe->getDuree().' hours')){
                $sortieMe->setEtat($this->etatRepository->findOneBy(['libelle' => 'Passée']));
            }else if($sortieMe->getDateHeureDebut()>$dateNow->modify('-1 month')){
                $sortieMe->setEtat($this->etatRepository->findOneBy(['libelle' => 'Clôturée']));
            }else if($sortieMe->getDateHeureDebut()>$dateNow && $dateNow<$sortieMe->getDateHeureDebut()->modify('+'.$sortieMe->getDuree().' hours')){
                $sortieMe->setEtat($this->etatRepository->findOneBy(['libelle' => 'Activité en cours']));
            }else if($sortieMe->setDateLimiteInscription()<=$dateNow){
                $sortieMe->setEtat($this->etatRepository->findOneBy(['libelle' => 'Ouverte']));
            }
            else{
                $etats = $this->etatRepository->findBy(['libelle' => 'Créée'], ['libelle' => 'Annulée']);
                $sortieMe->setEtat($faker->randomElement($etats));
            }*/
            $sortieMe->setEtat($faker->randomElement($etat));
            $manager->persist($sortieMe);
        }
        $manager->flush();


    }
/*
    public function getDependencies()
    {
        // TODO: Implement getDependencies() method.
        return[CampusFixtures::class];
    }
*/
}
