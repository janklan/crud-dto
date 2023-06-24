<?php

namespace App\Controller;

use App\Crud\PersonCreateDto;
use App\Crud\PersonDto;
use App\Crud\PersonUpdateDto;
use App\Entity\Person;
use App\Form\PersonCreateType;
use App\Form\PersonUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonCreateType::class, new PersonCreateDto(), [
            'action' => $request->getUri(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();
            assert($dto instanceof PersonDto);
            $entityManager->persist($dto->apply());
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('default/index.html.twig', [
            'form' => $form,
            'people' => $entityManager->getRepository(Person::class)
                ->createQueryBuilder('person')
                ->select('person', 'address')
                ->leftJoin('person.address', 'address')
                ->orderBy('person.name', 'asc')
                ->getQuery()
                ->getResult(),
        ]);
    }

    #[Route('/update/{person}', name: 'update')]
    public function update(Request $request, Person $person, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonUpdateType::class, new PersonUpdateDto($person), [
            'action' => $request->getUri(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();
            assert($dto instanceof PersonDto);
            $entityManager->persist($dto->apply());
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('default/update.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{person}', name: 'delete')]
    public function delete(Person $person, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($person);
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }
}
