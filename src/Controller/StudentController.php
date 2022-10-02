<?php
namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  class StudentController extends AbstractController{
    #[Route('/bonjour',name:'index')]
    public function index():response{
        return new response("Bonjour mes étudiants");
    }
  }
?>