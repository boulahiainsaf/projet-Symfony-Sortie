<?php

namespace App\Services;

use App\Entity\Etat;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;

class Historiser
{
    public function actualiseEtatSorties(array $sorties, EtatRepository $etatRepository, EntityManagerInterface $entityManager)
    {

        foreach ($sorties as $sortie) {

            //1. calculer dateTerminee = dateHeureDebut + durée (en seconds)
            $dateHeureDebutInSeconds = $sortie->getDateHeureDebut()->getTimestamp();
            $dureeSeconds = $sortie->getDuree() * 60;
            $dateTermineeInSeconds = $dateHeureDebutInSeconds + $dureeSeconds;

            $dateLimiteInscriptionInSeconds = $sortie->getDateLimiteInscription()->getTimestamp();

            //2. calculer aujourdhui en seconds
            $currentTimeinSeconds = time();
            //3. calculer 1 mois en seconds
            $mois = 30 * 24 * 60 * 60;

            //A. Set etat historisee pour sorties finies depuis pluis de 1 mois
            $etatHistorisee = $etatRepository->findEtatByLibelle(Etat::HISTORISEE);
            if (($currentTimeinSeconds - $dateTermineeInSeconds) > $mois and $sortie->getEtat() != $etatHistorisee) {
                $sortie->setEtat($etatHistorisee);

                //B. Set etat passee
            } elseif (($currentTimeinSeconds - $dateTermineeInSeconds) > 0 and $sortie->getEtat() != $etatHistorisee) {
                $etatPassee = $etatRepository->findEtatByLibelle(Etat::PASSEE);
                $sortie->setEtat($etatPassee);
            //C. Set etat Cloturee
            } elseif ($dateLimiteInscriptionInSeconds < $currentTimeinSeconds and $dateHeureDebutInSeconds > $currentTimeinSeconds) {
                $etatCloturee = $etatRepository->findEtatByLibelle(Etat::CLOTUREE);
                $sortie->setEtat($etatCloturee);

                //D. Set etat en cours
            } elseif ($dateHeureDebutInSeconds < $currentTimeinSeconds and $dateTermineeInSeconds > $currentTimeinSeconds) {
                $etatEnCours = $etatRepository->findEtatByLibelle(Etat::ENCOURS);
                $sortie->setEtat($etatEnCours);
            }


            $entityManager->persist($sortie);
            $entityManager->flush();


        }


    }
}