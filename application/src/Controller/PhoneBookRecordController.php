<?php

namespace App\Controller;

use App\Entity\PhoneBookRecord;
use App\Form\CreateRecordType;
use App\Form\EditRecordType;
use App\Form\ShareRecordType;
use App\Manager\PhoneBookRecordManager;
use App\Repository\PhoneBookRecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhoneBookRecordController extends AbstractController
{
    /**
     * @Route("/records", name="app_records")
     */
    public function myRecords(Request $request, PhoneBookRecordRepository $phoneBookRecordRepository)
    {
        $user = $this->getUser();

        $phoneBookRecords = $phoneBookRecordRepository->findBy(['creator' => $user->getId()]);

        $newPhoneBookRecord = new PhoneBookRecord();
        $createRecordForm = $this->createForm(CreateRecordType::class, $newPhoneBookRecord);
        $createRecordForm->handleRequest($request);

        if ($createRecordForm->isSubmitted() && $createRecordForm->isValid()) {
            $newPhoneBookRecord->setCreator($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newPhoneBookRecord);
            $entityManager->flush();

            return $this->redirectToRoute('app_records');
        }

        return $this->render('records/records.html.twig', [
            'phoneBookRecords' => $phoneBookRecords,
            'createRecordForm' => $createRecordForm->createView()
        ]);
    }

    /**
     * @Route("/shared", name="app_shared")
     */
    public function sharedRecords()
    {
        $user = $this->getUser();
        $sharedRecords = $user->getSharedRecords()->getValues();

        return $this->render('records/shared.html.twig', [
            'sharedPhoneBookRecords' => $sharedRecords
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_delete")
     */
    public function deleteRecord(PhoneBookRecord $phoneBookRecord): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($phoneBookRecord);
        $entityManager->flush();

        return $this->redirectToRoute('app_index');
    }


    /**
     * @Route("/edit/{id}", name="app_edit")
     */
    public function editRecord(Request $request, PhoneBookRecord $phoneBookRecord)
    {
        $form = $this->createForm(EditRecordType::class, $phoneBookRecord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_records');
        }

        return $this->render('records/edit.html.twig', [
            'editForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/share/{id}", name="app_share")
     */
    public function shareRecord(Request $request, PhoneBookRecord $phoneBookRecord, PhoneBookRecordManager $manager)
    {
        $form = $this->createForm(ShareRecordType::class, $phoneBookRecord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sharedUserIds = $request->request->get('share_record')['sharedUsers'];
            $manager->addSharedUsersToRecord($phoneBookRecord, $sharedUserIds);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_share', [
                'id' => $phoneBookRecord->getId()
            ]);
        }

        return $this->render('records/share.html.twig', [
            'sharedRecord' => $phoneBookRecord,
            'sharedUsers' => $phoneBookRecord->getSharedUsers(),
            'sharedUserIds' => $manager->getSharedUserIds($phoneBookRecord),
            'shareForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/unshare/{id}/user/{userId}", name="app_unshare")
     */
    public function unshareRecord(Request $request, PhoneBookRecord $phoneBookRecord, int $userId, PhoneBookRecordManager $manager): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $manager->unshareUser($phoneBookRecord, $userId);
        $entityManager->flush();

        return $this->redirectToRoute('app_share', [
            'id' => $phoneBookRecord->getId()
        ]);
    }
}
