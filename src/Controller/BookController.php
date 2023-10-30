<?php

namespace App\Controller;
use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use PHPUnit\Framework\Constraint\Count;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/ShowBook', name: 'app_showBook')]
    public function Affiche(BookRepository $repository)
    {
        //récupérer les livres publiés
        $publishedBooks = $this->getDoctrine()->getRepository(Book::class)->findBy(['published' => true]);
        //compter le nombre de livres pubbliés et non publiés
        $numPublishedBooks = count($publishedBooks);
        $numUnPublishedBooks = count($this->getDoctrine()->getRepository(Book::class)->findBy(['published' => false]));

        if ($numPublishedBooks > 0) {
            return $this->render('book/show.html.twig', ['publishedBooks' => $publishedBooks, 'numPublishedBooks' => $numPublishedBooks, 'numUnPublishedBooks' => $numUnPublishedBooks]);

        } else {
            //afficher un message si aucun livre n'a été trouvé$
            return $this->render('book/no_books_found.html.twig');
        }

    }
    #[Route('/AddBook', name: 'app_AddBook')]
    public function Add(Request $request)
    {
        $book = new Book();
        $form = $this->CreateForm(BookType::class, $book);
        $form->add('Add', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //initialisation de l'attribut "published" a true
            //  $book->setPublished(true);
// get the accociated author from the book entity
            $authors = $book->getAuthor();
            //incrementation de l'attribut "nb_books" de l'entire Author

            if ($authors instanceof Author) {
                $authors->setNbBooks($authors->getNbBooks() + 1);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('app_showBook');
        }
        return $this->render('book/Add.html.twig', ['f' => $form->createView()]);

    }
    
    #[Route('/editbook/{ref}', name: 'app_editBook')]
    public function edit(BookRepository $repository, $ref, Request $request)
    {
        $book = $repository->find($ref);
        $form = $this->createForm(BookType::class, $book);
        $form->add('Edit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush(); // Correction : Utilisez la méthode flush() sur l'EntityManager pour enregistrer les modifications en base de données.
            return $this->redirectToRoute("app_showBook");
        }

        return $this->render('book/edit.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    #[Route('/deletebook/{ref}', name: 'app_deleteBook')]
    public function delete($ref, BookRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $book= $repository->find($ref);

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('app_showBook');
    }
        public function showBook($ref, BookRepository $repository)
    {
        $book = $repository->find($ref);
        if (!$book) {
            return $this->redirectToRoute('app_showBook');
        }

        return $this->render('book/show.html.twig', ['b' => $book]);

}
#[Route('/detailBook/{ref}', name: 'app_detailBook')]

public function detailBook($ref, BookRepository $repository)
{
    $book = $repository->find($ref);
    if (!$book) {
        return $this->redirectToRoute('app_AfficheBook');
    }

    return $this->render('book/detail.html.twig', ['b' => $book]);

}
}
