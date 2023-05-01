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
 * Gère les routes de la page d'administration des catégories
 *
 * @author karinfer
 */
class AdminCategoriesController extends AbstractController {
    /**
     * $var CategorieRepository
     */
    private $categorieRepository;
    /**
     * $var FormationRepository
     */
    private $formationRepository;
    
    /**
     *  Création du constructeur
     * @param FormationRepository $formationRepository 
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(CategorieRepository $categorieRepository, FormationRepository $formationRepository){
        $this -> categorieRepository = $categorieRepository;
        $this -> formationRepository = $formationRepository;
    }
    /**
     * Création de la route vers la page d'administration des catégories
     * @Route("/admin/categories", name="admin.categories")
     * @return Response
     */
    public function index(): Response{
             $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render("/admin/admin.categories.html.twig", [
            'formations' => $formations,
            'categories' => $categories,
        ]);             
           
    }
    /**
     * Suppression d'une catégorie et redirection vers la page d'administration
     * @Route("/admin/categorie/suppr/{id}", name="admin.categorie.suppr")
     * @param Categorie $categorie
     * @return Response
     */
    public function suppr(Categorie $categorie): Response{
        $this->categorieRepository->remove($categorie, true);
        return $this->redirectToRoute('admin.categories');
        
    }    
        
    
    /**
     * Ajout d'une catégorie et redirection vers la page d'administration
     * @Route("/admin/categorie/ajout", name="admin.categorie.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $name = $request->get("name");
        $nomcategorie = $this->categorieRepository->findAllEqual($name);
        
        if ($nomcategorie == false) {
            $categories = new Categorie();
            $categories->setName($name);
            $this->categorieRepository->add($categories, true);
            return $this->redirectToRoute('admin.categories');
        }
        return $this->redirectToRoute('admin.categories');
    }
    /**
     * Tri les enregistrements selon le champ et l'ordre
     * @Route("/admin/categories/tri/{champ}/{ordre}", name="admin.categories.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        $categories = $this->categorieRepository->findAllOrderBy($champ, $ordre);
        $formations = $this->formationRepository->findAll();
        return $this->render('/admin/admin.categories.html.twig', [
            'formations' => $formations,
            'categories' => $categories,
        ]);
    }
}
