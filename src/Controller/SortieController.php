<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\SortieRepository;
use App\Search\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sorties", name="sortie")
     */
    public function list(SortieRepository $sortieRepository, Search $search): Response
    {
        $searchForm = $this->createForm(SearchType::class, $search);

        $sorties = $sortieRepository->findAll();
        return $this->render('sortie/sortie.html.twig', [
            'searchForm' => $searchForm->createView(),
            "sorties" => $sorties

        ]);
    }
}
