<?php


namespace App\Controller\Admin;


use App\Entity\Participant;
use App\Form\FileUploadType;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Services\FileUploader;
use App\Services\Reader;
use App\Services\ValidateUserCSV;
use Doctrine\ORM\EntityManagerInterface;
use MobileDetectBundle\DeviceDetector\MobileDetectorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin/dashboard", name="admin_")
 */
class AdminUploadController extends AbstractController
{
    /**
     * @Route("/upload", name="upload_users")
     * @param ParticipantRepository $participantRepository
     * @param CampusRepository $campusRepository
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param FileUploader $file_uploader
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     * @throws \Doctrine\DBAL\Exception
     */

    public function upload(ParticipantRepository $participantRepository,    CampusRepository $campusRepository,
                           EntityManagerInterface $entityManager,
                           Request $request,
                           FileUploader $file_uploader,
                           MobileDetectorInterface $mobileDetector,
                           UserPasswordHasherInterface $hasher): Response
    {
        if ($mobileDetector->isMobile()) {
            return $this->redirectToRoute('main_welcome');
        } else {
            $form = $this->createForm(FileUploadType::class);
            $form->handleRequest($request);
            $readerMessage = '';
            $readerStatus = 'success';
            $validationMessage = '';
            $dbSuccessMsg = '';
            $databaseValidationArray = [];
            $lineNb = 0;
            $campusArray = [];
            $allCampus = $campusRepository->findAll();
            $campusString = '';
            $count = 1;
            $uploadStatus = ['upload' => false, 'validate' => false, 'save' => false];

            foreach ($allCampus as $key => $c) {
                $campusString .= (count($allCampus) === $count) ? $c->getNom() . ' !' : $c->getNom() . ' / ';
                $count++;
                $campusArray[] = $c->getNom();
            }
            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form['upload_file']->getData();
                if ($file) {
                    $file_name = $file_uploader->upload($file);
                    if ($file_name !== null) {
                        $directory = $file_uploader->getTargetDirectory();
                        $full_path = $directory . '/' . $file_name;
                        $incomingDataArray = Reader::CSVtoArray($full_path);
                        $readerData = $incomingDataArray[0];
                        $readerStatus = $incomingDataArray[1];
                        $readerMessage = $incomingDataArray[2];
                        if ($readerStatus === 'error') {
                            $this->fileDelete($file_name, "csvUpload_directory");
                        } else {
                            $uploadStatus['upload'] = true;
                            $validationMessage = ValidateUserCSV::validate($readerData, $campusArray);
                            if ($validationMessage === '') {
                                $uploadStatus['validate'] = true;
                                $entityManager->getConnection()->beginTransaction();
                                if (is_array($readerData) && count($readerData) > 0) {
                                    $emails = array_column($readerData, 'mail');
                                    $countedEmails = array_count_values($emails);

                                    foreach ($readerData as $userCsv) {
                                        $errorInLoop = false;
                                        $lineNb++;
                                        $newUser = new Participant();
                                        $newUser->setNom(trim(ucwords(strtolower($userCsv['nom']))));
                                        $newUser->setPrenom(trim(ucwords(strtolower($userCsv['prenom']))));
                                        $tel = str_replace(' ', '', $userCsv['telephone']);
                                        if ($tel != '') {
                                            $newUser->setTelephone($tel);
                                        }
                                        //TODO verifier si email est au format email? (encore une fois si c'est déjà fait avec validateur?)
                                        $email = trim($userCsv['mail']);
                                        $findEmail = $participantRepository->findOneBy(['email' => $email]);
                                        $findEmailCSV = $countedEmails[$email];
                                        if (($findEmail && $findEmail->getEmail() === $email) || ($findEmailCSV && $findEmailCSV > 1)) {
                                            $errorInLoop = true;
                                            array_push($databaseValidationArray, 'Ligne ' . $lineNb . ': email <strong>' . $email . '</strong> est déjà enregistré dans la base de données ou il est indiqué plusieurs fois dans le fichier téléchargé - vérifiez les doublons!');
                                        } else {
                                            $newUser->setEmail($email);

                                        }
                                        $campus = trim(ucwords(strtolower($userCsv['campus'])));
                                        $findCampus = $campusRepository->findOneBy(['nom' => $campus]);
                                        if ($findCampus && $findCampus->getNom() === $campus) {
                                            $newUser->setCampus($findCampus);
                                        } else {
                                            $errorInLoop = true;
                                            array_push($databaseValidationArray, 'Ligne ' . $lineNb . ': Le campus n\'existe pas! Choisissez parmi ' . $campusString);
                                        }
                                        $newUser->setPseudo($email);
                                        $newUser->setMotPasse($hasher->hashPassword($newUser, $email));
                                        $newUser->setActif(false);
                                        $newUser->setAdministrateur(false);

                                        if (!$errorInLoop) {
                                            $entityManager->persist($newUser);
                                        }
                                    }
                                }
                                if ($lineNb === count($readerData) && count($databaseValidationArray) > 0) {
                                    $entityManager->getConnection()->rollback();
                                    dump('Rollback flush!');
                                } else {
                                    $entityManager->flush();
                                    $entityManager->getConnection()->commit();
                                    $dbSuccessMsg = $lineNb > 1 ? $lineNb . ' utilisateurs ont été enrégistrés' : $lineNb . ' utilisateur a été enrégistré' . ' dans la base de données!';
                                    $uploadStatus['save'] = true;
                                }
                            }
                        }
                        $this->fileDelete($file_name, "csvUpload_directory");
                    } else {
                        // Oups, an error occured !!!
                    }

                }

            }

            return $this->render('admin/user/upload.html.twig', [
                'usersUploadForm' => $form->createView(),
                'readerMessage' => $readerMessage,
                'readerStatus' => $readerStatus,
                'validationErrorMessage' => $validationMessage,
                'dbValidation' => $databaseValidationArray,
                'dbSuccess' => $dbSuccessMsg,
                'uploadStatus' => $uploadStatus
            ]);
        }
    }

    private function fileDelete(string $file_name, string $directory)
    {
        try {
            $filesystem = new Filesystem();
            $path = $this->getParameter($directory) . '/' . $file_name;
            $filesystem->remove($path);
        } catch (\Exception $e) {
            dump($e);
        }
    }
}