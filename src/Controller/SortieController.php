<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\AddSortieType;
use App\Form\SearchType;
use App\Repository\SortieRepository;
use App\Search\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function addSortie(Request $request): Response {
        $sortie = new Sortie();
        $addSortieForm = $this->createForm(AddSortieType::class, $sortie);

        return $this->render('sortie/addSortie.html.twig', [
            'addSortieForm' => $addSortieForm->createView()
        ]);
    }
}
