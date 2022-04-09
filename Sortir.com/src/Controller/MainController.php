<?php


namespace App\Controller;


use MobileDetectBundle\DeviceDetector\MobileDetectorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/", name="main_")
 */
class MainController extends AbstractController
{
    /**
     * @Route("", name="welcome")
     */
    public function home(){
       dump($this->getParameter('kernel.project_dir'));



       // $mobileDetector = new Detector();
       // dump($mobileDetector->isMobile());

        if($this->getUser()){
            return $this->redirectToRoute('app_sortie');
        }
        else{
            return $this->render('main/welcome.html.twig');
        }
    }
}