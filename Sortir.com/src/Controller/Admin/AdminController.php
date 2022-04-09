<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use App\Form\AddParticipantType;
use App\Form\ParticipantsType;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Services\FileUploader;
use App\Services\FilterParticipants;
use App\Services\FilterSorties;
use Doctrine\ORM\EntityManagerInterface;
use MobileDetectBundle\DeviceDetector\MobileDetectorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     * @param ParticipantRepository $participantRepository
     * @param CampusRepository $campusRepository
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param FileUploader $file_uploader
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     * @throws \Doctrine\DBAL\Exception
     */

    public function userList(ParticipantRepository $participantRepository,
                             EntityManagerInterface $entityManager,
                             MobileDetectorInterface $mobileDetector,
                             Request $request)

    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            $formSubmit = false;
            // FORMULAIRE de filtres
            $filters = new FilterParticipants();
            $filtersForm = $this->createForm(ParticipantsType::class, $filters);
            $filtersForm->handleRequest($request);
            if ($filtersForm->isSubmitted() && $filtersForm->isValid()) {
                $data = $filtersForm->getData();
                dump($data);
                $filteredList = $participantRepository->getSearchUsers($data);
                $formSubmit = true;
            }
            // FIN FORMULAIRE
            if ($formSubmit && $filteredList && count($filteredList) > 0) {
                $displayList = $filteredList;
            } else if ($formSubmit && count($filteredList) == 0) {
                $displayList = null;
            } else {
                $displayList = $participantRepository->findBy([], ['id' => 'DESC']);
            }

            return $this->render('admin/user/list.html.twig', [
                'users' => $displayList,
                'filterForm' => $filtersForm->createView()
            ]);
        }
    }

    /**
     * @Route("/addParticipant", name="addParticipant")
     */
    public function addParticipant (Request $request,
                                    EntityManagerInterface $entityManager,
                                    UserPasswordHasherInterface $passwordHasher,
                                    MobileDetectorInterface $mobileDetector
    ) : Response
    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            $participant = new Participant();
            $addParticipantForm = $this->createForm(AddParticipantType::class, $participant);
            $addParticipantForm->handleRequest($request);

            if ($addParticipantForm->isSubmitted() and $addParticipantForm->isValid()) {
                //traitement mot de passe
                $plaintextPassword = $participant->getPassword();
                $hashedPassword = $passwordHasher->hashPassword(
                    $participant,
                    $plaintextPassword
                );
                $participant->setMotPasse($hashedPassword);

                $entityManager->persist($participant);
                $entityManager->flush();
                $this->addFlash('succes', 'Le participant a été ajouté avec succès');
                return $this->redirectToRoute('admin_addParticipant');
            }

            return $this->render('admin/addParticipant.html.twig', [
                'addParticipantForm' => $addParticipantForm->createView(),
            ]);
        }
    }

    /**
     * @Route("/update-user/{id}", name="user_update_status")
     */
    public function updateStatus(int $id,
                                 ParticipantRepository $participantRepository,
                                 EntityManagerInterface $entityManager,
                                 MobileDetectorInterface $mobileDetector,
                                 Request $request): Response
    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            if (!$this->getUser()) {
                return $this->redirectToRoute('app_login');
            }

            $participant = $participantRepository->find($id);

            if (!$participant) {
                throw $this->createNotFoundException('ce profil nexiste pas');
            }

                     if($participant->getActif()){
                $participant->setActif(false);
            }else{
                $participant->setActif(true);
            }
            try {
                $entityManager->persist($participant);
                $entityManager->flush();
                if ($request->isXmlHttpRequest()) {
                    dump($participant->getActif());
                    $json_array = [
                        'status' => $participant->getActif(),
                        'message' => 'L\'utilisateur ' . $participant->getPseudo() . 'est '.$participant->getActif()?'activé!':'desactivé!',
                        'id'=> $id
                    ];
                    return $this->json($json_array);
                    //return $this->json($id);
                }else{
                    $this->addFlash('succes', 'L\'utilisateur ' . $participant->getPseudo() . 'est '.$participant->getActif()?'activé!':'desactivé!');
                    return $this->redirectToRoute('admin_dashboard');
                }
            } catch (\Exception $e) {
                $this->addFlash('echec', $e->getMessage());
                return $this->redirectToRoute('admin_dashboard');
            }
        }
    }
    /**
     * @Route("/delete-user/{id}", name="user_delete")
     */
    public function deleteUser(int $id,
                               ParticipantRepository $participantRepository,
                               EntityManagerInterface $entityManager,
                               MobileDetectorInterface $mobileDetector,
                               Request $request): Response
    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            if (!$this->getUser()) {
                return $this->redirectToRoute('app_login');
            }

            try{
            $participant = $participantRepository->find($id);
            $entityManager->remove($participant);
            $entityManager->flush();
                if ($request->isXmlHttpRequest()) {
                       $json_array = [
                        'status' => 'succes',
                        'message' => 'L\'utilisateur '. $participant->getPseudo() . ' a été bien supprimé!',
                        'id'=> $id
                    ];
                    return $this->json($json_array);
                    //return $this->json($id);
                }else{
                     $this->addFlash('succes', 'L\'utilisateur ' . $participant->getPseudo() . ' a été supprimé!');
                    return $this->redirectToRoute('admin_dashboard');
                }

              } catch (\Exception $e) {
                dump('error');
                if ($request->isXmlHttpRequest()) {
                    $json_array = [
                        'status' => 'echec',
                        'message' => 'L\'utilisateur '. $participant->getPseudo() . ' ne peut pas étre supprimé - il est participant ou organisateur d\'une sortie!',
                        'id'=> null
                    ];
                    return $this->json($json_array);
                }
                $this->addFlash('echec', 'L\'utilisateur ' . $participant->getPseudo() . ' ne peut pas étre supprimé - il est participant ou organisateur d\'une sortie!');
                return $this->redirectToRoute('admin_dashboard');
            }
        }
    }

    /**
     * @Route("/delete-users", name="users_delete_select")
     */
    public function deleteUsers(ParticipantRepository $participantRepository,
                               EntityManagerInterface $entityManager,
                               MobileDetectorInterface $mobileDetector,
                               Request $request): Response
    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            if (!$this->getUser()) {
                return $this->redirectToRoute('app_login');
            }
            // **** TEST
            $data = $request->request->get('users');
            dump($data);
            $users=[];
            if ($request->isXmlHttpRequest()) {
                dump('coucou ajax');

               $data = $request->request->get('dataSend');
               dump($data);
                // $data = json_decode($data,true); // for json object
                $json_array = [
                    'status' => 'echec',
                    'message' => ' L\'utilisateur ne peut pas étre supprimé - il est participant ou organisateur d\'une sortie!',
                    'id'=> null
                ];
                return $this->json($json_array);
            }
            /*
             * //TODO boucle array récuperé - trouver des utilisateurs un par un et voir si la suppression est possible
            try{
                $participant = $participantRepository->find($id);
                $entityManager->remove($participant);
                $entityManager->flush();
                if ($request->isXmlHttpRequest()) {
                    $json_array = [
                        'status' => 'succes',
                        'message' => 'L\'utilisateur '. $participant->getPseudo() . ' a été bien supprimé!',
                        'id'=> $id
                    ];
                    return $this->json($json_array);
                    //return $this->json($id);
                }else{
                    $this->addFlash('succes', 'L\'utilisateur ' . $participant->getPseudo() . ' a été supprimé!');
                    return $this->redirectToRoute('admin_dashboard');
                }

            } catch (\Exception $e) {
                dump('error');
                if ($request->isXmlHttpRequest()) {
                    $json_array = [
                        'status' => 'echec',
                        'message' => 'L\'utilisateur '. $participant->getPseudo() . ' ne peut pas étre supprimé - il est participant ou organisateur d\'une sortie!',
                        'id'=> null
                    ];
                    return $this->json($json_array);
                }
                $this->addFlash('echec', 'L\'utilisateur ' . $participant->getPseudo() . ' ne peut pas étre supprimé - il est participant ou organisateur d\'une sortie!');
                return $this->redirectToRoute('admin_dashboard');
            }*/
        }
    }




}
