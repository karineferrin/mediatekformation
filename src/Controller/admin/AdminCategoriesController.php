<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminCategorieController
 *
 * @author karin
 */
class AdminCategoriesController extends AbstractController {
    /**
     * $var CategorieRepository
     */
    private $repository;
    /**
     * $var FormationRepository
     */
    private $formationRepository;
    
    /**
     * 
     * @param CategorieRepository $repository
     */
    public function __construct(CategorieRepository $repository, FormationRepository $formationRepository){
        $this -> repository = $repository;
        $this -> formationRepository = $formationRepository;
    }
    /**
     * @Route("/admin/categories", name="admin.categories")
     * @return Response
     */
    public function index(): Response{
             $categories = $this -> repository -> findAll();
             return $this->render("admin/admin.categories.html.twig", [
            'categories' => $categories
        ]);             
           
    }
    /**
     * @Route("/admin/categorie/suppr/{id}", name="admin.categorie.suppr")
     * @param Categorie $categorie
     * @return Response
     */
    public function suppr(Categorie  $categorie, int $id, ): Response{
        $formations = $this->getFormation();
        dd ($formations);
        
        
        if ($formations != null) {
            return $this ->render('admin/admin.categories.html.twig', []);
            
        }else{
            $this -> repository -> remove($categorie , true);
            return  $this -> redirectToRoute('admin.formations');
        }
    }
    
    /**
     * @Route("/admin/categorie/ajout", name="admin.categorie.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $nomCategorie = $request->get("nom");
        $categorie = new Categorie();
        $categorie ->setName($nomCategorie);
        $this->repository->add($categorie, true);
        return $this->redirectToRoute('admin.categories');
        }
}
