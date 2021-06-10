<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantEditType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function editProfil(Participant $user, Request $request, EntityManagerInterface $entityManager,UserPasswordEncoderInterface $passwordEncoder): Response {

        $form = $this->createForm(ParticipantEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $hashed = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hashed);

            $entityManager->flush();
            return $this->redirectToRoute('sortie');
        }

        return $this->render('participant/edit.html.twig', [
            "user" => $user,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route ("/participant/details/{id}", name="participant_details")
     */
    public function detailSortie(ParticipantRepository $participantRepository, int $id): Response{
        $participant = $participantRepository->find($id);
        if($participant == null){
            $this->addFlash('echec', 'Le participant n\'existe pas');
            return $this->redirectToRoute('sortie');
        }
        return $this->render('participant/detailparticipant.html.twig', [
            "participant" => $participant
        ]);
    }
}
