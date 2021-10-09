<?php

namespace App\Controller;

use App\Entity\User\User;
use App\Form\UserCreateForm;
use App\Form\UserUpdateForm;
use App\Model\FlashMessage\LastActionFlashMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private LastActionFlashMessage $flashMessage;
    private FlashBagInterface $flashBag;

    public function __construct(
        EntityManagerInterface $entityManager,
        LastActionFlashMessage $flashMessage,
        FlashBagInterface $flashBag
    )
    {
        $this->entityManager    = $entityManager;
        $this->flashMessage     = $flashMessage;
        $this->flashBag         = $flashBag;
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

            $this->flashBag->set(...$this->flashMessage
                ->getSuccessData(LastActionFlashMessage::ACTION_CREATE, 'user')
            );

            return $this->redirectToRoute('user_index');
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
    public function remove(int $id): Response
    {
        /** @var User|null $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('The user not found');
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->flashBag->set(...$this->flashMessage->getSuccessData(
            LastActionFlashMessage::ACTION_REMOVE,
            'user'
        ));

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/update/{id}", name="user_update", requirements={"id"="\d+"})
     */
    public function update(Request $request, int $id, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var User|null $user */
        $user = $this->entityManager->getRepository(User::class)->find($id);

        $form = $this->createForm(UserUpdateForm::class, $user);
        $form->handleRequest($request);

        if (!$user) {
            throw $this->createNotFoundException('The user not found');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());

            $user->setPassword($hashedPassword);

            $this->entityManager->flush();

            $this->flashBag->set(...$this->flashMessage->getSuccessData(
                LastActionFlashMessage::ACTION_UPDATE,
                'user'
            ));

            return $this->redirectToRoute('user_index');
        }

        return $this->render('@admin/user/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="user_index")
     */
    public function index(): Response
    {
        return $this->render('@admin/user/index.html.twig', [
            'users' => $this->entityManager->getRepository(User::class)->findAll(),
        ]);
    }
}
