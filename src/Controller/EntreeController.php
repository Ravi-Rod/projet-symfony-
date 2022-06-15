<?php

namespace App\Controller;

use App\Entity\Entree;
use App\Form\EntreeType;
use App\Repository\EntreeRepository;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/entree')]
class EntreeController extends AbstractController
{
    #[Route('/', name: 'app_entree_index', methods: ['GET'])]
    public function index(EntreeRepository $entreeRepository): Response
    {
        return $this->render('entree/index.html.twig', [
            'entrees' => $entreeRepository->findAll(),
        ]);
    }

    #[Route('/show', name: 'app_entree_affiche', methods: ['GET'])]
    public function affiche(entreeRepository $entreeRepository): Response
    {
        
        return $this->render('entree/affiche.html.twig', [
            'entrees' => $entreeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_entree_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine, ProduitRepository $produitRepository): Response
    {
        $entree = new Entree();
        $form = $this->createForm(EntreeType::class, $entree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                
            $entityManager = $doctrine->getManager();
            $entityManager->persist($entree);
            $entityManager->flush();
        }

        return $this->renderForm('entree/new.html.twig', [
            'entree' => $entree,
            'form' => $form,
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_entree_show', methods: ['GET'])]
    public function show(Entree $entree, ManagerRegistry $doctrine, int $id): Response
    {
        $entree = $doctrine->getRepository(Entree::class)->find($id);

        if (!$entree) {
            throw $this->createNotFoundException(
                'pas d\'entrées '.$id
            );
        }

        return $this->render('entree/show.html.twig', [
            'entree' => $entree,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_entree_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entree $entree, EntreeRepository $entreeRepository): Response
    {
        $form = $this->createForm(EntreeType::class, $entree);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entreeRepository->add($entree, true);

            return $this->redirectToRoute('app_entree_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entree/edit.html.twig', [
            'entree' => $entree,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_entree_delete', methods: ['POST'])]
    public function delete(Request $request, Entree $entree, EntreeRepository $entreeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$entree->getId(), $request->request->get('_token'))) {
            $entreeRepository->remove($entree, true);
        }

        return $this->redirectToRoute('app_entree_index', [], Response::HTTP_SEE_OTHER);
    }
}
