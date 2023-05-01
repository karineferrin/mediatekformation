<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Tests d'intégration sur le FormationRepository
 *
 * @author karinefer
 */
class FormationRepositoryTest extends KernelTestCase{
    
    /**
     * Récupérer le repository de Formation
     */
    public function recupRepository(): FormationRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(FormationRepository::class);
        return $repository;
    }
    
    /**
     * Récupérer le nombre d'enregistrements contenus dans la table Formation
     */
    public function testNbFormations(){
        $repository = $this->recupRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals(238, $nbFormations);
    }
    
    /**
     * Création d'une instance de Formation avec les champs
     * @return Formation
     */
    public function newFormation(): Formation{
        $formation = (new Formation())
                ->setTitle("FormationDeTest")
                ->setDescription("DESCRIPTION DE FORMATIONDETEST")
                ->setPublishedAt(new DateTime("2023/01/14"));
        return $formation;
    }
    
    /**
     * Tester l'ajout d'une formation
     */
    public function testAddFormation(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    /**
     * Tester la suppression d'une formation
     */
    public function testRemoveFormation(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormations - 1, $repository->count([]), "erreur lors de la suppression");
    }
    
    /**
     * Tester la fonction de tri d'un champ dans l'ordre défini
     */
    public function testFindAllOrderBy(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllOrderBy("title", "ASC");
        $nbFormations = count($formations);
        $this->assertEquals(239, $nbFormations);
        $this->assertEquals("Android Studio (complément n°1) : Navigation Drawer et Fragment", $formations[0]->getTitle());
    }
    
    /**
     * Tester la fonction de tri d'un champ dans l'ordre défini
     * Et d'un champ dans l'ordre défini si autre table
     */
    public function testFindAllOrderByTable(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllByTable("name", "ASC", "playlist");
        $nbFormations = count($formations);
        $this->assertEquals(238, $nbFormations);
        $this->assertEquals("Android win cours", $formations[0]->getTitle());
    }
    
    /**
     * Tester le filtrage des formations dont un champ contient une valeur spécifiée
     */
    public function testFindByContainValue(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findByContainValue("title", "C#");
        $nbFormations = count($formations);
        $this->assertEquals(11, $nbFormations);
        $this->assertEquals("C# : ListBox en couleur", $formations[0]->getTitle());
    }
    
    /**
     * Tester le filtrage des formations dont un champ contient une valeur spécifiée
     * Et si un champ contient une valeur spécifiée dans une autre table
     */
    public function testFindByContainValueTable(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findByContainValueTable("name", "Compléments Android (programmation mobile)", "playlist");
        $nbFormations = count($formations);
        $this->assertEquals(13, $nbFormations);
        $this->assertEquals("Android Studio (complément n°13) : Permissions", $formations[0]->getTitle());
    }
    
    /**
     * Teste le tri des formations selon la date la plus récente de publication
     */
    public function testFindAllLasted(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllLasted(1);
        $nbFormations = count($formations);
        $this->assertEquals(1, $nbFormations);
        $this->assertEquals(new DateTime("2023-04-28 14:25:19"), $formations[0]->getPublishedAt());
    }
    
    /**
     * Tester si la fonction récupère les formations d'une playlist selon son id
     * Et réalise le tri ascendant
     */
    public function testFindAllForOnePlaylist(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $formations = $repository->findAllForOnePlaylist(3);
        $nbFormations = count($formations);
        $this->assertEquals(19, $nbFormations);
        $this->assertEquals("Python n°0 : installation de Python",$formations[0]->getTitle());
    }
    
}
