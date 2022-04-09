<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Form\AnnulerType;
use App\Repository\CampusRepository;
use App\Form\FilterType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\ParticipantRepository;
use App\Services\FilterSorties;
use App\Services\Historiser;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Sortie;
use App\Form\SortieType;
use MobileDetectBundle\DeviceDetector\MobileDetectorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SortieController extends AbstractController
{
    /**
     * @Route("/accueil", name="app_sortie")
     * @param SortieRepository $sortieRepository
     * @return Response
     */
    public function list(SortieRepository $sortieRepository,
                         Request $request,
                         EtatRepository $etatRepository,
                         EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Actualiser état sorties
        $sortiesAActualiser = $sortieRepository->findAll();
        $actualiseEtat = new Historiser();
        $actualiseEtat->actualiseEtatSorties($sortiesAActualiser, $etatRepository, $entityManager);

        /** @var Participant $user */
        $user = $this->getUser();

        $defaultList = $sortieRepository->getAllFromUserCampus($user);
        $filteredList = [];

        $today = new \DateTime();
        $formSubmit = false;

        // FORMULAIRE de filtres
        $filters = new FilterSorties();
        $options = ['user' => $this->getUser()];
        $filtersForm = $this->createForm(FilterType::class, $filters, $options);

        $filtersForm->handleRequest($request);

        if ($filtersForm->isSubmitted() && $filtersForm->isValid()) {
            $data = $filtersForm->getData();
            dump($data);
            $filteredList = $sortieRepository->getFilteredSorties($data, $user);
            $formSubmit = true;
        }
        // FIN FORMULAIRE

        if ($formSubmit && $filteredList && count($filteredList) > 0) {
            $displayList = $filteredList;
        } else if ($formSubmit && count($filteredList) == 0) {
            $displayList = null;
        } else {
            $displayList = $defaultList;
        }
// récuperer sorties inscrites pour un utilisateur / écran téléphone
        $userSortiesList = $sortieRepository->getUserSortiesAdmission($user);
        return $this->render('sortie/accueil.html.twig', [
            'displayList' => $displayList,
            'userSortiesList' => $userSortiesList,
            'user' => $user,
            'today' => $today,
            'filterForm' => $filtersForm->createView()
        ]);
    }

    /**
     * @Route("/inscription/{id}", name="app_sortie_inscription")
     */
    public function inscription(int $id,
                                EntityManagerInterface $entityManager,
                                Request $request,
                                SortieRepository $sortieRepository,
                                ParticipantRepository $participantRepository,
                                MobileDetectorInterface $mobileDetector): Response
    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {

            $sortie = $sortieRepository->find($id);

            /** @var Participant $user */
            $user = $this->getUser();
            $this->denyAccessUnlessGranted('inscription', $sortie);

            $sortie->addParticipant($user);
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('succes', 'inscrit à la sortie correctement');


            return $this->redirectToRoute('app_sortie');
        }


    }


    /**
     * @Route("/create", name="app_create")
     */
    public function create(Request $request,
                           VilleRepository $villeRepository,
                           EntityManagerInterface $entityManager,
                           ParticipantRepository $participantRepository,
                           EtatRepository $etatRepository,
                           CampusRepository $campusRepository,
                           MobileDetectorInterface $mobileDetector): Response
    {
        dump($mobileDetector->isMobile());
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            if (!$this->getUser()) {
                return $this->redirectToRoute('app_login');
            }
            $lieu1 = new Lieu();

            $organisateur = $participantRepository->findByEmail($this->getUser()->getUserIdentifier());
            $sortie = new Sortie();
            $sortie->setCampus($campusRepository->find($organisateur->getCampus()));
            $sortie->setOrganisateur($organisateur);
            $sortieForm = $this->createForm(SortieType::class, $sortie);
            $sortieForm->handleRequest($request);
            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
                $a = "";
                if (isset($_POST['active'])) {
                    $a = Etat::CREE;
                } else {
                    $a = Etat::OUVERTE;
                }

                $etat = $etatRepository->findEtatByLibelle($a);
                $sortie->setEtat($etat);
                if ($sortieForm->get("lieuForm")->get("nom")->getData() == null && $sortieForm->get("lieuForm")->get("rue")->getData() == null) {

                    $entityManager->persist($sortie);
                    $entityManager->flush();

                    $this->addFlash('succes', 'Sortie est bien ajouter ');
                    return $this->redirectToRoute('app_sortie');
                } else {
                    $lieu = new Lieu();
                    $lieu->setNom($sortieForm->get('lieuForm')->get('nom')->getData());
                    $lieu->setVille($sortieForm->get('ville')->getData());
                    $lieu->setRue($sortieForm->get('lieuForm')->get('rue')->getData());
                    $lieu->setLatitude($sortieForm->get('lieuForm')->get('latitude')->getData());
                    $lieu->setLongitude($sortieForm->get('lieuForm')->get('longitude')->getData());
                    $sortie->setLieu($lieu);
                    $entityManager->persist($lieu);
                    $entityManager->flush();
                    $entityManager->persist($sortie);
                    $entityManager->flush();

                    $this->addFlash('succes', 'Sortie est bien ajouter ');
                    return $this->redirectToRoute('app_sortie');
                }

            }
        }
        return $this->render('Sortie/create.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'organisateur' => $organisateur,

        ]);
    }

    /**
     * @Route("/desistement/{id}", name="app_sortie_desistement")
     */
    public function desistement(int $id,
                                EntityManagerInterface $entityManager,
                                Request $request,
                                SortieRepository $sortieRepository,
                                ParticipantRepository $participantRepository,
                                MobileDetectorInterface $mobileDetector): Response
    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            $sortie = $sortieRepository->find($id);
            $this->denyAccessUnlessGranted('desistement', $sortie);

            /** @var Participant $user */
            $user = $this->getUser();

            $sortie->removeParticipant($user);
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('succes', 'Vous vous êtes desinscrit!');
            return $this->redirectToRoute('app_sortie');
        }
    }

    /**
     * @Route("/afficher/{id}", name="app_sortie_afficher")
     */
    public function afficher(int $id,
                             EntityManagerInterface $entityManager,
                             Request $request,
                             SortieRepository $sortieRepository,
                             ParticipantRepository $participantRepository): Response
    {

        $sortie = $sortieRepository->find($id);
        $this->denyAccessUnlessGranted('afficher', $sortie);

        $participants = $sortie->getParticipants();

        return $this->render('sortie/afficher.html.twig',
            ['sortie' => $sortie,
                'participants' => $participants]);

    }


    /**
     * @Route("/annuler/{id}", name="app_sortie_annuler")
     */
    public function annuler(int $id,
                            EntityManagerInterface $entityManager,
                            Request $request,
                            SortieRepository $sortieRepository,
                            EtatRepository $etatRepository,
                            ParticipantRepository $participantRepository,
                            MobileDetectorInterface $mobileDetector): Response
    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            $sortie = $sortieRepository->find($id);
            $this->denyAccessUnlessGranted('annuler', $sortie);

            $etat = $etatRepository->findEtatByLibelle(Etat::ANNULEE);

            $infosA = $sortie->getInfosSortie();
            $sortie->setInfosSortie("");
            $annulerForm = $this->createForm(AnnulerType::class, $sortie);
            $annulerForm->handleRequest($request);
            $infosB = $sortie->getInfosSortie();


            if ($annulerForm->isSubmitted() && $annulerForm->isValid() /*&& $sortie->getEtat()->getLibelle() != 'Activité en cours'*/) {

                $sortie->setEtat($etat);

                $sortie->setInfosSortie(nl2br("MOTIF D'ANNULATION: $infosB  \n $infosA"));

                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('succes', 'Votre sortie a été annulée');
                return $this->redirectToRoute('app_sortie');

            }
            return $this->render('sortie/annuler.html.twig',
                ['sortie' => $sortie,
                    'annulerForm' => $annulerForm->createView()
                ]);
        }
    }

    /**
     * @Route ("/modifier/{id}",name="app_modif")
     */
    public function modifier(int $id,
                             Request $request,
                             SortieRepository $sortieRepository,
                             LieuRepository $lieuRepository,
                             EtatRepository $etatRepository,
                             ParticipantRepository $participantRepository,
                             CampusRepository $campusRepository,
                             EntityManagerInterface $entityManager,
                             MobileDetectorInterface $mobileDetector): Response
    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            if (!$this->getUser()) {
                return $this->redirectToRoute('app_login');
            }
            $organisateur = $participantRepository->findByEmail($this->getUser()->getUserIdentifier());
            $sortie = new Sortie();
            $sortie->setCampus($campusRepository->find($organisateur->getCampus()));
            $sortie->setOrganisateur($organisateur);

            $sortie = $sortieRepository->find($id);
            $this->denyAccessUnlessGranted('modifier', $sortie);
            $lieu = $lieuRepository->find($sortie->getLieu());


            $sortieForm = $this->createForm(SortieType::class, $sortie);
            $sortieForm->handleRequest($request);
            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
                $a = "";
                if (isset($_POST['active'])) {
                    $a = Etat::CREE;
                } else {
                    $a = Etat::OUVERTE;
                }
                $etat = $etatRepository->findEtatByLibelle($a);
                $sortie->setEtat($etat);
                if ($sortieForm->get("lieuForm")->get("nom")->getData() == null && $sortieForm->get("lieuForm")->get("rue")->getData() == null) {
                    $entityManager->persist($sortie);
                    $entityManager->flush();

                    $this->addFlash('succes', 'la Sortie ' . strtoupper($sortie->getNom()) . ' a été modifiée avec succes');
                    return $this->redirectToRoute('app_sortie');
                } else {
                    $lieu1 = new Lieu();
                    $lieu1->setNom($sortieForm->get('lieuForm')->get('nom')->getData());
                    $lieu1->setVille($sortieForm->get('ville')->getData());
                    $lieu1->setRue($sortieForm->get('lieuForm')->get('rue')->getData());
                    $lieu1->setLatitude($sortieForm->get('lieuForm')->get('latitude')->getData());
                    $lieu1->setLongitude($sortieForm->get('lieuForm')->get('longitude')->getData());
                    $sortie->setLieu($lieu1);
                    $entityManager->persist($lieu1);
                    $entityManager->flush();
                    $entityManager->persist($sortie);
                    $entityManager->flush();

                    $this->addFlash('succes', 'Sortie est bien ajouter ');
                    return $this->redirectToRoute('app_sortie');
                }
            }



        return $this->render('Sortie/modifierSortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'sortie' => $sortie,
            'lieu' => $lieu,
            'organisateur' => $organisateur,

        ]);
    }
}

    /**
     * @Route ("/supprimer/{id}",name="app_supprimer")
     */

    public function supprimer(int $id,
                              SortieRepository $sortieRepository,
                              EntityManagerInterface $entityManager,
                              MobileDetectorInterface $mobileDetector)
    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            $sortie = $sortieRepository->find($id);
            $this->denyAccessUnlessGranted('annuler', $sortie);
            try {
                $entityManager->remove($sortie);
                $entityManager->flush();
                $this->addFlash('succes', 'La sortie a été supprimée!');
                return $this->redirectToRoute('app_sortie');
            } catch (\Exception $e) {
                $this->addFlash('echec', 'la sortie ne peut pas être supprimé');

                return $this->redirectToRoute('app_sortie');
            }

        }
    }
}
