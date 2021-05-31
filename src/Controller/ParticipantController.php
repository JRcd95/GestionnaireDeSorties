<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participants", name="participant_list")
     */
    public function list(): Response
    {
        //todo: afficher la liste des participants
        return $this->render('participant/list.html.twig');
    }

    /**
     * @Route("/participant/edit/{id}", name="participant_edit")
     */
    public function editProfil(Participant $user, Request $request, EntityManagerInterface $entityManager): Response {

        $form = $this->createForm(ParticipantEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('main_home');
        }

        return $this->render('participant/edit.html.twig', [
            "user" => $user,
            "form" => $form->createView()
        ]);
    }
}
