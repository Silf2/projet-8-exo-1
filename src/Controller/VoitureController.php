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

    #[Route('/', name: 'app_home')]
    public function index(VoitureRepository $voitureRepository): Response
    {
        return $this->render('home/home.html.twig', [
            'voitures' => $voitureRepository->findAll(),
        ]);
    }

    #[Route('/voiture/add', name : 'app_car_add')]
    public function addVoiture(Request $request, EntityManagerInterface $entityManager): Response{
        $voiture = new Voiture();

        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $voiture = $form->getData();

            $entityManager->persist($voiture);
            $entityManager->flush();

            return $this->redirectToRoute('app_car', ['id' => $voiture->getId()]);
        };

        return $this->render('nouvelle-voiture/nouvelle-voiture.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/voiture/{id}', name : 'app_car')]
    public function voiture(Voiture $voiture): Response{
        var_dump($voiture->getId());
        die;

        if(!$voiture){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('voiture/voiture.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    #[Route('/voiture/{id}/delete', name : 'app_car_delete')]
    public function deleteVoiture(EntityManagerInterface $entityManager, Voiture $voiture): Response{
        
        $entityManager->remove($voiture);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
