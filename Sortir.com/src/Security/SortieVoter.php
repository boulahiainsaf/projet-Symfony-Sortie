<?php

namespace App\Security;

use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use function PHPUnit\Framework\throwException;

class SortieVoter extends Voter
{

    const AFFICHER = 'afficher';
    const ANNULER = 'annuler';
    const INSCRIPTION = 'inscription';
    const DESISTEMENT = 'desistement';
    const CREATE = 'create';
    const MODIFIER = 'modifier';

//    /**
//     * @inheritDoc
//     */
    protected function supports(string $attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::AFFICHER, self::ANNULER, self::INSCRIPTION, self::DESISTEMENT, self::CREATE, self::MODIFIER])) {
            return false;
        }

        // only vote on `Sortie` objects
        if (!$subject instanceof Sortie) {
            return false;
        }
        return true;
    }

//    /**
//     * @inheritDoc
//     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof Participant) {
            // the user must be logged in; if not, deny access
            return false;
        }
        // you know $subject is a Sortie object, thanks to `supports()`
        /** @var Sortie $sortie */
        $sortie = $subject;

        switch ($attribute) {
            case self::AFFICHER:
                return $this->canAfficher($sortie, $user);
            case self::ANNULER:
                return $this->canAnnuler($sortie, $user);
            case self::INSCRIPTION:
                return $this->canInscrire($sortie, $user);
            case self::DESISTEMENT:
                return $this->canDesister($sortie, $user);
            case self::CREATE:
                return $this->canCreate($sortie, $user);
            case self::MODIFIER:
                return $this->canModifier($sortie, $user);
        }
        throw new \LogicException('This code should not be reached!');

    }

    private function canAfficher(Sortie $sortie, Participant $user)
    {
        //On ne peut consulter les sorties historisées
        if ($sortie->getEtat()->getLibelle() == Etat::HISTORISEE) {
//            throw new \Exception("Les sorties Historisées ne peuvent être consultées");
            return false;
        }
        //Seulement un organisateur peut voir une sortie en état Crée
        $etatCree = $sortie->getEtat()->getLibelle() == Etat::CREE;
        $organisateur = $sortie->getOrganisateur();
        if ($etatCree and $organisateur!=$user) return false;

        return true;

    }

    private function canAnnuler(Sortie $sortie, Participant $user)
    {

        //definition des etats valides avant annulation
        $etatsOkAnnulation = [Etat::OUVERTE, Etat::CLOTUREE, Etat::CREE];
        if (!in_array($sortie->getEtat()->getLibelle(), $etatsOkAnnulation)) return false;

        //seleument l'organisateur peut annuler la sortie
        $estOrganisateur = $sortie->getOrganisateur() == $user;
        if (!$estOrganisateur and !$user->getAdministrateur() ) return false;

        return true;
    }

    private function canInscrire(Sortie $sortie, Participant $user)
    {
        //verifications sur caracteristiques sortie (nbPlaces, etat, date)
        $dateInscriptionOk = $sortie->getDateLimiteInscription() > new \DateTime();
        $restePlaces = $sortie->getParticipants()->count() < $sortie->getNbInscriptionsMax();
        $etatOk = $sortie->getEtat()->getLibelle() == Etat::OUVERTE;

        if (!$restePlaces or !$dateInscriptionOk or !$etatOk) {
            return false;
        }

        //verifications sur caracteristiques participant (déjà inscrit?)
        if ($this->dejaInscrit($sortie, $user)) {
            return false;
        }
        return true;
    }

    private function canDesister(Sortie $sortie, Participant $user)
    {
        //definition des etats valides pour desinscriptio
        $etatsOkDesinscrire = [Etat::OUVERTE, Etat::CLOTUREE];
        if (!in_array($sortie->getEtat()->getLibelle(), $etatsOkDesinscrire)) return false;

        //un participant ne peut se desinscrire que s'il est déjà inscrit!
        if ($this->dejaInscrit($sortie, $user)) {
            return true;
        }
        return false;
    }

    private function canCreate(Sortie $sortie, Participant $user)
    {
        return true;
    }

    private function canModifier(Sortie $sortie, Participant $user)
    {
        if ($user->getAdministrateur()) return true;
        $estOrganisateur = $sortie->getOrganisateur() == $user;
        $etatsOkModifier = [Etat::CREE, Etat::OUVERTE];
        if (!in_array($sortie->getEtat()->getLibelle(), $etatsOkModifier)) return false;



        if (!$estOrganisateur) return false;

        return true;
        
    }

    //function utilisee dans canInscrire et canDesister
    private function dejaInscrit(Sortie $sortie, Participant $user)
    {
        $dejaInscrit = false;
        $participantsInscrits = $sortie->getParticipants();
        foreach ($participantsInscrits as $p)
        {
            if ($p == $user) $dejaInscrit=true;
        }
        return $dejaInscrit;
    }




}