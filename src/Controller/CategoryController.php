<?php

namespace App\Controller;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
    #[Route('category/all', name:'category_get_all')]
    public function getcategories(categoryRepository $repo):Response{
        $categories=$repo->findAll();
        return $this->render('category/categories.html.twig', [
            'categories'=> $categories
        ]);
    }
    #[Route('addCategory/', name:'category_add')]
    public function new(Request $request,ManagerRegistry $doctrine): Response
    {
       
           $category = new Category();
           $form = $this->createForm(CategoryType::class,$category);
           $form->handleRequest($request);
           if($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $entityManager = $doctrine->getManager();
            $repo=$doctrine->getRepository(Category::class);
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category_get_all');
           } 
           return $this->renderForm('category/addCategory.html.twig',['form' => $form]);
    }
    #[Route('removeCategory/{id}', name:'category_remove')]
    public function removecategory(ManagerRegistry $doctrine,$id):Response{

        $em=$doctrine->getManager();
        $repo=$doctrine->getRepository(Category::class);
        $category=$repo->find($id);
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('category_get_all');
    
    }
}
