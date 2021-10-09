<?php

namespace App\Controller;

use App\Entity\User\User;
use App\Form\UserCreateForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/create", name="user_create")
     */
    public function create(
        Request $request,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $user = new User();

        $form = $this->createForm(UserCreateForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());

            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return $this->render('@admin/user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="user_show", requirements={"id"="\d+"})
     */
    public function show(int $id): Response
    {
        /** @var User|null $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('The user not found');
        }

        return $this->render('@admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/remove/{id}", name="user_remove", requirements={"id"="\d+"})
     */
    public function remove(Session $session, int $id): Response
    {
        /** @var User|null $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('The user not found');
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $session->getFlashBag()->set(
            'lastAction',
            []
        )

        return $this->redirectToRoute('user_index', [
            'message' => 'The user created!',
            'status'
        ]);
    }

    /**
     * @Route("/index", name="user_index")
     */
    public function index(): Response
    {
        return $this->render('@admin/user/index.html.twig', [
            'users' => $this->entityManager->getRepository(User::class)->findAll(),
        ]);
    }
}
