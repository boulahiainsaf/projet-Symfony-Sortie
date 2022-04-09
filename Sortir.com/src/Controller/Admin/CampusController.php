<?php

namespace App\Controller\Admin;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Form\FilterCampusType;
use App\Repository\CampusRepository;
use App\Services\FilterCampus;
use Doctrine\ORM\EntityManagerInterface;
use MobileDetectBundle\DeviceDetector\MobileDetectorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//use function Sodium\crypto_box_keypair_from_secretkey_and_publickey;

/**
 * @Route("/admin/campus", name="admin_campus")
 */
class CampusController extends AbstractController
{
    /**
     * @Route("/", name="")
     */
    public function campus(CampusRepository       $campusRepository,
                           Request                $request,
                           MobileDetectorInterface $mobileDetector,
                           EntityManagerInterface $entityManager): Response
    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            //1.recherche de tous les campus existants
            $campus = $campusRepository->findAll();

            //2.creation form pour filtrer campus par nom
            $filtersCampus = new FilterCampus();
            $campusForm = $this->createForm(FilterCampusType::class, $filtersCampus);
            $campusForm->handleRequest($request);


            //3. creation form pour ajouter un campus
            $newCampus = new Campus();
            $ajouterCampusForm = $this->createForm(CampusType::class, $newCampus);
            $ajouterCampusForm->handleRequest($request);

            //Traitement formulaire recherche par nom campus
            if ($campusForm->isSubmitted() and $campusForm->isValid()) {
                $data = $campusForm->getData()->getNomCampusContient();
                $filteredList = $campusRepository->findByNomContient($data);

                return $this->render('admin/campus.html.twig', [
                    'campus' => $filteredList,
                    'campusForm' => $campusForm->createView(),
                    'ajouterCampusForm' => $ajouterCampusForm->createView()
                ]);
            }

            //traitement formulaire ajouter campus
            if ($ajouterCampusForm->isSubmitted() and $ajouterCampusForm->isValid()) {
                $entityManager->persist($newCampus);
                $entityManager->flush();
                $this->addFlash('succes', 'Le campus a été ajouté avec succès');
                return $this->redirectToRoute('admin_campus');

            }

            if (isset($_POST['modifier'])) {

                $nouveauNomCampus = filter_input(INPUT_POST, 'nomCampus', FILTER_SANITIZE_STRING);
                $idCampus = $_POST['idCampus'];
                $campus = $campusRepository->find($idCampus);
                $vieuxNomCampus = $campus->getNom();

                if (strlen(trim($nouveauNomCampus)) < 3) {
                    $this->addFlash('echec', 'le nom doit avoir au moins 3 caracteres');
                    return $this->redirectToRoute('admin_campus');
                } else {

                    try {
                        $campus->setNom($nouveauNomCampus);
                        $entityManager->flush();
                        $this->addFlash('succes', 'Le campus ' . strtoupper($vieuxNomCampus) . ' a été renommé à ' . strtoupper($nouveauNomCampus));
                        return $this->redirectToRoute('admin_campus');
                    } catch (\Exception $e) {
                        $this->addFlash('echec', 'le campus ne peut pas être modifié');
                        return $this->redirectToRoute('admin_campus');
                    }
                }


            }

            return $this->render('admin/campus.html.twig', [
                'campus' => $campus,
                'campusForm' => $campusForm->createView(),
                'ajouterCampusForm' => $ajouterCampusForm->createView()
            ]);
        }
    }

    /**
     * @Route("/supprimer/{id}", name="_supprimer")
     */

    public function supprimer(int                    $id,
                              CampusRepository       $campusRepository,
                              MobileDetectorInterface $mobileDetector,
                              EntityManagerInterface $entityManager)
    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            $campus = $campusRepository->find($id);
            $nomCampus = $campus->getNom();

            try {
                $entityManager->remove($campus);
                $entityManager->flush();
                $this->addFlash('succes', 'Le campus ' . strtoupper($nomCampus) . ' a été supprimé!');
                return $this->redirectToRoute('admin_campus');
            } catch (\Exception $e) {
                $this->addFlash('echec', 'le campus ne peut pas être supprimé, vérifiez s\'il est attaché à une sortie');

                return $this->redirectToRoute('admin_campus');
            }
        }
    }


}
