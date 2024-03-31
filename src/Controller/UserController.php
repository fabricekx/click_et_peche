<?php

namespace App\Controller;

use App\Form\EditProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Validator\Constraints\UserPasswordValidator;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit')]
    public function modify(Request $request, EntityManagerInterface $entityManager): Response
    {


        $form = $this->createForm(EditProfilType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $entityManager->persist($this->getUser()); // insérer en base
                $entityManager->flush(); // fermer la transaction executée par la bdd

                $this->addFlash(
                    'success',
                    'Votre profile a bien été mis à jour !'
                );
                // récupération de l'image de orofil
                // $user = $this->getUser();
                // $avatarFilename = $user->getAvatar();
                // $avatarPath = '/public/images/avatars' . $avatarFilename; // Remplacez ceci par le chemin réel de votre répertoire d'avatars

                return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);

            }
        }


        return $this->render('user/edit_profil.html.twig', [
            'editProfilType' => $form,
        ]);
    }



    #[Route('/profile/edit_pwd', name: 'app_profile_edit_pwd')]
    public function modify_pwd(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder): Response
    {
        if ($request->isMethod('POST')) {
            $user = $this->getUser();

            // on vérifie que les deux mots de passe sont identiques
            if ($request->request->get('password1') == $request->request->get('password2')) {
                // Vérifier si le mot de passe respecte les contraintes
                $password1 = $request->request->get('password1');
                if (strlen($password1) < 6 || !preg_match('/[0-9]/', $password1) || !preg_match('/[!@#$%^&*-]/', $password1)) {
                    $this->addFlash('errors', 'Votre mot de passe doit contenir au moins 6 caractères et un caractère spécial');
                    return $this->redirectToRoute('app_profile_edit_pwd');
                }


                // on hash le nouveau mot de passe et on le modifie 
                $newEncodedPassword = $passwordEncoder->hashPassword($user, $request->request->get('password1'));
                $user->setPassword($newEncodedPassword);
                $entityManager->persist($this->getUser()); // insérer en base
                $entityManager->flush(); // fermer la transaction executée par la bdd
                $this->addFlash('success', 'Votre mot de passe a été modifié');
                return $this->redirectToRoute('app_user');
            } else {
                $this->addFlash('errors', 'Les mots de passe ne sont pas identiques');

            }


        }


        return $this->render('user/modify_password.html.twig', [

        ]);
    }
}
