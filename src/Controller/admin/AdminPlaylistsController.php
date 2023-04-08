<?php

namespace App\Controller\admin;

use App\Controller\Form\PlaylistType;
use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminPlaylistsController
 *
 * @author karin
 * 
 */
class AdminPlaylistsController extends AbstractController {
    const PAGES_PLAYLISTS = "admin/admin.playlists.html.twig";
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
     * 
     * @param FormationRepository $formationRepository
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     */
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }
    
    /**
     * @Route("/admin/playlists", name="admin.playlists")
     * @return Response
     */
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGES_PLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }
    
    /**
     * @Route("/admin/playlist/suppr/{id}", name="admin.playlist.suppr")
     * @param Playlist $playlist
     * @return Response
     */
    public function suppr(Playlist $playlist): Response{
        $this -> playlistRepository -> remove($playlist , true);
        return  $this -> redirectToRoute('admin.playlists');
    }
    /**
     * @Route("/admin/playlist/edit/{id}", name="admin.playlist.edit")
     * @param Request $request
     * @return Response
     */
    public function edit(Playlist $id, Request $request): Response{
        $formPlaylist = $this->createForm(PlaylistType::class, $id);
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($id, true);
            return $this->redirectToRoute('admin.playlists');
        }
        return $this -> render("admin/admin.playlist.edit.html.twig", [
            'playlist' => $id,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
    /**
     * @Route("/admin/playlist/ajout", name="admin.playlist.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response{
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()){
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute('admin.playlists');
        }
        return $this -> render("admin/admin.playlist.ajout.html.twig", [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
    /**
     * @Route("/admin/playlists/tri/{champ}/{ordre}", name="admin.playlists.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        switch($champ){
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nbformations":
                $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render("admin/admin.playlists.html.twig", [

            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }
    
    /**
     * @Route("/admin/playlists/recherche/{champ}/{table}", name="admin.playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table): Response{
        $valeur = $request->get("recherche");
        if ($table != ""){
            $playlists = $this->playlistRepository->findByContainValueTable($champ,$valeur,$table);
        }else{
            $playlists = $this->playlistRepository->findByContainValue($champ,$valeur);
        }
            $categories = $this->categorieRepository->findAll();
            return $this->render(self::PAGES_PLAYLISTS, [
               'playlists' => $playlists,
               'categories' => $categories,            
               'valeur' => $valeur,
               'table' => $table
            ]);  
    }
    /**
     * @Route("/playlist/edit/{id}", name="admin.playlists.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render("admin.playlist.edit.html.twig", [
            'playlist' => $playlist,
            'playlistformations' => $playlistFormations
        ]);        
    }
}
