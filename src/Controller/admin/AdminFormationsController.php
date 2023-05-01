<?php

namespace App\Controller\admin;

use App\Controller\Form\FormationType;
use App\Entity\Formation;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Gère les routes de la page d'administration des formations
 *
 * @author karinfer
 * 
 */
class AdminFormationsController extends AbstractController {
    const PAGES_FORMATIONS = "admin/admin.formations.html.twig";
    /**
     * $var FormationRepository
     */
    private $formationRepository;
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;
    /**
     * Création du constructeur 
     * @param CategorieRepository $categorieRepository
     * @param PlaylistRepository $playlistRepository
     * @param FormationRepository $formationRepository
     */
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }
    
    /**
     * Création de la route vers la page d'administration des formations
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $playlists = $this->playlistRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGES_FORMATIONS, [
            'formations' => $formations,
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }
    
    /**
     * Suppression d'une formation
     * @Route("/admin/suppr/{id}", name="admin.formation.suppr")
     * @param Formation $formation
     * @return Response
     */
    public function  suppr(Formation $formation): Response{
        $this -> formationRepository -> remove($formation , true);
        return  $this -> redirectToRoute('admin.formations');
    }
    /**
     * Edition d'une formation
     * @Route("/admin/edit/{id}", name="admin.formation.edit")
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */
    public function edit(Formation  $formation, Request $request): Response{
        $formFormation = $this->createForm(FormationType::class, $formation);
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->formationRepository->add($formation, true);
            return $this->redirectToRoute('admin.formations');
        }
        return $this -> render("admin/admin.formation.edit.html.twig", [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }
    /**
     * Ajout d'une formation
     * @Route("/admin/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted() && $formFormation->isValid()){
            $this->formationRepository->add($formation, true);
            return $this->redirectToRoute('admin.formations');
        }
        return $this -> render("admin/admin.formation.ajout.html.twig", [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }
    /**
     * Retourne toutes les formations triées sur un champ
     * Et sur un champ si autre table
     * @Route("/admin/tri/{champ}/{ordre}/{table}", name="admin.formations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    public function sort($champ, $ordre, $table=""): Response{
        if ($table != ""){
            $formations = $this->formationRepository->findAllByTable($champ, $ordre, $table);
        }else{
            $formations = $this->formationRepository->findAllOrderBy($champ, $ordre);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGES_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
    /**
     * Récupère les enregistrements selon le champ et la valeur,
     * Et si le champ est dans une autre table
     * @Route("/admin/recherche/{champ}/{table}", name="admin.formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        if ($table != ""){
            $formations = $this->formationRepository->findByContainValueTable($champ, $valeur, $table);
        }else{
            $formations = $this->formationRepository->findByContainValue($champ, $valeur);
        }
        $categories = $this->categorieRepository->findAll();
        
        return $this->render(self::PAGES_FORMATIONS, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }
}
