<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class UserController
 * @Route(path="user", name="user.")
 */
class UserController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/activate/{token}", methods={"GET"}, name="activate")
     * @param string $token
     * @param UserRepository $userRepository
     * @return Response
     */
    public function activate(string $token, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneByToken($token);
        if ($user !== null){
            $user->setActive(true);
            $this->manager->persist($user);
            $this->manager->flush();
            return $this->render('user/activated.html.twig');
        }

        return $this->render('user/invalid.html.twig');
    }
}
