<?php

namespace App\Controller;
use App\Form\ClubType;
use App\Entity\Club;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClubRepository;
use Doctrine\Persistence\ManagerRegistry;
class ClubController extends AbstractController
{
    #[Route('/club', name: 'app_club')]
    public function index(): Response
    {
        return $this->render('club/index.html.twig', [
            'controller_name' => 'ClubController',
        ]);
    }
    #[Route('club/get/{nom}', name: 'getname')]
    public function getName($nom): Response
    {
        return $this->render('club/detail.html.twig', [
            'nom' => $nom,
        ]);
    }
    #[Route('club/list', name: 'formations')]
    public function list(): Response
    {
        $formations = array(
            array('ref' => 'form147', 'Titre' => 'Formation Symfony
            4','Description'=>'formation pratique',
            'date_debut'=>'12/06/2020', 'date_fin'=>'19/06/2020',
            'nb_participants'=>19) ,
            array('ref'=>'form177','Titre'=>'Formation SOA' ,
            'Description'=>'formation theorique','date_debut'=>'03/12/2020','date_fin'=>'10/12/2020',
            'nb_participants'=>0),
            array('ref'=>'form178','Titre'=>'Formation Angular' ,
            'Description'=>'formation theorique','date_debut'=>'10/06/2020','date_fin'=>'14/06/2020',
            'nb_participants'=>12));
   
        return $this->render('club/list.html.twig', ['formations'=>$formations]);
    }
    #[Route('club/formation/{titre}', name:'club/formation')]
    public function formation($titre): Response
    {
        return $this->render('club/detail.html.twig', [
            'titre'=> $titre
        ]);
    }
    #[Route('club/all', name:'club_get_all')]
    public function getClubs(ClubRepository $repo):Response{
        $clubs=$repo->findAll();
        return $this->render('club/clubs.html.twig', [
            'clubs'=> $clubs
        ]);
    }
    #[Route('club/{id}', name:'club_get_by_id')]
    public function getClub(ClubRepository $repo,$id):Response{
        $club=$repo->find($id);
        return $this->render('club/detailsClub.html.twig', [
            'club'=> $club
        ]);
    }
    #[Route('removeClub/{id}', name:'club_remove')]
    public function removeClub(ManagerRegistry $doctrine,$id):Response{

        $em=$doctrine->getManager();
        $repo=$doctrine->getRepository(club::class);
        $club=$repo->find($id);
        $em->remove($club);
        $em->flush();
        return $this->redirectToRoute('club_get_all');
    
    }
    #[Route('addClub/', name:'club_add')]
    public function new(Request $request,ManagerRegistry $doctrine): Response
    {
       
           $club = new Club();
           $form = $this->createForm(ClubType::class,$club);
           $form->handleRequest($request);
           if($form->isSubmitted() && $form->isValid()) {
           $club = $form->getData();

           $entityManager = $doctrine->getManager();
           $repo=$doctrine->getRepository(club::class);
            $entityManager->persist($club);
            $entityManager->flush();
            return $this->redirectToRoute('club_get_all');
           } 
           return $this->render('club/addClub.html.twig',['form' => $form->createView()]);
    }
    #[Route('editClub/{id}', name:'club_edit')]
    public function edit(Request $request,ManagerRegistry $doctrine ,$id) {
        $club = new club();
        $club = $this->getDoctrine()->getRepository(Club::class)->find($id);
       
        $form = $this->createForm(ClubType::class,$club);
       
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
       
        $entityManager = $doctrine->getManager();
        $entityManager->flush();
       
        return $this->redirectToRoute('club_get_all');
        }
       
        return $this->render('club/editClub.html.twig', ['form' =>
       $form->createView()]);
        }
       
}
