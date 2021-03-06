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
     * GOING ON THE RSE MAIN PAGE
     * @Route("/rse", name="rse")
     */
    public function index(): Response
    {
        return $this->render('rse/homeRSE.html.twig', [
            'controller_name' => 'RSEController',
        ]);
    }


    /**
     * SHOW THE RSE CALENDAR FORM AND PUSH THE EMAIL
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
                    ->subject($firstname . ' voici votre calendrier des ??v??nements du d??veloppement durable')
                    ->htmlTemplate('textEmail/calendarMail.html.twig')
                    ->attachFromPath('calendrier_RSE_V3.pdf', 'Calendrier RSE')
                    ->context([
                        'firstname' => $firstname
                    ]);
                $mailer->send($email);
                $this->addFlash('success', 'Notre calendrier vous a ??t?? envoy?? par mail ?? l\'instant');
                return $this->redirectToRoute('rse', [

                ]);
            }catch (\Exception $exception){
                $this->addFlash('error', 'Il y a eu un probl??me pendant le traitement, merci de r??essayer plus tard');
            }
        }
        return $this->render('rse/calendar.html.twig', [
            'calendarForm' => $calendarForm->createView()
        ]);
    }
}
