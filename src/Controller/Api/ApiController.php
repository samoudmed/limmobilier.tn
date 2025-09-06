<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Finder\SplFileInfo;
use App\Entity\Photos;
use App\Entity\User;
use App\Entity\Annonces;
use App\Entity\Sender;
use App\Entity\Message;
use App\Entity\Delegation;
use App\Entity\Gouvernorat;
use App\Entity\Villes;
use App\Entity\Kind;

class ApiController extends AbstractController {

    /**
     * @Route("/api/properties", name="api-properties", methods={"GET"})
     */
    public function index(Request $request): JsonResponse {

        $annonces = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findActive(24);
        $annoncesList = $this->getFeaturedPhoto($annonces);
        $data = array_map(function($property) {
            return [
                'id' => $property->getId(),
                'title' => $property->getLabel(),
                'price' => $property->getPrix(),
                'rooms' => $property->getPieces(),
                'surface' => $property->getSurface(),
                'city' => $property->getDelegation()->getLabel(),
                'image' => $property->photo,
                'agence' => $property->getUser()->getLogo(),
            ];
        }, $annoncesList);

        return $this->json($data);
    }

    /**
     * @Route("/api/property/{id}", name="api-property", methods={"GET"})
     */
    public function annonce($id) {

        $annonce = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findOneBy(['id' => $id]);

        if (!$annonce) {
            return $this->json(['error' => 'Annonce non trouvée'], 404);
        }

        $photos = array_map(function($photo) {
            return [
                'photo' => $photo->getNom(),
            ];
        }, $annonce->getPhotos()->toArray());

        $data = [
            'id' => $annonce->getId(),
            'title' => $annonce->getLabel(),
            'price' => $annonce->getPrix(),
            'rooms' => $annonce->getPieces(),
            'surface' => $annonce->getSurface(),
            'city' => $annonce->getDelegation()->getLabel(),
            'photos' => $photos,
            'agence' => $annonce->getUser()->getLogo(),
            'agencyPhone' => $annonce->getUser()->getTelephone(),
            'description' => $annonce->getDescription(),
        ];

        return $this->json($data);
    }

    public function getFeaturedPhoto($annonces) {

        foreach ($annonces as $k => $annonce) {

            $photo = $this->getDoctrine()
                    ->getRepository(Photos::class)
                    ->findOneBy(array('annonce' => $annonce, 'featured' => 1));
            if (!$photo) {
                $photo = $this->getDoctrine()
                        ->getRepository(Photos::class)
                        ->findOneBy(array('annonce' => $annonce));
            }
            $annonces[$k]->photo = (isset($photo)) ? $photo->getNom() : 'default-img.png';
        }

        return $annonces;
    }

    /**
     * @Route("/api/contact", name="api_contact", methods={"POST"})
     */
    public function contact(Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data || empty($data['name']) || empty($data['email']) || empty($data['message'])) {
            return new JsonResponse(['message' => 'Champs obligatoires manquants'], 400);
        }

        $sender = new Sender();
        $sender->setNom($data['name']);
        $sender->setEmail($data['email']);
        $sender->setTelephone($request->request->get('phone'));

        $em = $this->getDoctrine()->getManager();

        $annonce = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findOneById($data['listingId']);


        $em->persist($sender);
        $em->flush();
        $message = New Message();
        $message->setCreatedAt(new \DateTime('now'));
        $message->setUpdatedAt(new \DateTime('now'));
        $message->setSender($sender);
        $message->setReceiver($annonce->getUser());
        $message->setSujet('test');
        $message->setMessage($data['message']);
        $message->setValidated(0);
        $message->setStatut(0);
        $message->setIsDeleted(0);
        $message->setAnnonce($annonce);
        $em->persist($message);
        $em->flush();

