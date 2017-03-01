<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


/**
* Start a new game with a new word randomly chosen from word table  
*
* @Route("/games", name="start_game")
* 
*/

class GameController extends Controller
{
    /**
     * Start a new game with a new word randomly chosen from word table  
     *
     * @Route("/", name="start_game")
     * @Method("GET")
     */
    public function indexAction() {

  
        $em = $this->getDoctrine()->getManager();
        // 
        $query = $em->createQuery("SELECT p FROM 'AppBundle:Word' p WHERE p.id > 0 ORDER BY p.id DESC");

        $word = $query->getResult();

        $lo =  $word[array_rand($word)];

         $game = new Game();
         // insert the word to the game table  method from entitty Game
         $game->setGame($lo);
       
         $em->persist($game);
      
         $em->flush();

         $game_id = $game->getId();
   
        return new Response('A new game started  at games/' . $game_id);
     
       
    }

    /**
     * @var string 
     * @var word 
     * @var letter
     */
    public function checkLetterAction ($letter, $string) {
    
    $g_word = [];
    $found = 0;
    $session = $this->get('session');
        // if no entry in the table under attempt 
        for($i=0; $i< strlen($string); $i++) {
            
             $g_word[$i] = '*';

            if($string[$i] === $letter) {
                    $g_word[$i] = $letter; 

                    $found ++ ;
                     } 
            }  

              $jerry = implode("", $g_word);
              
            return $jerry;


}
  
    /**
     * @var string 
     * @var request $l (letter)
     * @var character in selected random word
     */
     public function replace_char($string, $position, $newchar) {
       
        $string[$position] = $newchar;
        return $string;
    }

    /**
     * 
     *
     * @Route("/{id}")
     * @Method("PUT")
     */
    public function guessAction(Game $game ) {   
       
   

      
    $request = $this->getRequest();
    $l = $request->get('ot');  
     
    //  should be in the entity as constraints  
    if(!preg_match("/^[a-z]+$/", $l) == 1) {

        die('please use only a-z characters');
} 
 

     
     $session =  $this->get('session');
     $original_word = $game->getGame();



    if ($session->has('tries_left')) {
        $session->set('tries_left', ($session->get('tries_left')-1));
        
    } else {  $session->set('tries_left', 10); }



   
        $guess= $this->checkLetterAction($l, $original_word);
        

             if (strrpos($original_word,$l))  {

                if($session->has('guess')) {
        
                    $pos = strrpos($original_word, $l) ;

                     $session->set('guess', $this->replace_char($session->get('guess'),$pos, $l));
                 }
                     else {$session->set('guess', $guess); }
            }
             

             if (strpos($original_word, $l) !== FALSE ) {
        

                 if($session->has('guess')) {
        
                    $pos = strpos($original_word, $l) ;

                     $session->set('guess', $this->replace_char($session->get('guess'),$pos, $l));
                 }
                     else {$session->set('guess', $guess); }
            }
    
                    
        $status = 'busy';
         
            if($session->get('tries_left') <= 0) {

            $status = 'Failed'; 
            $session->invalidate();
            }  
            if ($session->get('guess') === $original_word) {
            $status ='succuess';  
            $session->invalidate();   
            }

        $response = new Response();
        $response->setContent(json_encode(array(
           
            'word' => $session->get('guess')? : $guess,
            'tries_left'=> $session->get('tries_left') ? :'zero tries left' ,
            'status'=>$status,
            )));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;

    }

 
}
