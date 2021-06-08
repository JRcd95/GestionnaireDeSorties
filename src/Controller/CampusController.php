<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Form\SearchCampusType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampusController extends AbstractController
{
    /**
     * @Route("/campus", name="campus")
     */
    public function gestionCampus(CampusRepository $campusRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $searchCampus = new Campus();
        $searchCampusForm = $this->createForm(SearchCampusType::class, $searchCampus);
        $searchCampusForm->handleRequest($request);
        $listcampus= $campusRepository->searchCampus($searchCampus);

        $campus = new Campus();
        $addCampusForm = $this->createForm(CampusType::class, $campus);
        $addCampusForm->handleRequest($request);

        if ($addCampusForm->isSubmitted() && $addCampusForm->isValid()){
            $entityManager->persist($campus);
            $entityManager->flush();

            $this->addFlash('sucess', 'Félicitation, le campus est créé !');
            return $this->redirectToRoute('campus');
        }

        return $this->render('campus/campus.html.twig', [
            'campus' => $listcampus,
            'searchCampusForm' => $searchCampusForm->createView(),
            'addCampusForm' => $addCampusForm->createView()
        ]);
    }

    /**
     * @Route ("/campus/delete/{id}", name="campus_delete")
     */
    public function deleteSortie(Campus $campus, EntityManagerInterface $entityManager, Request $request): RedirectResponse {

        if($this->isCsrfTokenValid('token_delete', $request->get('token'))) {
            $entityManager->remove($campus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('campus');
    }

}
