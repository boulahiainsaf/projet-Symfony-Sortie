<?php


namespace App\Controller\Api;



use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route ("/api")
 */

class ApiLieuController extends  AbstractController
{
    /**
     * @Route ("/lieux/{id}",name="api_lieu_liste",methods={"GET"})
     */
    public function liste(LieuRepository $lieuRepository,SerializerInterface $serializer,int $id)
    {
        $lieux=$lieuRepository->findByVille($id);

   //     $lieuNormaliser = $normalizer->normalize($lieux,null,['groups'=>"liste_lieux"]);
     //   $json=json_encode($lieuNormaliser);
        //$json = $serializer->serialize($lieux,'json',['groups'=>"liste_lieux"]);
       // return new JsonResponse($json,Response::HTTP_OK,[],true);
        return $this->json($lieux,Response::HTTP_OK,[],['groups'=>"liste_lieux"]);
    }
    /**
     * @Route ("/lieuseul/{id}",name="api_lieu",methods={"GET"})
     */
    public function lieu(int $id,LieuRepository $lieuRepository,SerializerInterface $serializer)
    {
        $lieudet=$lieuRepository->find($id);
        return $this->json($lieudet,Response::HTTP_OK,[],['groups'=>"liste_lieux"]);
    }



}