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
use App\Form\PersonFormType;
use App\Service\UploaderHelper;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
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

    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function actionIndex(EntityManagerInterface $entityManager): Response
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
     * @Route("/gift-register", name="gift-register")
     * @param Request $request
     * @param UploaderHelper $uploaderHelper
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \Exception
     */
    public function registerGift(Request $request, UploaderHelper $uploaderHelper, EntityManagerInterface $entityManager): Response
    {
        $person = new Person();

        $form = $this->createForm(PersonFormType::class, $person, ['isAdmin' => false]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var File $uploadedFile */
            $uploadedFile = $form['file']->getData();
            if ($uploadedFile) {
                $newFilename = $uploaderHelper->uploadImage($uploadedFile, UploaderHelper::GIFT_IMAGE, $person->getImage());
                $person->setImage($newFilename);
            }

            $entityManager->persist($person);
            $entityManager->flush();

            return $this->redirectToRoute('gift-summary', ['person-id' => $person->getId()]);
        }

        return $this->render('home/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/gift-summary", name="gift-summary")
     * @param Request $request
     * @return Response
     */
    public function giftSummary(Request $request, EntityManagerInterface $entityManager): Response
    {
        $personId = $request->get('person-id');

        if ($personId === null) {
            return $this->redirectToRoute('home');
        }

        $person = $entityManager->getRepository(Person::class)->find((int) $personId);

        if ($person === null) {
            return $this->redirectToRoute('home');
        }

        return $this->render('home/summary.html.twig', [
            'person' => $person,
        ]);
    }
}
