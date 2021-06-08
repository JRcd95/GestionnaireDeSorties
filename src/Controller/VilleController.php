<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\SearchVilleType;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    /**
     * @Route("/ville", name="ville")
     */
    public function gestionVille(VilleRepository $villeRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $searchVille = new Ville();
        $searchVilleForm = $this->createForm(SearchVilleType::class, $searchVille);
        $searchVilleForm->handleRequest($request);
        $listVille = $villeRepository->searchVille($searchVille);

        $ville = new Ville();
        $addVilleForm = $this->createForm(VilleType::class, $ville);
        $addVilleForm->handleRequest($request);

        if ($addVilleForm->isSubmitted() && $addVilleForm->isValid()){
            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash('success', 'Félicitation, la ville est créée !');
            return $this->redirectToRoute('ville');
        }
        return $this->render('ville/ville.html.twig', [
            'villes' => $listVille,
            'searchVilleForm' => $searchVilleForm->createView(),
            'addVilleForm' => $addVilleForm->createView()
        ]);
    }

    /**
     * @Route ("/ville/delete/{id}", name="ville_delete")
     */
    public function deleteVille(Ville $ville, EntityManagerInterface $entityManager, Request $request): RedirectResponse {

        if($this->isCsrfTokenValid('token_delete', $request->get('token'))) {
            $entityManager->remove($ville);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ville');
    }
}
