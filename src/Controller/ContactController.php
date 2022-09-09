<?php

namespace App\Controller;

use App\Services\Tools;
use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use App\Repository\SwapRepository;
use App\Repository\GamesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request, MailerInterface $mailer,Tools $tools,GamesRepository $gamesRepository, SwapRepository $swapRepository): Response
    {

        $user = $tools->getUser();

        if ($user != null) {
            $idswap = htmlspecialchars($_POST['seller']);
            $swap = $swapRepository->find($idswap);            
            $idgame = $swap->getIdgameuser()->getId();
            $game = $gamesRepository->find($idgame);
            $etatgame = $swap->getidshape()->getetat();

             
            // dd($idswap, $swap, $etatgame);

            $form = $this->createForm(ContactType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $dataform = $form->getdata();
                // dd($dataform);
                // $email = $dataform['email'];
                $email = 'jmb-dw26@outlook.fr';
                // $content = $dataform['content'];
                $content = 'Super Cool';

                $email = (new Email())
                    ->from($email)
                    ->to($email)
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('A propos de votre jeu...')
                    ->text('Bonjour, SLorem ipsum dolor sit amet consectetur adipisicing elit. Sint est enim qui quisquam in minus vel nam perspiciatis incidunt eum quae,');
                // ->html('<p>See Twig integration for better HTML integration!</p>');

                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    dump('---> Error Mail not Sent...');
                }

                dd($mailer);
            }

            return $this->renderform('contact/index.html.twig', [
                'contactform' => $form,
                'etatgame' => $etatgame,               
                'game' => $game,
            ]);

        } else {
            // User non Connecté...
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }
    }
}
