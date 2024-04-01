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
use App\Service\ImageService;

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
public function modify(
    Request $request, 
    EntityManagerInterface $entityManager,
    ImageService $imageService
): Response {
    $oldAvatarPictureName = $this->getUser()->getAvatar();
    $this->getUser()->setAvatar(null);
    $form = $this->createForm(EditProfilType::class, $this->getUser());
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Vérifie si un fichier a été soumis dans le formulaire
        $avatarFile = $form->get('avatar')->getData();
        if ($avatarFile) {
            // Utilisez la méthode copyImage pour gérer le téléchargement de l'image
            $fileName = $imageService->copyImage("avatar", $this->getParameter("avatar_picture_directory"), $form);
            // Mettez à jour l'avatar de l'utilisateur avec le nom du fichier
            $this->getUser()->setAvatar($fileName);
        } else {
            $this->getUser()->setAvatar($oldAvatarPictureName);
        }

        // Enregistrez l'utilisateur en base de données
        $entityManager->persist($this->getUser());
        $entityManager->flush();

        // Ajoutez un message flash pour indiquer que le profil a été mis à jour avec succès
        $this->addFlash('success', 'Votre profil a bien été mis à jour !');

        // Redirigez l'utilisateur vers la page de profil
        return $this->redirectToRoute('app_user', [], Response::HTTP_SEE_OTHER);
    }

    // Affichez le formulaire de modification du profil
    return $this->render('user/edit_profil.html.twig', [
        'editProfilType' => $form,
        'oldAvatar' => $oldAvatarPictureName,
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
