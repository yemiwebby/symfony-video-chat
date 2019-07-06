<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('App:User');
    }
    
    /**
     * @Route("/register/page", name="user_registration", methods={"GET"})
     */
    public function registerAction(Request $request)
    {
        return $this->render('registration/index.html.twig');
    }

    /**
     * @Route("/register", name="register", methods={"POST"})
     * @param \Symfony\Component\HttpFoundation\Request
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerUsers(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $user->setUsername($request->request->get('username'))
            ->setEmail($request->request->get('email'))
            ->setPassword($encoder->encodePassword($user, $request->request->get('password')))
        ;
        $this->updateDatabase($user);

        $this->addFlash(
            'success',
            'Registration successful'
        );
        return $this->redirectToRoute('room');

    }

    function updateDatabase($object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
