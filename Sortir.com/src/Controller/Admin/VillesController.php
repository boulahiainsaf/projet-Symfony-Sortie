<?php

namespace App\Controller\Admin;

use App\Entity\Ville;
use App\Form\FilterVillesType;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use App\Services\FilterVilles;
use Doctrine\ORM\EntityManagerInterface;
use MobileDetectBundle\DeviceDetector\MobileDetectorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin/villes", name="admin_villes")
 */
class VillesController extends AbstractController
{
    /**
     * @Route("/", name="")
     */
    public function villes(VilleRepository $villeRepository,
                           Request $request,
                           MobileDetectorInterface $mobileDetector,
                           EntityManagerInterface $entityManager) : Response
    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            //1.recherche de toutes les villes existants
            $villes = $villeRepository->findAll();

            //2. creation form pour filtrer villes par nom
            $filterVilles = new FilterVilles();
            $villesForm = $this->createForm(FilterVillesType::class, $filterVilles);
            $villesForm->handleRequest($request);

            //3. Création form pour ajouter Ville
            $newVille = new Ville();
            $ajouterVilleForm = $this->createForm(VilleType::class, $newVille);
            $ajouterVilleForm->handleRequest($request);

            //Traitement formulaire recherche par nom ville
            if ($villesForm->isSubmitted() and $villesForm->isValid()) {
                $data = $villesForm->getData()->getNomVillesContient();
                $filteredList = $villeRepository->findByNomContient($data);

                return $this->render('admin/villes.html.twig', [
                    'villes' => $filteredList,
                    'villesForm' => $villesForm->createView(),
                    'ajouterVilleForm' => $ajouterVilleForm->createView()
                ]);
            }

            //traitement formulaire ajouter ville
            if ($ajouterVilleForm->isSubmitted() and $ajouterVilleForm->isValid()) {
                $entityManager->persist($newVille);
                $entityManager->flush();
                $this->addFlash('succes', 'La ville a été ajouté avec succès');
                return $this->redirectToRoute('admin_villes');


            }

            if (isset($_POST['modifier'])) {

                $nouveauNomVille = filter_input(INPUT_POST, 'nomVille', FILTER_SANITIZE_STRING);
                $nouveauCpVille = filter_input(INPUT_POST, 'codePostal', FILTER_SANITIZE_STRING);
                $idVille = $_POST['idVille'];
                $ville = $villeRepository->find($idVille);
                $vieuxNomVille = $ville->getNom();
                $vieuxCpVille = $ville->getCodePostal();

                if (strlen(trim($nouveauNomVille)) < 3) {
                    $this->addFlash('echec', 'le nom doit avoir au moins 3 caracteres');
                    return $this->redirectToRoute('admin_villes');
                } elseif (strlen(trim($nouveauCpVille)) < 5) {
                    $this->addFlash('echec', 'le cp doit contenir 5 caracteres');
                    return $this->redirectToRoute('admin_villes');
                } else {

                    try {
                        $ville->setNom($nouveauNomVille);
                        $ville->setCodePostal($nouveauCpVille);
                        $entityManager->flush();
                        $this->addFlash('succes', 'La ville a été modifiée!');
                        return $this->redirectToRoute('admin_villes');
                    } catch (\Exception $e) {
                        $this->addFlash('echec', 'cette ne peut pas être modifiée');
                        return $this->redirectToRoute('admin_villes');
                    }
                }


            }

            return $this->render('admin/villes.html.twig', [
                'villes' => $villes,
                'villesForm' => $villesForm->createView(),
                'ajouterVilleForm' => $ajouterVilleForm->createView()
            ]);
        }
    }

    /**
     * @Route("/supprimer/{id}", name="_supprimer")
     */

    public function supprimer(int $id,
                              VilleRepository $villeRepository,
                              MobileDetectorInterface $mobileDetector,
                              EntityManagerInterface $entityManager)
    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            $ville = $villeRepository->find($id);
            $nomVille = $ville->getNom();

            try {
                $entityManager->remove($ville);
                $entityManager->flush();
                $this->addFlash('succes', 'La ville ' . strtoupper($nomVille) . ' a été supprimée!');
                return $this->redirectToRoute('admin_villes');
            } catch (\Exception $e) {
                $this->addFlash('echec', 'cette ne peut pas être supprimée, veuillez vérifier si elle est attachée à une sortie');
                return $this->redirectToRoute('admin_villes');
            }

        }
    }

}
