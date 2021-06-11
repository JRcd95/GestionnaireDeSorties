<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\AddSortieType;
use App\Form\CancelSortieType;
use App\Form\EditSortieType;
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
        $sortie = new Sortie();

        $sortie->setOrganisateur($this->getUser());
        $sortie->setCampusOrganisateur($this->getUser()->getCampus());
        $sortie->setEtat($entityManager->getRepository('App:Etat')->find(1));

        $addSortieForm = $this->createForm(AddSortieType::class, $sortie);

        $addSortieForm->handleRequest($request);
        if ($addSortieForm->isSubmitted() && $addSortieForm->isValid()){

            if($addSortieForm->get('enregistrer')->isClicked()){
                $sortie->setEtat($etat= $entityManager->getRepository('App:Etat')->find(1));
            }
            if($addSortieForm->get('publier')->isClicked()){
                $sortie->setEtat($etat= $entityManager->getRepository('App:Etat')->find(2));
            }

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Félicitation, la sortie est créée !');

            return $this->redirectToRoute('sortie');
        }
        return $this->render('sortie/addSortie.html.twig', [
            'addSortieForm' => $addSortieForm->createView()
        ]);
    }
    /**
     * @Route ("/sortie/details/{id}", name="sortie_details")
     */
    public function detailSortie(SortieRepository $sortieRepository, int $id): Response{
        $sortie = $sortieRepository->find($id);
        if($sortie == null){
            $this->addFlash('echec', 'La sortie n\'existe pas');
            return $this->redirectToRoute('sortie');
        }
        return $this->render('sortie/detailSortie.html.twig', [
            "sortie" => $sortie
        ]);
    }

    /**
     * @Route ("/sortie/sinscrire/{id}", name="sortie_sinscrire")
     */
    public function sincrireSortie(SortieRepository $sortieRepository,int $id, EntityManagerInterface $entityManager, Request $request, Sortie $sortie): RedirectResponse {

        if($this->isCsrfTokenValid('token_inscription', $request->get('token'))){
            $sortie = $sortieRepository->find($id);
            if($sortie == null){
                $this->addFlash('echec', 'La sortie n\'existe pas');
            }
            if ($sortie->getParticipants()->count() < $sortie->getNbInscriptionMax()) {
                $sortie->addParticipant($this->getUser());
                $entityManager->persist($sortie);
                $entityManager->flush();

                $this->addFlash('success', 'Félicitation, vous vous êtes inscrit à la sortie !');
            } else {
                $this->addFlash('echec', 'Le nombre maximum de participant est atteint');
            }
        }
        return $this->redirectToRoute('sortie');
    }

    /**
     * @Route ("/sortie/desister/{id}", name="sortie_desister")
     */
    public function desisterSortie(SortieRepository $sortieRepository,int $id, EntityManagerInterface $entityManager, Request $request): RedirectResponse {

       if($this->isCsrfTokenValid('token_desistement', $request->get('token'))) {
           $sortie = $sortieRepository->find($id);
           if($sortie == null){
               $this->addFlash('echec', 'La sortie n\'existe pas');
           }
           $today = new \DateTime();
           if ($today < $sortie->getDateLimiteInscription()){
               $sortie->removeParticipant($this->getUser());
               $entityManager->persist($sortie);
               $entityManager->flush();

               $this->addFlash('success', 'Vous êtes bien retiré de la liste des inscrits.');
           } else {
               $this->addFlash('echec', 'La date limite d\'inscription est dépassée');
           }
       }
        return $this->redirectToRoute('sortie');
    }

    /**
     * @Route ("/sortie/edit/{id}", name="sortie_edit")
     */
    public function editSortie(Sortie $sortie, Request $request, EntityManagerInterface $entityManager): Response{

        $formEditSortie = $this->createForm(EditSortieType::class, $sortie);
        $formEditSortie->handleRequest($request);

        if ($formEditSortie->isSubmitted() && $formEditSortie->isValid()){

            if($formEditSortie->get('enregistrer')->isClicked()){

                $entityManager->flush();
            }
            if($formEditSortie->get('publier')->isClicked()){
                $sortie->setEtat($etat= $entityManager->getRepository('App:Etat')->find(2));

                $entityManager->flush();
            }
            return $this->redirectToRoute('sortie');
        }
        return $this->render('sortie/editSortie.html.twig', [
            "sortie" => $sortie,
            "formEditSortie" => $formEditSortie->createView()
        ]);
    }

    /**
     * @Route ("/sortie/publier/{id}", name="sortie_publier")
     */
    public function publierSortie(SortieRepository $sortieRepository, int $id, EntityManagerInterface $entityManager, Request $request):RedirectResponse {

        if($this->isCsrfTokenValid('token_publier', $request->get('token'))) {
            $sortie = $sortieRepository->find($id);
            if($sortie == null){
                $this->addFlash('echec', 'La sortie n\'existe pas');
            }
            if ($sortie->getEtat()->getId() == 1) {
                $sortie->setEtat($etat = $entityManager->getRepository('App:Etat')->find(2));
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'Félicitation, la sortie vient d\'être publiée !');
            } else {
                $this->addFlash('echec', 'La sortie a déjà été publiée');
            }
        }
        return $this->redirectToRoute('sortie');
    }

    /**
     * @Route ("/sortie/annulee/{id}", name="sortie_annulee")
     */
    public function annuleeSortie(Sortie $sortie, Request $request, EntityManagerInterface $entityManager):Response {
        $formCancel=$this->createForm(CancelSortieType::class, $sortie);
        $formCancel->handleRequest($request);

        if ($formCancel->isSubmitted() && $formCancel->isValid()){
            $sortie->setEtat($etat= $entityManager->getRepository('App:Etat')->find(6));

            $entityManager->flush();
            return $this->redirectToRoute('sortie');
        }

        return $this->render('sortie/annulerSortie.html.twig', [
            "sortie" => $sortie,
            "form" => $formCancel->createView()
        ]);
    }

    /**
     * @Route ("/sortie/delete/{id}", name="sortie_delete")
     */
    public function deleteSortie(Sortie $sortie, EntityManagerInterface $entityManager, Request $request): RedirectResponse {

        if($this->isCsrfTokenValid('token_delete', $request->get('token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sortie');
    }
}

























