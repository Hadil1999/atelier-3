<?php
namespace App\Controller;
use App\Entity\Student;
use App\Form\StudentType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
  class StudentController extends AbstractController{
    #[Route('/bonjour',name:'index')]
    public function index():response{
        return new response("Bonjour mes étudiants");
    }
    #[Route('student/all', name:'student_get_all')]
    public function getStudents(StudentRepository $repo):Response{
        $students=$repo->findAll();
        return $this->render('student/students.html.twig', [
            'students'=> $students
        ]);
    }
    #[Route('student/{nsc}', name:'student_get_by_nsc')]
    public function getStudent(StudentRepository $repo,$nsc):Response{
        $student=$repo->find($nsc);
        return $this->render('student/detailsStudent.html.twig', [
            'student'=> $student
        ]);
    }
    #[Route('removeStudent/{nsc}', name:'student_remove')]
    public function removeStudent(ManagerRegistry $doctrine,$nsc):Response{

        $em=$doctrine->getManager();
        $repo=$doctrine->getRepository(student::class);
        $student=$repo->find($nsc);
        $em->remove($student);
        $em->flush();
        return $this->redirectToRoute('student_get_all');
    
    }
    #[Route('addStudent/', name:'student_add')]
    public function new(Request $request,ManagerRegistry $doctrine): Response
    {
       
           $student = new Student();
           $form = $this->createForm(StudentType::class,$student);
           $form->handleRequest($request);
           if($form->isSubmitted() && $form->isValid()) {
           $student = $form->getData();

           $entityManager = $doctrine->getManager();
           $repo=$doctrine->getRepository(Student::class);
            $entityManager->persist($student);
            $entityManager->flush();
            return $this->redirectToRoute('student_get_all');
           } 
           return $this->render('student/addStudent.html.twig',['form' => $form->createView()]);
    }
    #[Route('editStudent/{nsc}', name:'student_edit')]
    public function edit(Request $request,ManagerRegistry $doctrine ,$nsc) {
        $student = new Student();
        $student = $this->getDoctrine()->getRepository(Student::class)->find($nsc);
       
        $form = $this->createForm(StudentType::class,$student);
       
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
       
        $entityManager = $doctrine->getManager();
        $entityManager->flush();
       
        return $this->redirectToRoute('student_get_all');
        }
       
        return $this->render('student/editStudent.html.twig', ['form' =>
       $form->createView()]);
        }
        #[Route('student/order/orderedbyemail', name:'student_orderedbyemail')]
        public function getStudentOrderedByEmail(StudentRepository $repo):Response{
                    $students=$repo->getStudentOrderedByEmail();
                    return $this->render('student/listbyemail.html.twig', [
                        'students'=> $students
                    ]);
        }  
        #[Route('student/moyenne/notAdmitted', name:'student_notAdmitted')]
        public function getStudentsNotAdmitted(StudentRepository $repo){
                    $students=$repo->getStudentsNotAdmitted();
                    return $this->render('student/notAdmittedStudents.html.twig', [
                        'students'=> $students
                    ]);
        }  
           
  }
?>