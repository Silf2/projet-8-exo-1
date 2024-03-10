<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VoitureRepository;
use App\Entity\Voiture;
use App\Form\VoitureType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class VoitureController extends AbstractController
{
    public function __construct(private VoitureRepository $voitureRepository, private EntityManagerInterface $entityManager){
    }

    #[Route('/', name: 'app_home')]
    public function index(VoitureRepository $voitureRepository): Response
    {
        $voitures = $voitureRepository->findAll();

        return $this->render('home/home.html.twig', [
            'voitures' => $voitures,
        ]);
    }

    #[Route('/voiture/add', name : 'app_car_add')]
    public function addVoiture(Request $request): Response{
        $voiture = new Voiture();

        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $voiture = $form->getData();

            $this->entityManager->persist($voiture);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_car', ['id' => $voiture->getId()]);
        };

        return $this->render('nouvelle-voiture/nouvelle-voiture.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/voiture/{id}', name : 'app_car')]
    public function voiture(int $id): Response{
        $voiture = $this->voitureRepository->find($id);

        if(!$voiture){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('voiture/voiture.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    #[Route('/voiture/{id}/delete', name : 'app_car_delete')]
    public function deleteVoiture(int $id): Response{
        $voiture = $this->voitureRepository->find($id);

        if(!$voiture){
            return $this->redirectToRoute('app_home');
        }

        $this->entityManager->remove($voiture);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
