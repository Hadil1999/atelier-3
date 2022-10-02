<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeacherController extends AbstractController
{
    #[Route('/teacher', name: 'app_teacher')]
    public function index(): Response
    {
        return $this->render('teacher/index.html.twig', [
            'controller_name' => 'TeacherController',
        ]);
    }
    #[Route('/teacher/{name}/{lastname}', name: 'app_teacher_show')]
    public function showTeacher($name,$lastname):response{
        return new response("Bonjour ".$name." ".$lastname);
    }
    #[Route('/teacher/{name}', name: 'app_teacher_show2')]
    public function showTeacher2($name):response{
        return $this->render('teacher/showTeacher.html.twig',['n'=>$name]);
    }

    
}
