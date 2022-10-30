<?php

namespace App\Controller;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
    #[Route('product/all', name:'product_get_all')]
    public function getproducts(ProductRepository $repo):Response{
        $products=$repo->findAll();
        return $this->render('product/products.html.twig', [
            'products'=> $products
        ]);
    }
    #[Route('addProduct/', name:'product_add')]
    public function new(Request $request,ManagerRegistry $doctrine): Response
    {
       
           $product = new Product();
           $form = $this->createForm(ProductType::class,$product);
           $form->handleRequest($request);
           if($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $entityManager = $doctrine->getManager();
            $repo=$doctrine->getRepository(Product::class);
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('product_get_all');
           } 
           return $this->renderForm('product/addProduct.html.twig',['form' => $form]);
    }
    #[Route('removeProduct/{id}', name:'product_remove')]
    public function removeProduct(ManagerRegistry $doctrine,$id):Response{

        $em=$doctrine->getManager();
        $repo=$doctrine->getRepository(Product::class);
        $product=$repo->find($id);
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('product_get_all');
    
    }
}
