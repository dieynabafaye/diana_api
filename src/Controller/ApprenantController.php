<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApprenantController extends AbstractController
{
    /**
     * @Route(
     *     name="apprenant_liste",
     *     path="api/apprenants",
     *     methods={"GET"},
     *     defaults={
     *          "_controller"="\app\ControllerApprenantController::showApprenant",
     *          "_api_resource_class"=User::class,
     *          "_api_collection_operation_name"="get_apprenants"
     *     }
     * )
    */
    public function showApprenant(UserRepository $repo)
    {
        $apprenants= $repo->findByProfil("APPRENANT");
        return $this->json($apprenants,Response::HTTP_OK,);

    }
}
