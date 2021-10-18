<?php

namespace App\Controller;

use App\Entity\Point\Point;
use App\Form\PointCreateForm;
use App\Form\PointUpdateForm;
use App\Form\RemoveForm;
use App\Model\FlashMessage\LastActionFlashMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/point")
 */
class PointController extends AbstractController
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
     * @Route("/create", name="point_create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        $point = new Point();

        $form = $this->createForm(PointCreateForm::class, $point);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($point);
            $this->entityManager->flush();

            return $this->redirectToRoute('point_index');
        }

        return $this->render('@admin/point/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="point_index")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('@admin/point/index.html.twig', [
            'points' => $this->entityManager->getRepository(Point::class)->findAll(),
        ]);
    }

    /**
     * @Route("/remove/{id}", name="point_remove", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function remove(Request $request, int $id): Response
    {
        /** @var Point|null $user */
        $point = $this->entityManager->getRepository(Point::class)->find($id);

        $form = $this->createForm(RemoveForm::class, $point);
        $form->handleRequest($request);

        if (!$point) {
            throw $this->createNotFoundException('The point not found');
        }

        if ($form->isSubmitted()) {
            $this->entityManager->remove($point);
            $this->entityManager->flush();

            $this->flashBag->set(...$this->flashMessage->getSuccessData(
                LastActionFlashMessage::ACTION_REMOVE,
                'point'
            ));

            return $this->redirectToRoute('point_index');
        }

        return $this->render('@admin/point/remove.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/{id}", name="point_update", requirements={"id"="\d+"})
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function update(Request $request, int $id): Response
    {
        /** @var Point|null $user */
        $point = $this->entityManager->getRepository(Point::class)->find($id);

        $form = $this->createForm(PointUpdateForm::class, $point);
        $form->handleRequest($request);

        if (!$point) {
            throw $this->createNotFoundException('The point not found');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->flush();

            $this->flashBag->set(...$this->flashMessage->getSuccessData(
                LastActionFlashMessage::ACTION_UPDATE,
                'point'
            ));

            return $this->redirectToRoute('point_index');
        }

        return $this->render('@admin/point/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
