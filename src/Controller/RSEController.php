<?php

namespace App\Controller;

use App\Form\CalendarType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class RSEController extends AbstractController
{
    /**
     * @Route("/rse", name="rse")
     */
    public function index(): Response
    {
        return $this->render('rse/homeRSE.html.twig', [
            'controller_name' => 'RSEController',
        ]);
    }


    /**
     * @Route("/calendar", name="calendar_rse")
     */
    public function calendarRSE(Request $request, MailerInterface $mailer){
        $calendarForm = $this->createForm(CalendarType::class);
        $calendarForm->handleRequest($request);
        if($calendarForm->isSubmitted() && $calendarForm->isValid()){
            try {
                #get the form data
                $lastname = $calendarForm->getData()['lastname'];
                $firstname = $calendarForm->getData()['firstname'];
                $mailAddress = $calendarForm->getData()['email'];
                $phone = $calendarForm->getData()['telephone'];
                $company = $calendarForm->getData()['company'];
                $stringToWrite = "\n" . $firstname . " " . $lastname . " - " . $mailAddress . " - " . $phone . " - " . $company;
                #write them in the .txt file
                if(file_exists('calendarData.txt')){
                    $content = file_get_contents('calendarData.txt');
                    $myFile = fopen('calendarData.txt', 'c+b');
                    fread($myFile, strlen($content));
                    fwrite($myFile, $stringToWrite);
                    fclose($myFile);
                }else{
                    file_put_contents('calendarData.txt', $stringToWrite);
                }
                #send the email with file to the user
                $email = (new TemplatedEmail())
                    ->from('team@sowrs.com')
                    ->to($mailAddress)
                    ->subject($firstname . ' voici votre calendrier des évènements du développement durable')
                    ->htmlTemplate('textEmail/calendarMail.html.twig')
                    ->attachFromPath('calendrier_RSE_V3.pdf', 'Calendrier RSE')
                    ->context([
                        'firstname' => $firstname
                    ]);
                $mailer->send($email);
                $this->addFlash('success', 'Notre calendrier vous a été envoyé par mail à l\'instant');
                return $this->redirectToRoute('rse', [

                ]);
            }catch (\Exception $exception){
                $this->addFlash('error', 'Il y a eu un problème pendant le traitement, merci de réessayer plus tard');
            }
        }
        return $this->render('rse/calendar.html.twig', [
            'calendarForm' => $calendarForm->createView()
        ]);
    }
}
