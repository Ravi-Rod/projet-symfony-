<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleType;
use App\Repository\RoleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/roles')]
class RolesController extends AbstractController
{
    #[Route('/', name: 'app_roles_index', methods: ['GET'])]
    public function index(RoleRepository $roleRepository): Response
    {
        return $this->render('roles/index.html.twig', [
            'roles' => $roleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_roles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                
            $entityManager = $doctrine->getManager();
            $entityManager->persist($role);
            $entityManager->flush();
        }

        return $this->renderForm('roles/new.html.twig', [
            'role' => $role,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_roles_show', methods: ['GET'])]
    public function show(Role $role): Response
    {
        return $this->render('roles/show.html.twig', [
            'role' => $role,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_roles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Role $role, RoleRepository $roleRepository): Response
    {
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roleRepository->add($role, true);

            return $this->redirectToRoute('app_roles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('roles/edit.html.twig', [
            'role' => $role,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_roles_delete', methods: ['POST'])]
    public function delete(Request $request, Role $role, RoleRepository $roleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$role->getId(), $request->request->get('_token'))) {
            $roleRepository->remove($role, true);
        }

        return $this->redirectToRoute('app_roles_index', [], Response::HTTP_SEE_OTHER);
    }
}
