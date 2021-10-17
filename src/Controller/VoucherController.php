<?php

namespace App\Controller;

use App\Entity\Voucher\Voucher;
use App\Form\RemoveForm;
use App\Form\VoucherCreateForm;
use App\Form\VoucherUpdateForm;
use App\Model\FlashMessage\LastActionFlashMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/voucher")
 */
class VoucherController extends AbstractController
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
     * @Route("/create", name="voucher_create")
     */
    public function create(Request $request): Response
    {
        $voucher = new Voucher();

        $form = $this->createForm(VoucherCreateForm::class, $voucher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($voucher);
            $this->entityManager->flush();

            return $this->redirectToRoute('voucher_index');
        }

        return $this->render('@admin/voucher/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="voucher_index")
     */
    public function index(): Response
    {
        return $this->render('@admin/voucher/index.html.twig', [
            'vouchers' => $this->entityManager->getRepository(Voucher::class)->findAll(),
        ]);
    }

    /**
     * @Route("/remove/{id}", name="voucher_remove", requirements={"id"="\d+"})
     */
    public function remove(Request $request, int $id): Response
    {
        $voucher = $this->entityManager->getRepository(Voucher::class)->find($id);

        $form = $this->createForm(RemoveForm::class, $voucher);
        $form->handleRequest($request);

        if (!$voucher) {
            throw $this->createNotFoundException('The voucher not found');
        }

        if ($form->isSubmitted()) {
            $this->entityManager->remove($voucher);
            $this->entityManager->flush();

            $this->flashBag->set(...$this->flashMessage->getSuccessData(
                LastActionFlashMessage::ACTION_REMOVE,
                'voucher'
            ));

            return $this->redirectToRoute('voucher_index');
        }

        return $this->render('@admin/voucher/remove.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/{id}", name="voucher_update", requirements={"id"="\d+"})
     */
    public function update(Request $request, int $id): Response
    {
        $voucher = $this->entityManager->getRepository(Voucher::class)->find($id);

        $form = $this->createForm(VoucherUpdateForm::class, $voucher);
        $form->handleRequest($request);

        if (!$voucher) {
            throw $this->createNotFoundException('The voucher not found');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->flush();

            $this->flashBag->set(...$this->flashMessage->getSuccessData(
                LastActionFlashMessage::ACTION_UPDATE,
                'voucher'
            ));

            return $this->redirectToRoute('voucher_index');
        }

        return $this->render('@admin/voucher/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
