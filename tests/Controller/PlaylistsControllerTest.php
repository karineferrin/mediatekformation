<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tests fonctionnels sur le PlaylistsController
 *
 * @author karinefer
 */
class PlaylistsControllerTest extends WebTestCase {
    
    /**
     * Tester d'accès à la page des playlists
     */
    public function testAccesPage(){
       $client = static::createClient();
       $client->request('GET', '/playlists');
       $this->assertResponseStatusCodeSame(Response::HTTP_OK);
   }
    
   /**
    * Tester le tri des playlists selon leur nom, dans un ordre ascendant
    */
    public function testTriPlaylists()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'playlists/tri/name/ASC');
        $this->assertSelectorTextContains('th', 'playlist');
        $this->assertCount(3, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Android - Test playlist');
    }
    
    /**
     * Tester le tri des playlists selon le nombre de formations 
     * dans l'ordre ascendant
     */
    public function testTriNbFormations()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'playlists/tri/nbformations/ASC');
        $this->assertSelectorTextContains('th', 'playlist');
        $this->assertCount(3, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Cours Informatique embarquée');
    }
    
    /**
     * Tester le filtrage des catégories selon la valeur recherchée
     */
    public function testFiltreCategories()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists/recherche/id/categories'); 
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Android'
        ]);
        //vérifie le nombre de lignes obtenues
        $this->assertCount(3, $crawler->filter('h5'));
        // vérifie si la formation correspond à la recherche
         $this->assertSelectorTextContains('h5', 'Android - Test playlist');
    }
    
    /**
     * Tester du lien qui redirige l'utilisateur vers la page de détail de la playlist
     */
    public function testLinkPlaylists() {
        $client = static::createClient();
        $client->request('GET','/playlists');
        $client->clickLink("Voir détail");
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/playlists/playlist/28', $uri);
    }
}
