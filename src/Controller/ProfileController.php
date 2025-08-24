<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Form\UserType;

class ProfileController extends AbstractController {

    /**
     * @Route("/compte/mon-profil.html", name="my_profile", methods={"GET", "POST"})
     */
    public function myProfil(Request $request, UserPasswordHasherInterface $userPasswordHasher) {

        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $data = $form->getData();
            if ($form->get('logo')->getData()) {

                $file = $form->get('logo')->getData();
                if (($file->guessExtension() == 'png') || ($file->guessExtension() == 'jpg') || ($file->guessExtension() == 'jpeg')) {
                    $fileName = md5(date('Y-m-d H:i:s:u')) . '.' . $file->guessExtension();

                    // moves the file to the directory where brochures are stored
                    $file->move($this->getParameter('logo_directory'), $fileName);

                    // updates the 'brochure' property to store the PDF file name
                    // instead of its contents

                    $data->setLogo($fileName);
                } else {
                    $data->setLogo('avatar.png');
                }
            } else {
                $data->setLogo('avatar.png');
            }
            if($form->get('plainPassword')->getData()) {
                $data->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);

            $em->flush();
            // ... persist the $product variable or any other work
        }

        return $this->render('default/compte/profil.html.twig', ['form' => $form->createView(), 'setting' => $user]);
    }
}

