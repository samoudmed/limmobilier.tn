<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Annonces;
use App\Entity\Photos;
use App\Entity\User;
use App\Entity\Villes;
use App\Entity\Gouvernorat;
use App\Entity\Delegation;
use App\Entity\Kind;
use App\Form\MessageType;
use App\Form\ContactType;
use App\Form\NewsletterType;
USE App\Form\NewsletterGeoType;
use App\Entity\Newsletter;
use App\Entity\Message;
use App\Entity\Contact;
use App\Entity\NewsletterGeo;
use App\Entity\Favorite;
use App\Repository\VillesRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Finder\Finder;
use Cocur\Slugify\Slugify;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\Search;
use \Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Inspector\Inspector;

class DefaultController extends AbstractController {

    /**
     * @Route("/", name="homepage", methods={"GET"})
     */
    public function index(Request $request, Inspector $inspector): Response {

        $annonces = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findActive(24);

        $annoncesList = $this->getFeaturedPhoto($annonces);

        $villes = $this->villes();

        return $this->render('default/index.html.twig', ['annonces' => $annoncesList, 'villes' => $villes])->setSharedMaxAge(3600);
    }

    public function villes() {

        $villes = $this->getDoctrine()
                ->getRepository(Villes::class)
                ->findBy([], ['label' => 'asc']);

        return $villes;
    }

