<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AuthorType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Author;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class AuthorController extends AbstractController
{
    #[Route('/show_author', name: 'app_show')]
    public function show(AuthorRepository $repository): Response
    {
        $authors = $repository->findAll();

        return $this->render('show.html.twig', [
            'authors' => $authors
        ]);
    }
    #[Route('/Add', name: 'app_Add')]
    public function add(Request $request)
    {
        $authors = new Author();
        $form = $this->createForm(AuthorType::class, $authors);
        $form->add('Add', SubmitType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($authors);
            $em->flush();
            return $this->redirectToRoute('app_show');
        }
    
        return $this->render('Add.html.twig', ['f' => $form->createView()]);
    }
    #[Route('/edit/{id}', name: 'app_edit')]
    public function edit($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
    
        $authors = $entityManager->getRepository(Author::class)->find($id);
        
    
        if (!$authors) {
            throw $this->createNotFoundException('No author found for id '.$id);
        }
    
        $form = $this->createFormBuilder($authors)
            ->add('username')
            ->add('email')
            ->getForm();
        $form->add('edit', SubmitType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_show');
        }

        return $this->render('edit.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    #[Route('/delete/{id}', name: 'app_delete')]
    public function delete($id, AuthorRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $authors = $repository->find($id);

        if (!$authors) {
            throw $this->createNotFoundException('author not found');
        }

        $entityManager->remove($authors);
        $entityManager->flush();

        return $this->redirectToRoute('app_show');
    }
    
    }

