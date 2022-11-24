<?php

namespace App\Controller;

use App\Entity\About;
use App\Entity\Blog;
use App\Entity\Category;
use App\Entity\Faq;
use App\Entity\Lecture;
use App\Entity\Person;
use App\Entity\PersonGift;
use App\Entity\Tag;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;

class HomeController extends AbstractController
{
   
    private const DEFAULT_DAY = [
        'enabled' => false,

    ];

    private function ceskyMesic($mesic): string
    {
        static $nazvy = array(1 => 'leden', 'únor', 'březen', 'duben', 'květen', 'červen', 'červenec', 'srpen', 'září', 'říjen', 'listopad', 'prosinec');
        return $nazvy[$mesic];
    }
    
    private function ceskyDen($den): string
    {
        static $nazvy = array(1 => 'pondělí', 'úterý', 'středa', 'čtvrtek', 'pátek', 'sobota','neděle');
        return $nazvy[$den];
    }

    /**
     * @Route("/", name="home", methods={"GET"})
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function actionIndex(Request $request, PaginatorInterface $paginator, EntityManagerInterface $entityManager): Response
    {   
        $personGifts = $entityManager->getRepository(PersonGift::class)->findAll();

        $person = $entityManager->getRepository(Person::class)->findBy([
            'active' => true
        ]);

        if (count($person) < 24) {
            $giftCount = 24;
        } else {
            $giftCount = count($person);
        }

        $gifts = [];
        for ($i = 1; $i <= $giftCount; $i++) {
            $gifts[$i] = self::DEFAULT_DAY;
        }

        /** @var PersonGift $personGift */
        foreach ($personGifts as $personGift) {
            $gifts[$personGift->getDay()] = [
                'enabled' => true,
                'person' => $personGift->getPerson()->getFirstName() .' '. $personGift->getPerson()->getLastName(),
                'giftImage' => $personGift->getGift()->getImagePath(),
                'giftName' => $personGift->getGift()->getGiftName(),
            ];
        }

        return $this->render('home/index.html.twig', [
            'gifts' => $gifts,
        ]);
    }

    /**
    * @Route("/about", name="home_about", methods={"GET"})
    */
    public function actionAbout(EntityManagerInterface $em): Response
    {
        $about = $em->getRepository(About::class)->findOneBy(['version' => 1]);
        return $this->render('home/about.html.twig', [
            'about' => $about,
        ]);
    }

    /**
     * @Route("/blog", name="home_blog")
     */
    public function actionBlog(Request $request, PaginatorInterface $paginator, EntityManagerInterface $em): Response
    {
        $blogsAll = $em->getRepository(Blog::class)->findBy(['published' => true], ['publishedAt' => 'DESC']);

        $blogs = $paginator->paginate($blogsAll, $request->query->getInt('page', 1), 8, ['align' => 'center']);

        return $this->render('home/blog.html.twig', [
            'blogs' => $blogs,
        ]);
    }

    /**
     * @Route("/blog/{id}", name="home_blog_detail")
     */
    public function actionBlogDetail(Blog $blog, Request $request, EntityManagerInterface $em): Response
    {

        return $this->render('home/blogDetail.html.twig', [
            'blog' => $blog,
        ]);
    }

    /**
     * @Route("/faq", name="home_faq")
     */
    public function actionFaq(EntityManagerInterface $em): Response
    {
        $faqs = $em->getRepository(Faq::class)->findBy([], ['position' => 'ASC']);
        return $this->render('home/faq.html.twig', [
            'faqs' => $faqs,
        ]);
    }

    /**
     * @Route("lecture/detail/{id}", name="lecture-detail")
     * @param EntityManagerInterface $em
     * @param Lecture $lecture
     * @return Response
     */
    public function actionDetail(EntityManagerInterface $em, Lecture $lecture):Response
    {
        $lecture = $em->getRepository(Lecture::class)->findOneBy(['id' => $lecture->getId()]);

        return $this->render('home/lectureDetail.html.twig', [
            'lecture' => $lecture,
        ]);
    }
}
