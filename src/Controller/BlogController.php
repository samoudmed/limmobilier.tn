<?php 
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Article;

class BlogController extends AbstractController
{
    /**
     * @Route("/articles-{page}.html", name="blog")
     */
    public function blog(Request $request, PaginatorInterface $paginator, EntityManagerInterface $em, $page = 1)
    {
        $query = $em->getRepository(Article::class)
            ->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        $articles = $paginator->paginate(
            $query, // Query, not array
            $page,
            20
        );

        return $this->render('blog/blog.html.twig', [
            'articles' => $articles
        ]);
    }
    
    /**
     * @Route("/recent-articles.html", name="recentArticles")
     */
    public function recentArticles($max = 3)
    {
         $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findBy(array(), array('id' => 'DESC'), $max);
         
        return $this->render('blog/__recents-articles.html.twig', ['articles' => $articles]);
    }
    
    /**
     * @Route("/article/{id}/{slug}.html", name="blog-details", requirements={"slug"="[a-zA-Z0-9-_/]+", "id": "\d+"})
     */
    public function blogDetails(Request $request, $id)
    {
        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneById($id);


        return $this->render('blog/blog-details.html.twig', ['article' => $article]);
    }

}