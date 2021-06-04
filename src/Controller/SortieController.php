<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\AddSortieType;
use App\Form\SearchType;
use App\Form\SinscrireType;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Search\Search;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sorties", name="sortie")
     */
    public function list(SortieRepository $sortieRepository, Search $search, Request $request): Response
    {

        $searchForm = $this->createForm(SearchType::class, $search);
        $searchForm->handleRequest($request);

        $sorties = $sortieRepository->searchFilter($search);
        return $this->render('sortie/sortie.html.twig', [
            'searchForm' => $searchForm->createView(),
            'sorties' => $sorties

        ]);
    }

    /**
     * @Route ("/sortie/add", name="sortie_add")
     */
    public function addSortie(Request $request, EntityManagerInterface $entityManager, LieuRepository $lieuRepository): Response {
        $lieu = $lieuRepository->findAll();

        $sortie = new Sortie();

        $sortie->setOrganisateur($this->getUser());
        $sortie->setCampusOrganisateur($this->getUser()->getCampus());
        $sortie->setEtat($entityManager->getRepository('App:Etat')->find(1));

        $addSortieForm = $this->createForm(AddSortieType::class, $sortie);

        $addSortieForm->handleRequest($request);
        if ($addSortieForm->isSubmitted() && $addSortieForm->isValid()){
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('sortie');
        }
        return $this->render('sortie/addSortie.html.twig', [
            'addSortieForm' => $addSortieForm->createView(),
            'lieu' => $lieu
        ]);
    }
    /**
     * @Route ("/sortie/details/{id}", name="sortie_details")
     */
    public function detailSortie(SortieRepository $sortieRepository, int $id): Response{
        $sortie = $sortieRepository->find($id);

        return $this->render('sortie/detailSortie.html.twig', [
            "sortie" => $sortie
        ]);
    }

    /**
     * @Route ("/sortie/sinscrire/{id}", name="sortie_sinscrire")
     */
    public function sincrireSortie(SortieRepository $sortieRepository,int $id, EntityManagerInterface $entityManager): RedirectResponse {
        $sortie = $sortieRepository->find($id);
        $sortie->addParticipant($this->getUser());
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('sortie');
    }

    /**
     * @Route ("/sortie/desister/{id}", name="sortie_desister")
     */
    public function desisterSortie(SortieRepository $sortieRepository,int $id, EntityManagerInterface $entityManager): RedirectResponse {
        $sortie = $sortieRepository->find($id);
        $sortie->removeParticipant($this->getUser());
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('sortie');
    }
}
