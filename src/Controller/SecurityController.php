<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface as ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
    * @Route("/inscription", name="app_registration")
    */
    public function Registration(Request $request, ObjectManager $monManager, UserPasswordEncoderInterface $monEncodeur, string $photoDir, \Swift_Mailer $mailer) {

        $user = new User;

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $monEncodeur->encodePassword( $user ,$user->getPassword()); 
            $password = $user->getPassword();          
            $user->setPassword($hashedPassword);

            if ($photo = $form['photo']->getData()) {
                $fileName = bin2hex(random_bytes(6)).'.'.$photo->guessExtension();
                try {
                    $photo->move($photoDir, $fileName);
                } catch (FileException $e) {
                    // unable to upload the photo, give up
                }
                $user->setAvatar($fileName);
            }

            $monManager->persist($user);
            $monManager->flush();

            $mail = (new \Swift_Message('Inscription confirmé'))
                ->setFrom('toDo@bot.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/registration.html.twig',
                        ['firstName' => $user->getFirstName(),
                          'password' => $password]
                    ),
                    'text/html'
                );
                $mailer->send($mail);

            return $this->redirectToRoute('home');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
