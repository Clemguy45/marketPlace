<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Form\ArticlesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ArticleController extends AbstractController
{
    /**
     *  
     * This controller display all Articles
     *
     * @param ArticleRepository $articleRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/article', name: 'app_article')]
    #[IsGranted('ROLE_USER')]
    public function index(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $articles = $paginator->paginate(
            $articleRepository->findBy(['user'=> $this->getUser()]),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('pages/article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * this controller displays a single Article
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/article/nouveau', 'article.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request , EntityManagerInterface $manager) : Response{
        $article = new Article();
        $form = $this->createForm(ArticlesType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $article->setUser($this->getUser());
            $manager->persist($article);
            $manager->flush();
            $this->addFlash('success', 'Votre article a bien été enregistrée');
            return $this->redirectToRoute('app_article');
        }

       return $this->render('pages/article/new.html.twig', [ 'form' => $form->createView()]);
    }

    /**
     * This controller displays a single Article
     *
     * @param Article $article
     * @param Request $request
     */
    #[Route('/article/edition/{id}', name: 'app_article_show', methods: ['GET', 'POST'])]
    #[Security('is_granted("ROLE_USER") and user === article.getUser()')]
    public function edit(Request $request , EntityManagerInterface $entityManagerInterface, Article $article) : Response {
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Votre article a bien été mise à jour');
            return $this->redirectToRoute('app_article');
        }
        return $this->render('pages/article/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManagerInterface
     * @param Article $article
     * @return Response
     */
    #[Route('/article/suppression/{id}', name: 'app_article_delete', methods: ['GET'])] 
    public function delete(EntityManagerInterface $entityManagerInterface, Article $article) : Response{
        if (!$article){
            $this->addFlash('warning', 'Vous ne pouvez pas supprimer cette article');
            return $this->redirectToRoute('app_article');
        }
        $entityManagerInterface->remove($article);
        $entityManagerInterface->flush();
        $this->addFlash('success', 'Votre article a bien été supprimé');
        return $this->redirectToRoute('app_article');
    }
}
