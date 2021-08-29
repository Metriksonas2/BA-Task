<?php

namespace App\Controller;

use App\Entity\PhoneBookRecord;
use App\Form\CreateRecordType;
use App\Form\ShareRecordType;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index()
    {
        if($this->getUser() !== null){
            return $this->redirectToRoute('app_records');
        }

        return $this->render('home/main.html.twig');
    }
}
