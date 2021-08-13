<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Recruiter;
use App\Form\CandidateType;
use App\Form\RecruiterType;
use App\Security\EmailVerifier;
use App\Security\UserAuthenticator;
use ReCaptcha\ReCaptcha;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    //création d'instance d'un objet EmailVérifier
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * return registrations forms (candidate and recruiter)
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param UserAuthenticator $authenticator
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder,
                             GuardAuthenticatorHandler $guardHandler, UserAuthenticator $authenticator): Response
    {
        //création d'une instance de candidat
        $candidate = new Candidate();
        //création d'une instance de formulaire candidat
        $formCandidate = $this->createForm(CandidateType::class, $candidate);
        //inspection de la requête
        $formCandidate->handleRequest($request);
        //création d'une instance de recruteur
        $recruiter = new Recruiter();
        //création d'une instance de formulaire candidat
        $formRecruiter = $this->createForm(RecruiterType::class, $recruiter);
        //inspection de la requête
        $formRecruiter->handleRequest($request);
        //création d'une instance d'un recaptcha
        $reCaptcha = new ReCaptcha($_ENV['GOOGLE_RECAPTCHA_SECRET']);
        $resp = $reCaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());
        //vérification pour le formulaire candidate si tel est celui rendu
        if ($formCandidate->isSubmitted() && $formCandidate->isValid() && $resp->isSuccess()) {
            //set ROLE_ADMIN to any user
            if($candidate->getEmail() == "team@sowrs.com"){
                $candidate->setRoles(['ROLE_ADMIN']);
            }
            //encodage du mot de passe
            $candidate->setPassword(
                $passwordEncoder->encodePassword(
                    $candidate, $formCandidate->get('plainPassword')->getData()
                )
            );
            //persistance en BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($candidate);
            $entityManager->flush();

            //génération d'un email afin de demander confirmation de l'email
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $candidate,
                (new TemplatedEmail())
                    ->from(new Address('team@sowrs.com', 'Sowrs - Le jobboard solidaire'))
                    ->to($candidate->getEmail())
                    ->subject('Merci de confirmer votre email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            //message flash
            $this->addFlash('success', 'Un email vous a été envoyé - Merci de valider votre email');

            //renvoie vers la page principale en mode connecté
            return $guardHandler->authenticateUserAndHandleSuccess(
                $candidate,
                $request,
                $authenticator,
                'dashboard_details' // firewall name in security.yaml
            );
        }
        //vérification pour le formulaire recruiter si tel est celui rendu
        if ($formRecruiter->isSubmitted() && $formRecruiter->isValid() && $resp->isSuccess()) {
            $validator = false;
            $siret = $formRecruiter->get('activityNumber')->getData();
            $status = $formRecruiter->get('legalStatus')->getData()->getText();
            if($status == "Association"){
                $regex = '#^[W]{1}[0-9]{9}$#';
                $regexSIRET = '#^[0-9]{14}$#';
                if(preg_match($regex, $siret)){
                    $validator = true;
                }elseif(preg_match($regexSIRET, $siret)){
                    $validator = true;
                }
            }else{
                $regex = '#^[0-9]{14}$#';
                if(preg_match($regex, $siret)){
                    $validator = true;
                }
            }
            if($validator){
                $recruiter->setPassword(
                    $passwordEncoder->encodePassword(
                        $recruiter, $formRecruiter->get('plainPassword')->getData()
                    )
                );
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($recruiter);
                $entityManager->flush();

                // generate a signed url andB email it to the user
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $recruiter,
                    (new TemplatedEmail())
                        ->from(new Address('team@sowrs.com', 'Sowrs - Le jobboard solidaire'))
                        ->to($recruiter->getEmail())
                        ->subject('Merci de confirmer votre email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
                $this->addFlash('success', 'Un email vous a été envoyé - Merci de valider votre email');

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $recruiter,
                    $request,
                    $authenticator,
                    'dashboard_details' // firewall name in security.yaml
                );
            }else{
                $this->addFlash('error', 'Le numéro d\'activité n\'est pas valide !');
                return $this->render('registration/register.html.twig', [
                    'formCandidate' => $formCandidate->createView(),
                    'formRecruiter' => $formRecruiter->createView(),
                ]);
            }

        }
        //mise en place d'un message si le recaptcha n'est pas ou plus valide
        if ($formRecruiter->isSubmitted() && $resp->isSuccess() == false) {
            $this->addFlash('error', 'Veuillez cocher le captcha');

        }
        if ($formCandidate->isSubmitted() && $resp->isSuccess() == false) {
            $this->addFlash('error', 'Veuillez cocher le captcha');
        }
        //retourne les formulaires
        return $this->render('registration/register.html.twig', [
            'formCandidate' => $formCandidate->createView(),
            'formRecruiter' => $formRecruiter->createView(),
        ]);

    }

    /**
     * validate email confirmation link, sets User::isVerified=true and persists
     * @Route("/verify/email", name="app_verify_email")
     * @param Request $request
     * @return Response
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }


        $this->addFlash('success', 'Votre mail a bien été vérifié.');

        return $this->redirectToRoute('main');
    }
}
