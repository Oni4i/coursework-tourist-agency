<?php

namespace App\Controller;

use App\Entity\Order\Order;
use App\Entity\User\User;
use App\Form\OrderCreateForm;
use App\Form\OrderUpdateForm;
use App\Form\RemoveForm;
use App\Model\FlashMessage\LastActionFlashMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
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
     * @Route("/create", name="order_create")
     */
    public function create(Request $request): Response
    {
        $order = new Order();

        $form = $this->createForm(OrderCreateForm::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $order->setUser($user);

            $this->entityManager->persist($order);
            $this->entityManager->flush();

            return $this->redirectToRoute('order_index');
        }

        return $this->render('@admin/order/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="order_index")
     */
    public function index(): Response
    {
        return $this->render('@admin/order/index.html.twig', [
            'orders' => $this->entityManager->getRepository(Order::class)->findAll(),
        ]);
    }

    /**
     * @Route("/remove/{id}", name="order_remove", requirements={"id"="\d+"})
     */
    public function remove(Request $request, int $id): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        $form = $this->createForm(RemoveForm::class, $order);
        $form->handleRequest($request);

        if (!$order) {
            throw $this->createNotFoundException('The order not found');
        }

        if ($form->isSubmitted()) {
            $this->entityManager->remove($order);
            $this->entityManager->flush();

            $this->flashBag->set(...$this->flashMessage->getSuccessData(
                LastActionFlashMessage::ACTION_REMOVE,
                'order'
            ));

            return $this->redirectToRoute('order_index');
        }

        return $this->render('@admin/order/remove.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", name="order_update", requirements={"id"="\d+"})
     */
    public function update(Request $request, int $id): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->find($id);

        $form = $this->createForm(OrderUpdateForm::class, $order);
        $form->handleRequest($request);

        if (!$order) {
            throw $this->createNotFoundException('The order not found');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->flush();

            $this->flashBag->set(...$this->flashMessage->getSuccessData(
                LastActionFlashMessage::ACTION_UPDATE,
                'order'
            ));

            return $this->redirectToRoute('order_index');
        }

        return $this->render('@admin/order/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
