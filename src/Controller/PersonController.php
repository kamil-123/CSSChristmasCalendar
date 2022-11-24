<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonFormType;
use App\Repository\PersonRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/person", name="admin.person.")
 */
class PersonController extends AbstractController
{

    /**
     * @Route("/", name="index")
     * @param PersonRepository $personRepository
     * @return Response
     */
    public function indexAction(PersonRepository $personRepository): Response
    {
        $persons = $personRepository->findAll();

        return $this->render('admin/person/index.html.twig', [
            'persons' => $persons
        ]);
    }

    /**
     * @Route("/create", name="create")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request, UploaderHelper $uploaderHelper, EntityManagerInterface $entityManager): Response
    {
        $person = new Person();
        $form = $this->createForm(PersonFormType::class, $person);

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
            return $this->redirectToRoute('admin.person.index');
        }

        return $this->render('admin/person/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @param Request $request
     * @return Response
     */
    public function editAction(Person $person, Request $request, UploaderHelper $uploaderHelper, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonFormType::class, $person);

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
            return $this->redirectToRoute('admin.person.index');
        }

        return $this->render('admin/person/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}/{token}", name="delete")
     * @param EntityManagerInterface $em
     * @param Person $person
     * @param string $token
     * @return RedirectResponse|Response
     */
    public function delete(EntityManagerInterface $em, Person $person, string $token, UploaderHelper $uploaderHelper)
    {
        if (!$this->isCsrfTokenValid('person-delete', $token)) {
            return $this->render('admin/error.html.twig', [
                'error' => 'CSRF token není validní. Zkuste znovu načíst celou stránku.'
            ]);
        }

        $uploaderHelper->deleteImage($person->getImage(), UploaderHelper::GIFT_IMAGE);

        $em->remove($person);
        $em->flush();

        return $this->redirectToRoute('admin.person.index');
    }
}