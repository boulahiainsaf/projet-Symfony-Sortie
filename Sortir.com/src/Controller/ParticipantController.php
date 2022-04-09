<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use MobileDetectBundle\DeviceDetector\MobileDetectorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


/**
 * @Route ("/participant", name="participant_")
 */
class ParticipantController extends AbstractController
{


    /**
     * @Route("/update", name="update")
     */
    public function update(UserPasswordHasherInterface $passwordHasher,
                           Request                     $request,
                           ParticipantRepository       $participantRepository,
                           EntityManagerInterface      $entityManager,
                           SluggerInterface            $slugger,
                           MobileDetectorInterface $mobileDetector): Response
    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            if (!$this->getUser()) {
                return $this->redirectToRoute('app_login');
            }

            /** @var Participant $participant */
            $participant = $this->getUser();

            $profilForm = $this->createForm(ProfilType::class, $participant);
//        $passwordForm = $this->createForm(ChangePasswordFormType::class);

            $profilForm->handleRequest($request);

            if ($profilForm->isSubmitted() && $profilForm->isValid()) {

                //traitement mot de passe
                $plaintextPassword = $participant->getPassword();
                $hashedPassword = $passwordHasher->hashPassword(
                    $participant,
                    $plaintextPassword
                );
                $participant->setMotPasse($hashedPassword);

                //traitement upload image

                /** @var UploadedFile $imageFile */
                $imageFile = $profilForm->get('imageFile')->getData();
                if ($imageFile) {
                    $extension = $imageFile->guessExtension();
                    $acceptedExtensions = ['jpeg', 'jpg', 'png'];
                    if (!in_array($extension, $acceptedExtensions)) {
                        $this->addFlash('echec', 'le fichier doit être jpeg, jpg ou png');
                        return $this->redirectToRoute('participant_update');
                    }
                    if ($imageFile) {
                        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                        try {
                            $imageFile->move(
                                $this->getParameter('profileImages_directory'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                            $this->addFlash('echec', '$e->getMessage()');
                            return $this->redirectToRoute('app_sortie');
                        }

                        $participant->setImageFile($newFilename);
                    }
                }


                //enregistrement sur BDD
                $entityManager->persist($participant);
                $entityManager->flush();

                $this->addFlash('succes', 'Profil modifié avec succes');
                return $this->redirectToRoute('app_sortie');
            }


            return $this->render('participant/updateProfile.html.twig', [
                'profilForm' => $profilForm->createView(),
//            'passwordForm' =>$passwordForm->createView(),
                'participant' => $participant
            ]);
        }
    }

    /**
     * @Route("/info/{id}", name="info")
     */
    public function info(int $id, ParticipantRepository $participantRepository, MobileDetectorInterface $mobileDetector): Response
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

            return $this->render('participant/infoProfile.html.twig', [
                'participant' => $participant
            ]);
        }
    }

}
