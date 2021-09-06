<?php

namespace App\Controller;

use App\Entity\PhoneBookRecord;
use App\Form\CreateRecordType;
use App\Form\ShareRecordType;
use App\Manager\PhoneBookRecordManager;
use App\Manager\UserManager;
use App\Repository\PhoneBookRecordRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PhoneBookRecordController extends AbstractController
{
    /**
     * @Route("/records", name="app_records", methods={"GET", "POST"})
     */
    public function myRecords(Request $request, PhoneBookRecordRepository $phoneBookRecordRepository, PhoneBookRecordManager $manager)
    {
        $user = $this->getUser();

        $phoneBookRecords = $phoneBookRecordRepository->getCreatedPhoneBookRecords($user->getId());

        $newPhoneBookRecord = new PhoneBookRecord();

        $createRecordForm = $this->createForm(CreateRecordType::class, $newPhoneBookRecord);
        $createRecordForm->handleRequest($request);

        if ($createRecordForm->isSubmitted() && $createRecordForm->isValid()) {
            $manager->createPhoneBookRecord($newPhoneBookRecord, $user);

            return $this->redirectToRoute('app_records');
        }

        return $this->render('records/records.html.twig', [
            'phoneBookRecords' => $phoneBookRecords,
            'createRecordForm' => $createRecordForm->createView()
        ]);
    }

    /**
     * @Route("/shared", name="app_shared", methods={"GET"})
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
     * @Route("/delete/{id}", name="app_delete", methods={"DELETE"})
     */
    public function deleteRecord(PhoneBookRecord $phoneBookRecord, PhoneBookRecordManager $manager): Response
    {
//        $manager->deleteRecord($phoneBookRecord);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($phoneBookRecord);
        $entityManager->flush();

        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("/edit/record/{id}", name="app_edit", methods={"POST"})
     */
    public function editAjaxRecord(Request $request, PhoneBookRecord $phoneBookRecord, PhoneBookRecordManager $manager): Response
    {
        try {
            $manager->editPhoneBookRecord($request, $phoneBookRecord);
        } catch (InvalidParameterException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->redirectToRoute('app_index');
    }

    /**
     * @Route("/record/{id}", name="app_get_record_data", methods={"GET"})
     */
    public function getRecordData(PhoneBookRecord $phoneBookRecord)
    {
        return new JsonResponse([
            'firstName' => $phoneBookRecord->getFirstName(),
            'lastName' => $phoneBookRecord->getLastName(),
            'email' => $phoneBookRecord->getEmail(),
            'phoneNumber' => $phoneBookRecord->getPhoneNumber(),
        ]);
    }

    /**
     * @Route("/share/{id}", name="app_share", methods={"GET", "POST"})
     */
    public function shareRecord(Request $request, PhoneBookRecord $phoneBookRecord,
                                UserManager $manager, UserService $userService)
    {
        $form = $this->createForm(ShareRecordType::class, $phoneBookRecord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sharedUserIds = $request->request->get('share_record')['sharedUsers'];
            $manager->addSharedUsersToRecord($phoneBookRecord, $sharedUserIds);

            return $this->redirectToRoute('app_share', [
                'id' => $phoneBookRecord->getId()
            ]);
        }

        return $this->render('records/share.html.twig', [
            'sharedRecord' => $phoneBookRecord,
            'sharedUsers' => $phoneBookRecord->getSharedUsers(),
            'sharedUserIds' => $userService->getSharedUserIds($phoneBookRecord),
            'shareForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/unshare/{id}/user/{userId}", name="app_unshare")
     */
    public function unshareRecord(Request $request, PhoneBookRecord $phoneBookRecord, int $userId, UserManager $manager): Response
    {
        $manager->unshareUser($phoneBookRecord, $userId);

        return $this->redirectToRoute('app_share', [
            'id' => $phoneBookRecord->getId()
        ]);
    }
}