        return new JsonResponse(['message' => 'Message reçu !'], 200);
    }

    /**
     * @Route("/api/signin", name="api-signin", methods={"POST"})
     */
    public function signin(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse {

        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = $this->getDoctrine()
                        ->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return $this->json(['error' => 'Invalid credentials'], 200);
        }

// Generate and return an API token here (e.g., JWT or custom)
        $token = bin2hex(random_bytes(32));
//$user->setApiToken($token); // Assuming your User entity has setApiToken()
//$userRepository->save($user, true);

        return $this->json([
                    'token' => $token,
                    'user' => $user->getEmail(),
                    'id' => $user->getId(),
        ]);
    }

    /**
     * @Route("/api/delegations/{gouvernoratName}", name="api-delegations", methods={"GET"})
     */
    public function delegations($gouvernoratName) {

        $gouvernorat = $this->getDoctrine()
                ->getRepository(Gouvernorat::class)
                ->findOneBy(['label' => $gouvernoratName]);

        $delegations = $this->getDoctrine()
                ->getRepository(Delegation::class)
                ->findBy(['gouvernorat' => $gouvernorat]);

        $data = array_map(function($delegation) {
            return [
                'value' => $delegation->getId(),
                'label' => $delegation->getLabel(),
            ];
        }, $delegations);

        return $this->json($data);
    }

    /**
     * @Route("/api/villes/{id}", name="api-villes", methods={"GET"})
     */
    public function villes($id) {

        $delegation = $this->getDoctrine()
                ->getRepository(Gouvernorat::class)
                ->findOneBy(['id' => $id]);

        $villes = $this->getDoctrine()
                ->getRepository(Villes::class)
                ->findBy(['delegation' => $delegation]);

        $data = array_map(function($ville) {
            return [
                'value' => $ville->getId(),
                'label' => $ville->getLabel(),
            ];
        }, $villes);

        return $this->json($data);
    }

    /**
     * @Route("/api/deposer-annonce", name="api_deposer_annonce", methods={"POST"})
     */
    public function deposerAnnonce(Request $request): JsonResponse {

        $data = json_decode($request->getContent(), true);
        $annonce = new Annonces();
        $expiredAt = 30;
        $dateExpired = new \DateTime();
        $dateExpired->modify('+' . $expiredAt . ' days');

        $gouvernorat = $this->getDoctrine()
                ->getRepository(Gouvernorat::class)
                ->findOneBySlug($data['gouvernorat']);

        $delegation = $this->getDoctrine()
                ->getRepository(Delegation::class)
                ->findOneById($data['delegation']);

        $ville = $this->getDoctrine()
                ->getRepository(Villes::class)
                ->findOneById($data['ville']);

        $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneById(1);

        $kind = $this->getDoctrine()
                ->getRepository(Kind::class)
                ->findOneBySlug($data['typeBien']);

        $annonce->setLabel($data['title']);
        $annonce->setSlug($data['title']);
        $annonce->setOffre($data['action']);
        $annonce->setKind($kind);
        $annonce->setUser($user);
        $annonce->setVille($ville);
        $annonce->setGouvernorat($gouvernorat);
        $annonce->setDelegation($delegation);
        $annonce->setCreatedAt(new \DateTime('now'));
        $annonce->setUpdatedAt(new \DateTime('now'));
        $annonce->setDescription(html_entity_decode($data['description']));
        $annonce->setExpiredAt($dateExpired);
        $annonce->setCreatedAt(new \DateTime('now'));
        $annonce->setUpdatedAt(new \DateTime('now'));
        $annonce->setStatut(1);
        $annonce->setPublished(1);
        $annonce->setDeleted(0);
        $annonce->setView(0);
        $this->getDoctrine()->getManager()->persist($annonce);
        $this->getDoctrine()->getManager()->flush();

        //upload photos
        $photos = $data['photos'] ?? [];
        foreach ($photos as $index => $base64) {
            $photo = preg_replace('#^data:image/\w+;base64,#i', '', $base64);
            $name = $photo['name'] ?? ('photo_' . $index . '.jpg');
            if ($photo) {
                // Extraire et décoder l'image base64
                $imageData = base64_decode($photo);

                if ($imageData === false) {
                    dd("❌ Erreur de décodage base64 pour $name");
                    continue;
                }

                // Déterminer l'extension (jpg, png, etc.)
                $ext = strtolower('jpg');
                if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    dd("❌ Extension non supportée : $ext");
                    continue;
                }

                // Nom de fichier sécurisé et chemin complet
                $safeName = pathinfo($name, PATHINFO_FILENAME);
                $fileName = $safeName . '_' . uniqid() . '.' . $ext;
                $filePath = $this->getParameter('photo_directory') . '/' . $fileName;
                // Écriture du fichier
                if (!file_put_contents($filePath, $imageData)) {
                    dd("❌ Erreur d’écriture du fichier $filePath");
                    continue;
                }

                // Enregistrement en base de données
                $em = $this->getDoctrine()->getManager();
                $photoEntity = new Photos();
                $photoEntity->setNom($fileName);
                $photoEntity->setFeatured($featured);
                $photoEntity->setCreatedAt(new \DateTime('now'));
                $photoEntity->setAnnonce($annonce);
                $photoEntity->setUser($user);
                $em->persist($photoEntity);
                $em->flush();

                echo "✅ Photo enregistrée : $fileName\n";
            } else {
                dd("❌ Format de donnée invalide pour la photo : $name");
            }
        }

        mail('samoud.mohamed@gmail.com', 'annonce new ', 'annonce new');

        $this->addFlash(
                'success', 'Votre Annonce a été ajoutée avec succès!'
        );

        //$event = new AnnoncePublishedEvent($annonce);
        //$this->dispatcher->dispatch($event, 'annonce.published');

        return new JsonResponse(['message' => 'Message reçu !'], 200);
    }

}
