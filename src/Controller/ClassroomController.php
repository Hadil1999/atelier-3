<?php

namespace App\Controller;
use App\Form\ClassroomType;
use App\Entity\Classroom;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ClassroomRepository;
use Doctrine\Persistence\ManagerRegistry;
class ClassroomController extends AbstractController
{
    #[Route('/classroom', name: 'app_classroom')]
    public function index(): Response
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }

    #[Route('classroom/all', name:'classroom_get_all')]
    public function getclassrooms(classroomRepository $repo):Response{
        $classrooms=$repo->findAll();
        return $this->render('classroom/classrooms.html.twig', [
            'classrooms'=> $classrooms
        ]);
    }
    #[Route('classroom/{id}', name:'classroom_get_by_id')]
    public function getclassroom(classroomRepository $repo,$id):Response{
        $classroom=$repo->find($id);
        return $this->render('classroom/detailsclassroom.html.twig', [
            'classroom'=> $classroom
        ]);
    }
    #[Route('removeclassroom/{id}', name:'classroom_remove')]
    public function removeclassroom(ManagerRegistry $doctrine,$id):Response{

        $em=$doctrine->getManager();
        $repo=$doctrine->getRepository(Classroom::class);
        $classroom=$repo->find($id);
        $em->remove($classroom);
        $em->flush();
        return $this->redirectToRoute('classroom_get_all');
    
    }
    #[Route('addclassroom/', name:'classroom_add')]
    public function new(Request $request,ManagerRegistry $doctrine): Response
    {
       
           $classroom = new Classroom();
           $form = $this->createForm(ClassroomType::class,$classroom);
           $form->handleRequest($request);
           if($form->isSubmitted() && $form->isValid()) {
            $classroom = $form->getData();
            $entityManager = $doctrine->getManager();
            $repo=$doctrine->getRepository(classroom::class);
            $entityManager->persist($classroom);
            $entityManager->flush();
            return $this->redirectToRoute('classroom_get_all');
           } 
           //return $this->render('classroom/addclassroom.html.twig',['form' => $form->createView()]);
           return $this->renderForm('classroom/addclassroom.html.twig',['form' => $form]);
    }
    #[Route('editclassroom/{id}', name:'classroom_edit')]
    public function edit(Request $request,ManagerRegistry $doctrine ,$id) {
        $classroom = new Classroom();
        $classroom = $this->getDoctrine()->getRepository(Classroom::class)->find($id);
       
        $form = $this->createForm(ClassroomType::class,$classroom);
       
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
       
        $entityManager = $doctrine->getManager();
        $entityManager->flush();
       
        return $this->redirectToRoute('classroom_get_all');
        }
       
        return $this->render('classroom/editclassroom.html.twig', ['form' =>
       $form->createView()]);
        }
       
}
