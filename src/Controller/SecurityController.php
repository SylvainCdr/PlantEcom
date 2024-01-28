<?php

namespace App\Controller;

use App\Entity\Users;
use App\Service\SendMailService;
use Doctrine\Common\Lexer\Token;
use Doctrine\ORM\Mapping\Entity;
use App\Form\ResetPasswordFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ResetPasswordRequestFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error
            ]
        );
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/oubli-pass', name: 'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UsersRepository $usersRepository,
        TokenGeneratorInterface $tokenGeneratorInterface,
        EntityManagerInterface $entityManager, 
        SendMailService $mail
    ): Response
    {
$form = $this->createForm(ResetPasswordRequestFormType::class);

$form->handleRequest($request);

// dd($form);

if ($form->isSubmitted() && $form->isValid()) {
    //On va chercher l'utilisateur par son email
    $user = $usersRepository->findOneByEmail($form->get('email')->getData());

// On vérifie si l'utilisateur existe
    if ($user) {

        // On  génére un token de reinitalisation de mot de passe
$token = $tokenGeneratorInterface->generateToken();
// dd($token);
$user ->setResetToken($token);
$entityManager->persist($user);
$entityManager->flush();

// On génère l'URL de réinitialisation de mot de passe
$url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
// dd($url);

//On crée les données du mail

$context = [
    'url' => $url,
    'user' => $user
];

// On envoie le mail
$mail->send(
    'no-reply@eshop.com',
    $user->getEmail(),
    'Réinitialisation de votre mot de passe',
    'password_reset',
    $context
);

$this->addFlash('success', 'Un email de réinitialisation de mot de passe vous a été envoyé');
return $this->redirectToRoute('app_login');

    }

    // $user est null
$this->addFlash('danger', 'Un problème est survenu');
return $this->redirectToRoute('app_login');
}


        return $this->render('security/reset_password_request.html.twig',
        [
            'requestPassForm' => $form->createView()
        ]);
    }

#[Route(path: '/oubli-pass/{token}', name: 'reset_pass')]
public function resetPass(
    string $token,
    Request $request,
    UsersRepository $usersRepository,
    EntityManagerInterface $entityManager,
    UserPasswordHasherInterface $passwordHasher
): Response
{
    // On vérifie si on a le token en BDD

    $user = $usersRepository->findOneByResetToken($token);
// dd($user);

if ($user) {
$form = $this->createForm(ResetPasswordFormType::class);

$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    // On supprime le token
    $user->setResetToken('');
    $user->setPassword(
        $passwordHasher->hashPassword(
            $user,
            $form->get('password')->getData()
        )
    );

    $entityManager->persist($user);
    $entityManager->flush();

    $this->addFlash('success', 'Votre mot de passe a bien été modifié');
    return $this->redirectToRoute('app_login');
}

return $this->render('security/reset_password.html.twig',
[
    'passForm' => $form->createView()
]);

}

$this->addFlash('danger', 'Token inconnu');
return $this->redirectToRoute('app_login');


}
}
