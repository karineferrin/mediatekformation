<?php

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Description of AdminFormationsController
 *
 * @author karin
 * 
 */
class AdminFormationsController extends AbstractController {
    /**
     * $var FormationRepository
     */
    private $repository;
    
    /**
     * 
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository){
        $this -> repository = $repository;
    }
    
    /**
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    public function index(): Response{
        $formations = $this -> repository -> findAllOrderBy('publishedAt','DESC');
        return $this->render("admin/admin.formations.html.twig", [
            'formations' => $formations
        ]);
    }
    
    /**
     * @Route("/admin/suppr/{id}", name="admin.formation.suppr")
     * @param Formation $formation
     * @return Response
     */
    public function  suppr(Formation  $formation): Response{
        $this -> repository -> remove($formation , true);
        return  $this -> redirectToRoute('admin.formations');
    }
    /**
     * @Route("/admin/edit/{id}", name="admin.formation.edit")
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */
    public function edit(Formation  $formation, Request $request): Response{
        $formFormation = $this->createForm(FormationType::class, $formation);
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->repository->add($formation, true);
            return $this->redirectToRoute('admin.formations');
        }
        return $this -> render("admin/admin.formation.edit.html.twig", [
            'formation' => $formation,
            'formFormation' => $formFormation->createView()
        ]);
    }
    /**
     * @Route("/admin/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->repository->add($formation, true);
            return $this->redirectToRoute('admin.formations');
        }
        return $this -> render("admin/admin.formation.ajout.html.twig", [
            'visite' => $formation,
            'formFormation' => $formFormation->createView()
        ]);
    }
    
}
