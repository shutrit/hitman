<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Word;
use AppBundle\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Word controller.
 *
 * @Route("word")
 */
class WordController extends Controller
{
    /**
     * Lists all word entities.
     *
     * @Route("/", name="word_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery("SELECT p FROM 'AppBundle:Word' p WHERE p.id > 0 ORDER BY p.id DESC  ");

        $word = $query->getResult();

       
        //  original by controller $words = $em->getRepository('AppBundle:Word')->getSingleResult();
   

        $jack =   implode(" ",$word);
           
        $jo =  $word[array_rand($word)];


        //insert to the game table just set game id , later we will add a column word 

        $game = new Game();
        $game->setGame($jo);

        $em->persist($game);

        $em->flush();
   
        
        return $this->render('word/index.html.twig', array(
            'words' => $jo,
        ));
       
       
    }

    /**
     * Creates a new word entity.
     *
     * @Route("/new", name="word_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $word = new Word();
        $form = $this->createForm('AppBundle\Form\WordType', $word);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($word);
            $em->flush($word);

            return $this->redirectToRoute('word_show', array('id' => $word->getId()));
        }

        return $this->render('word/new.html.twig', array(
            'word' => $word,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a word entity.
     *
     * @Route("/{id}", name="word_show")
     * @Method("GET")
     */
    public function showAction(Word $word)
    {
        $deleteForm = $this->createDeleteForm($word);

        return $this->render('word/show.html.twig', array(
            'word' => $word,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing word entity.
     *
     * @Route("/{id}/edit", name="word_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Word $word)
    {
        $deleteForm = $this->createDeleteForm($word);
        $editForm = $this->createForm('AppBundle\Form\WordType', $word);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('word_edit', array('id' => $word->getId()));
        }

        return $this->render('word/edit.html.twig', array(
            'word' => $word,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a word entity.
     *
     * @Route("/{id}", name="word_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Word $word)
    {
        $form = $this->createDeleteForm($word);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($word);
            $em->flush($word);
        }

        return $this->redirectToRoute('word_index');
    }

    /**
     * Creates a form to delete a word entity.
     *
     * @param Word $word The word entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Word $word)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('word_delete', array('id' => $word->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
