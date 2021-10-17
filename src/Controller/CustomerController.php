<?php

namespace App\Controller;

use App\Entity\Customer\Customer;
use App\Entity\User\User;
use App\Form\CustomerCreateForm;
use App\Form\CustomerUpdateForm;
use App\Form\RemoveForm;
use App\Model\FlashMessage\LastActionFlashMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customer")
 */
class CustomerController extends AbstractController
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
     * @Route("/create", name="customer_create")
     */
    public function create(Request $request): Response
    {
        $customer = new Customer();

        $form = $this->createForm(CustomerCreateForm::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $customer->setCreatedByUser($user);

            $this->entityManager->persist($customer);
            $this->entityManager->flush();

            return $this->redirectToRoute('customer_index');
        }

        return $this->render('@admin/customer/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="customer_index")
     */
    public function index(): Response
    {
        return $this->render('@admin/customer/index.html.twig', [
            'customers' => $this->entityManager->getRepository(Customer::class)->findAll(),
        ]);
    }

    /**
     * @Route("/remove/{id}", name="customer_remove", requirements={"id"="\d+"})
     */
    public function remove(Request $request, int $id): Response
    {
        $customer = $this->entityManager->getRepository(Customer::class)->find($id);

        $form = $this->createForm(RemoveForm::class, $customer);
        $form->handleRequest($request);

        if (!$customer) {
            throw $this->createNotFoundException('The customer not found');
        }

        if ($form->isSubmitted()) {
            $this->entityManager->remove($customer);
            $this->entityManager->flush();

            $this->flashBag->set(...$this->flashMessage->getSuccessData(
                LastActionFlashMessage::ACTION_REMOVE,
                'customer'
            ));

            return $this->redirectToRoute('customer_index');
        }

        return $this->render('@admin/customer/remove.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", name="customer_update", requirements={"id"="\d+"})
     */
    public function update(Request $request, int $id): Response
    {
        $customer = $this->entityManager->getRepository(Customer::class)->find($id);

        $form = $this->createForm(CustomerUpdateForm::class, $customer);
        $form->handleRequest($request);

        if (!$customer) {
            throw $this->createNotFoundException('The customer not found');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->flashBag->set(...$this->flashMessage->getSuccessData(
                LastActionFlashMessage::ACTION_UPDATE,
                'customer'
            ));

            return $this->redirectToRoute('customer_index');
        }

        return $this->render('@admin/customer/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