    /**
     * @Route("/liste-villes.html", name="liste_villes", methods={"GET"})
     */
    public function listeVilles(): Response {

        $delegations = $this->getDoctrine()
                ->getRepository(Delegation::class)
                ->findBy([], ['label' => 'asc']);

        $response = $this->render('default/villes.html.twig', ['delegations' => $delegations]);

        // cache publicly for 3600 seconds
        $response->setPublic();
        $response->setMaxAge(3600);

        // (optional) set a custom Cache-Control directive
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    /**
     * @Route("/annonce/{label}-{id}.html", name="annonce_details", requirements={"label"="[0-9a-zA-Z][0-9a-zA-Z\-_]{1,99}", "id"="\d+"})
     */
    public function annonce(Request $request, $id) {


        $annonce = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findOneBy(array('id' => $id));
        $dateNow = new \DateTime();

        if((!$annonce)) {
            
            return $this->redirectToRoute('homepage');
            
        } elseif ((($annonce->isPublished() == 0) || ($annonce->isStatut() == 0) || ($annonce->isDeleted() == 1) || ($annonce->getExpiredAt() < $dateNow))) {

            $annonces = $this->getDoctrine()
                    ->getRepository(Annonces::class)
                    ->searchSimilar($annonce->getOffre(), $annonce->getKind(), $annonce->getVille(), 4, $id);

            $annoncesInCity = $this->getDoctrine()
                    ->getRepository(Annonces::class)
                    ->searchSimilarCity($annonce->getVille(), 4, $id);

            $annonces = $this->getFeaturedPhoto($annonces);
            if(!$annonces){
                $annonces = $this->getDoctrine()
                    ->getRepository(Annonces::class)
                    ->findActive(8);
                $annonces = $this->getFeaturedPhoto($annonces);
            }
            $annoncesInCity = $this->getFeaturedPhoto($annoncesInCity);

            return $this->render('default/details-expired.html.twig', ['annonce' => $annonce, 'annonces' => $annonces, 'annoncesInCity' => $annoncesInCity]);
        } else {

            $em = $this->getDoctrine()->getManager();
            $annonce->setView($annonce->getView() + rand(1, 50));
            $annonce->setRealView($annonce->getRealView() + 1);
            $em->persist($annonce);
            $em->flush();

            $photo = $this->getDoctrine()
                    ->getRepository(Photos::class)
                    ->findOneByAnnonce($annonce);

            if (isset($photo)) {
                $annonce->photo = $photo->getNom();
            }

            $message = new Message();
            $formMessage = $this->createForm(MessageType::class, $message);
            $formMessage->handleRequest($request);

            $return = $this->render('default/details.html.twig', ['annonce' => $annonce, 'formMessage' => $formMessage->createView()]);
            
            $return->setPublic();
            $return->setSharedMaxAge(3600); // Cache de 1 heure pour Varnish
            $return->headers->set('Cache-Control', 'public, s-maxage=3600, max-age=3600');
            $return->headers->remove('Set-Cookie'); // Supprimer les cookies

            return $return;
        }
    }

    public function similar(Request $request, $offre, $type, $ville, $except) {

        $offre = ucfirst($offre);
        $annonces = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->searchSimilar($offre, $type, $ville, 3, $except);

        $annonces = $this->getFeaturedPhoto($annonces);

        return $this->render('default/_similar.html.twig', ['annonces' => $annonces]);
    }

    public function random(Request $request, $offre, $type) {

        $offre = ucfirst($offre);
        $annonces = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->searchRandom($offre, 9, 5);

        $annonces = $this->getFeaturedPhoto($annonces);

        return $this->render('default/_similar.html.twig', ['annonces' => $annonces]);
    }

    /**
     * @Route("/annonces-offre-{offre}-{page}.html", name="annonce_liste", methods={"GET"})
     */
    public function annonceListe(Request $request, PaginatorInterface $paginator, $offre, $page = 1) {


        $allAnnonces = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findByBien(ucfirst($offre));

        $villes = $this->villes();

        $allAnnonces = $this->getFeaturedPhoto($allAnnonces);

        $annonces = $paginator->paginate(
                $allAnnonces, /* query NOT result */
                $page, /* page number */
                24 /* limit per page */
        );

        if ((count($annonces->getItems()) === 0) && ($page > 1)) {
            return $this->redirectToRoute('annonce_liste', ['offre' => $offre, 'page' => 1]);
        }

        return $this->render('default/list.html.twig', ['annoncesList' => $allAnnonces, 'annonces' => $annonces, 'offre' => $offre, 'villes' => $villes, 'page' => $page]);
    }

    /**
     * @Route("/annonces-agence-{label}-{id}-{page}.html", name="liste_agence", requirements={"label"="[0-9a-zA-Z][0-9a-zA-Z\-_]{0,99}"})
     */
    public function listeAgence(Request $request, PaginatorInterface $paginator, $id, $page = 1) {

        $slugify = new Slugify();
        $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($id);

        $allAnnonces = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findByAgence($id);

        $allAnnonces = $this->getFeaturedPhoto($allAnnonces);

        $annonces = $paginator->paginate(
                $allAnnonces, /* query NOT result */
                $page, /* page number */
                24 /* limit per page */
        );

        if ((count($annonces->getItems()) === 0) && ($page > 1)) {
            return $this->redirectToRoute('liste_agence', ['label' => $slugify->slugify($user->getAgence()), 'id' => $user->getId(), 'page' => 1]);
        }

        return $this->render('default/list_agence.html.twig', ['annoncesList' => $allAnnonces, 'annonces' => $annonces, 'user' => $user, 'page' => $page]);
    }
    
    /**
     * @Route("/agences-immobilieres-{page}.html", name="liste-agences")
     */
    public function listeAgences(PaginatorInterface $paginator, $page = 1) {

        $slugify = new Slugify();
        $result = $this->getDoctrine()
                ->getRepository(User::class)
                ->findAgences();

        $agences = $paginator->paginate(
                $result, /* query NOT result */
                $page, /* page number */
                24 /* limit per page */
        );

        return $this->render('default/list_agences.html.twig', ['agences' => $agences]);
    }

    /**
     * @Route("/annonces-type-{offre}-{type}-{page}.html", name="annonce_liste_type", methods={"GET"}, requirements={"type"="[0-9a-zA-Z][0-9a-zA-Z\-_]{1,99}"})
     */
    public function annonceListeType(Request $request, PaginatorInterface $paginator, $offre, $type, $page = 1) {

        $oType = $this->getDoctrine()->getRepository(Kind::class)->findOneByLabel(str_replace('-', ' ', $type));

        $date = Date('Y-m-d');
        $allAnnonces = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findByBienType(ucfirst($offre), $oType);

        $villes = $this->villes();

        $allAnnonces = $this->getFeaturedPhoto($allAnnonces);

        $annonces = $paginator->paginate(
                $allAnnonces, /* query NOT result */
                $request->query->getInt('page', $page), /* page number */
                24 /* limit per page */);

        if ((count($annonces->getItems()) === 0) && ($page > 1)) {
            return $this->redirectToRoute('annonce_liste_type', ['offre' => $offre, 'type' => $type, 'page' => 1]);
        }

        return $this->render('default/list.html.twig', ['annoncesList' => $allAnnonces, 'annonces' => $annonces, 'offre' => $offre, 'type' => $oType, 'page' => $page, 'villes' => $villes]);
    }

    public function getFeaturedPhoto($annonces) {

        foreach ($annonces as $k => $annonce) {

            $photo = $this->getDoctrine()
                    ->getRepository(Photos::class)
                    ->findOneBy(array('annonce' => $annonce, 'featured' => 1));
            if(!$photo) {
                $photo = $this->getDoctrine()
                    ->getRepository(Photos::class)
                    ->findOneBy(array('annonce' => $annonce));
            }
            $annonces[$k]->photo = (isset($photo)) ? $photo->getNom() : 'default-img.png';
        }

        return $annonces;
    }

    public function mailingAnnonceListeType(Request $request, PaginatorInterface $paginator, $offre, $type, $page = 1) {

        $allAnnonces = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findBy(['offre' => ucfirst($offre), 'type' => $type], array('id' => 'DESC'));

        $villes = $this->villes();

        $allAnnonces = $this->getFeaturedPhoto($annoncesList);

        $annonces = $paginator->paginate(
                $allAnnonces, /* query NOT result */
                $request->query->getInt('page', $page), /* page number */
                10 /* limit per page */
        );

        return $this->render('default/mailing.html.twig', ['annoncesList' => $allAnnonces, 'annonces' => $annonces, 'offre' => $offre, 'type' => $type, 'page' => $page, 'villes' => $villes]);
    }

    public function countListing(Request $request, $offre) {

        $annonces = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findByBien($offre);

        return new Response(count($annonces));
    }

    public function listingVille(Request $request) {

        $villes = $this->villes();

        return $this->render('default/_villes.html.twig', ['villes' => $villes]);
    }

    public function listingGouvernorat(Request $request) {

        $gouvernorats = $this->getDoctrine()
                ->getRepository(Gouvernorat::class)
                ->findAll();

        return $this->render('default/_gouvernorat.html.twig', ['gouvernorats' => $gouvernorats]);
    }

    public function listingDelegation(Request $request, $gouvernourat = null) {

        $delegations = $this->getDoctrine()
                ->getRepository(Delegation::class)
                ->findBy([], ['label' => 'asc']);

        return $this->render('default/_delegation.html.twig', ['delegations' => $delegations]);
    }

    /**
     * @Route("/listing-delegation.html", name="listingDelegationByGouvernorat")
     */
    public function listingDelegationByGouvernorat(Request $request) {

        $gouvernourat = $request->request->get('gouvernorat');
        $delegations = $this->getDoctrine()
                ->getRepository(Delegation::class)
                ->findBy(array('gouvernorat' => $gouvernourat));
        foreach ($delegations as $k => $value) {
            $data['delegations'][$k]['id'] = $value->getId();
            $data['delegations'][$k]['label'] = $value->getLabel();
        }

        return $this->json($data);
    }

    /**
     * @Route("/listing-villes.html", name="listingDelegationByDelegation")
     */
    public function listingVilleByDelegation(Request $request) {

        $delegation = $request->request->get('delegation');
        $villes = $this->getDoctrine()
                ->getRepository(Villes::class)
                ->findBy(array('delegation' => $delegation));

        foreach ($villes as $k => $value) {
            $data[$k]['id'] = $value->getId();
            $data[$k]['label'] = $value->getLabel();
        }

        return $this->json($data);
    }

    /**
     * @Route("/contact.html", name="contact")
     */
    public function contact(Request $request, MailerInterface $mailer) {

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $contact = $form->getData();

            $contact->setCreatedAt(new \DateTime('now'));
            $contact->setUpdatedAt(new \DateTime('now'));
            $em->persist($contact);
            $em->flush();

            $email = (new TemplatedEmail())
                    ->from('contact@limmobilier.tn')
                    ->to('samoud.mohamed@gmail.com')
                    ->subject('Contact')
                    // path of the Twig template to render
                    ->htmlTemplate('emails/contact.html.twig')

                    // pass variables (name => value) to the template
                    ->context([
                'nom' => $contact->getNom() . ' ' . $contact->getPrenom(),
                'contact' => $contact,
            ]);

            $mailer->send($email);

            $this->addFlash(
                    'success', 'Votre Annonce a été ajouté avec succès!'
            );
        }

        return $this->render('default/contact.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/mentions-legales.html", name="mentions_legales", methods={"GET"})
     */
    public function mentions(Request $request) {

        return $this->render('default/mentions.html.twig');
    }

    /**
     * @Route("/faq.html", name="FAQ", methods={"GET"})
     */
    public function faq(Request $request) {

        return $this->render('default/faq.html.twig');
    }

    /**
     * @Route("/confidentialites.html", name="confidentialites", methods={"GET"})
     */
    public function confidentialites(Request $request) {

        return $this->render('default/confidentialites.html.twig');
    }

    public function utilisation(Request $request) {

        return $this->render('default/utilisation.html.twig');
    }

    /**
     * @Route("/favorite.html", name="favorite")
     */
    public function favorite(Request $request) {

        $id = $request->request->get('id');

        $annonce = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findOneById($id);

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $favorite = new Favorite();
        $favorite->setAnnonce($annonce);
        $favorite->setUser($user);
        $em->persist($favorite);
        $em->flush();
        
        return $this->json('succes');
    }
}
