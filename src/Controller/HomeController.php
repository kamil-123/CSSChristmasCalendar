<?php

namespace App\Controller;

use App\Entity\Person;
use App\Entity\PersonGift;
use App\Form\PersonFormType;
use App\Repository\PersonGiftRepository;
use App\Repository\PersonRepository;
use App\Service\UploaderHelper;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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

    /**
     * @Route("/generate-gift/{token}", name="generate-gift")
     */
    public function generateGift(
        string $token,
        PersonGiftRepository $personGiftRepository,
        PersonRepository $personRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $checkToken = $this->getParameter('check.token');
        if ($checkToken !== $token) {
            return new Response(null , Response::HTTP_OK, []);
        }

        $date = new DateTimeImmutable();
        $month = (int) $date->format('m');
        $day = (int) $date->format('d');

        if ($month === 12) {
            $personGifts = $personGiftRepository->findAll();
            $personGiftByDay = array_filter($personGifts, function (PersonGift $personGift) use ($day) {
                return $personGift->getDay() === $day;
            });
            if ($personGiftByDay === []) {
                $usedPersonIds = [];
                $usedGiftIds = [];
                foreach ($personGifts as $personGift) {
                    $usedPersonIds [] = $personGift->getPerson()->getId();
                    $usedGiftIds [] = $personGift->getGift()->getId();
                }

                $persons = $personRepository->findBy([
                    'active' => true,
                ]);

                $personChristmasDayArray = array_filter($persons, function (Person $person) {
                    return $person->isChristmasDay() === true;
                });

                $isChristmasDayActive = $personChristmasDayArray !== [];

                if ($day === 24 && $isChristmasDayActive === true) {
                    $newPerson = reset($personChristmasDayArray);
                    $newGift = $newPerson;
                } else {
                    $unUsedPersons = array_filter($persons, function (Person $person) use ($usedPersonIds) {
                        return !in_array($person->getId(), $usedPersonIds) && $person->isChristmasDay() === false;
                    });

                    if ($unUsedPersons === []) {
                        return new Response(null , Response::HTTP_OK, []);
                    }

                    shuffle($unUsedPersons);
                    $newPerson = reset($unUsedPersons);
                    $usedGiftIds[] = $newPerson->getId();

                    $unUsedGifts = array_filter($persons, function (Person $person) use($usedGiftIds) {
                        return !in_array($person->getId(), $usedGiftIds) && $person->isChristmasDay() === false;
                    });


                    shuffle($unUsedGifts);
                    $newGift = reset($unUsedGifts);
                }
                $newPersonGift = new PersonGift($day, $newPerson, $newGift);
                $entityManager->persist($newPersonGift);
                $entityManager->flush();
            }
        }

        return new Response(null , Response::HTTP_OK, []);
    }
}
