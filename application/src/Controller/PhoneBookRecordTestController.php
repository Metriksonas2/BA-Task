<?php

namespace App\Controller;

use App\Entity\PhoneBookRecord;
use App\Form\PhoneBookRecordType;
use App\Repository\PhoneBookRecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/phone/book/record/test')]
class PhoneBookRecordTestController extends AbstractController
{
    #[Route('/', name: 'phone_book_record_test_index', methods: ['GET'])]
    public function index(PhoneBookRecordRepository $phoneBookRecordRepository): Response
    {
        return $this->render('phone_book_record_test/index.html.twig', [
            'phone_book_records' => $phoneBookRecordRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'phone_book_record_test_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $phoneBookRecord = new PhoneBookRecord();
        $form = $this->createForm(PhoneBookRecordType::class, $phoneBookRecord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($phoneBookRecord);
            $entityManager->flush();

            return $this->redirectToRoute('phone_book_record_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('phone_book_record_test/new.html.twig', [
            'phone_book_record' => $phoneBookRecord,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'phone_book_record_test_show', methods: ['GET'])]
    public function show(PhoneBookRecord $phoneBookRecord): Response
    {
        return $this->render('phone_book_record_test/show.html.twig', [
            'phone_book_record' => $phoneBookRecord,
        ]);
    }

    #[Route('/{id}/edit', name: 'phone_book_record_test_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PhoneBookRecord $phoneBookRecord): Response
    {
        $form = $this->createForm(PhoneBookRecordType::class, $phoneBookRecord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('phone_book_record_test_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('phone_book_record_test/edit.html.twig', [
            'phone_book_record' => $phoneBookRecord,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'phone_book_record_test_delete', methods: ['POST'])]
    public function delete(Request $request, PhoneBookRecord $phoneBookRecord): Response
    {
        if ($this->isCsrfTokenValid('delete'.$phoneBookRecord->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($phoneBookRecord);
            $entityManager->flush();
        }

        return $this->redirectToRoute('phone_book_record_test_index', [], Response::HTTP_SEE_OTHER);
    }
}
