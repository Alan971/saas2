<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AdminAccountController extends AbstractController
{
    #[Route('/admin/account/{criteria?}/{sens?}', name: 'app_admin_account')]
    public function index(EntityManagerInterface $em, ?string $criteria='email' , ?string $sens='ASC' ): Response
    {
        // Sécuriser les valeurs attendues pour éviter une erreur Doctrine
        if (!in_array(strtoupper($sens), ['ASC', 'DESC'])) {
            $sens = 'ASC'; // Valeur par défaut
        }

        // Liste des champs valides
        $validFields = ['id', 'email', 'role']; 

        // Vérification du critère de tri
        if (!in_array($criteria, $validFields)) {
            return $this->redirectToRoute("app_admin_account", [
                'criteria' => 'id',
                'sens' => $sens // Garde le sens actuel
            ]);
        }
        $users = $em->getRepository(User::class)->findBy([], [$criteria => $sens]);

        return $this->render('admin_account/index.html.twig', [
            "users" => $users,
        ]);
    }

    #[Route("/admin/account_modify/{id}", name: "admin_account_modify")]
    public function modify(EntityManagerInterface $em, int $id): Response
    {
        try {
        /** @var User $user */
        $user = $em->getRepository(User::class)->find($id);
        }
        catch (\Exception $e) { 
            return $this->redirectToRoute("app_admin_account");
        }
        
        return $this->render("admin_account/modify.html.twig", [
            "user" => $user
        ]);
    }
    #[Route("/admin/account_delete/{id}", name: "admin_account_delete")]
    public function delete(int $id, EntityManagerInterface $em ): Response
    {
        try {
            /** @var User $user */
            $user = $em->getRepository(User::class)->find($id);
            $em->remove($user);
            $em->flush();
            $this->addFlash('message', 'Utilisateur supprimé');
        }
        catch (\Exception $e) {
            $this->addFlash('message', $e);
        }
        return $this->redirectToRoute("app_admin_account");
    }
    #[Route("/admin/account_lock/{id}", name: "admin_account_lock")]
    public function lock(EntityManagerInterface $em, int $id ): Response
    {
        /** @var User $user */
        $user = $em->getRepository(User::class)->find($id);
        if ($user->isVerified()) {
            $user->setIsVerified(false);
        } else {
            $user->setIsVerified(true);
        }
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_admin_account');
    }

    // #[Route('/admin/account/add', name: 'admin_account_add')]
    // public function add(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(RegistrationFormType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         if($user->getPassword() !== null){
    //             $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
    //         }
    //         $em->persist($user);
    //         $em->flush();
    //         return $this->redirectToRoute('admin_account_index');
    //     }

    //     return $this->render('admin/account/add.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

}
