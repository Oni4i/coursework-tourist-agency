<?php

namespace App\Controller;

use App\Entity\Point\Point;
use App\Form\PointCreateForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/point")
 */
class PointController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/create", name="point_create")
     */
    public function create(
        Request $request
    ): Response
    {
        $point = new Point();

        $form = $this->createForm(PointCreateForm::class, $point);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($point);
            $this->entityManager->flush();
        }

        return $this->render('@admin/point/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
