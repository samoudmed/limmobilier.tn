<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonces;
use App\Entity\Photos;
use App\Entity\User;
use Symfony\Component\Finder\Finder;
use \Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use App\Service\ResizePhoto;
use App\Service\ManagePhoto;

class ClearingController extends AbstractController {

    /**
     * @Route("/clearPhoto.html", name="clearPhoto", methods={"GET"})
     */
    public function clearPhoto(Request $request, CacheManager $imagineCacheManager) {

        $finder = new Finder();
        // find all files in the current directory
        $finder->files()->in(__DIR__ . '/../../public/uploads/photos')->exclude('263x175')->exclude('848x682')->exclude('86x50');

        // check if there are any search results
        if ($finder->hasResults()) {
            // ...
        }

        foreach ($finder as $file) {
            $absoluteFilePath = $file->getRealPath();
            $fileNameWithExtension = $file->getRelativePathname();
            $fileNameWithExtension . '<br>';

            $photo = $this->getDoctrine()
                    ->getRepository(Photos::class)
                    ->findOneByNom($fileNameWithExtension);

            if ((!$photo) && $fileNameWithExtension != 'limmobilier.tn.png' && $fileNameWithExtension != 'image-5.jpg' && $fileNameWithExtension != 'image-4-md.jpg' && $fileNameWithExtension != 'default-img.svg' && $fileNameWithExtension != 'default-img.png.webp' && $fileNameWithExtension != 'affiche.png') {

                echo 'Not photo ' . $absoluteFilePath . '<br>';
                unlink('/home/limmobilier/public_html/public/uploads/photos/' . $fileNameWithExtension);
                unlink('/home/limmobilier/public_html/public/uploads/photos/263x175/' . $fileNameWithExtension);
                unlink('/home/limmobilier/public_html/public/uploads/photos/263x175/webp/' . $fileNameWithExtension . '.webp');
                unlink('/home/limmobilier/public_html/public/uploads/photos/848x682/' . $fileNameWithExtension);
                unlink('/home/limmobilier/public_html/public/uploads/photos/848x682/webp/' . $fileNameWithExtension . '.webp');
                unlink('/home/limmobilier/public_html/public/uploads/photos/86x50/' . $fileNameWithExtension);
            } else {
                echo 'Exist photo ' . $absoluteFilePath . '<br>';
                /* $annonce = $this->getDoctrine()
                  ->getRepository(Annonces::class)
                  ->findById($photo->getAnnonce()); */

                if (!isset($annonce)) {
                    //$entityManager->remove($photo);
                    //$entityManager->flush();
                    //unlink('/home/limmobilier/public_html/public/uploads/photos//' . $fileNameWithExtension);
                    //unlink('/home/limmobilier/public_html/public/uploads/photos//263x175/' . $fileNameWithExtension);
                    //unlink('/home/limmobilier/public_html/public/uploads/photos//848x682/' . $fileNameWithExtension);
                    //unlink('/home/limmobilier/public_html/public/uploads/photos//86x50/' . $fileNameWithExtension);
                    //echo 'Not annonce ' . $photo->getNom() . '<br>';
                } else {
                    // $resolvedPath = $imagineCacheManager->getBrowserPath($file->getRelativePathname(), 'my_thumb');
                }
            }
        }

        return null;
    }

    /**
     * @Route("/fictionalAds.html", name="fictionalAds", methods={"GET"})
     */
    public function fictionalAds(Request $request) {

        $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find(1);

        $annonces = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findByUser($user);

        foreach ($annonces as $k => $annonce) {
            echo $annonce->getLabel() . '<br>';
        }

        return null;
    }

    /**
     * @Route("/deletedAds.html", name="deletedAds", methods={"GET"})
     */
    public function deletedAds(Request $request) {

        $annonces = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findByDeleted(1);
        $filesystem = new Filesystem();

        foreach ($annonces as $k => $annonce) {
            foreach ($annonce->getPhotos() as $k => $photo) {

                if ($filesystem->exists('/home/limmobilier/public_html/public/uploads/photos/' . $photo->getNom())) {
                    echo $photo->getNom() . '<br>';
                    unlink('/home/limmobilier/public_html/public/uploads/photos/' . $photo->getNom());
                    unlink('/home/limmobilier/public_html/public/uploads/photos/263x175/' . $photo->getNom());
                    unlink('/home/limmobilier/public_html/public/uploads/photos/263x175/webp/' . $photo->getNom() . '.webp');
                    unlink('/home/limmobilier/public_html/public/uploads/photos/848x682/' . $photo->getNom());
                    unlink('/home/limmobilier/public_html/public/uploads/photos/848x682/webp/' . $photo->getNom() . '.webp');
                    unlink('/home/limmobilier/public_html/public/uploads/photos/86x50/' . $photo->getNom());
                }
            }
        }

        return null;
    }

    /**
     * @Route("/regenerate-web-photos.html", name="regenerateWebPhotos", methods={"GET"})
     */
    public function regenerateWebPhotos() {
        $em = $this->getDoctrine()->getManager();
        $photos = $em->getRepository(Photos::class)->findBy([], ['id' => 'DESC']);
        
        foreach ($photos as $photo) {
            
            // Assume that getNom() returns the filename of the photo.
            $originalFile = '/home/limmobilier/public_html/public/uploads/photos/' . $photo->getNom();

            // Define the paths for the webp files
            $webpPaths = [
                '/home/limmobilier/public_html/public/uploads/photos/86x50/' . $photo->getNom()
            ];

            // Check if the original photo exists and if webp doesn't exist
            if (file_exists($originalFile)) {
                $webpExists = false;
                
                foreach ($webpPaths as $webpPath) {
                    if (file_exists($webpPath)) {
                        $webpExists = true;
                        break; // If webp file exists, no need to regenerate
                    }
                }

                // If webp doesn't exist, regenerate
                if (!$webpExists) {
                    $managePhoto = new ResizePhoto($this->getParameter('photo_directory') . '/' . $photo->getNom());
                    // Create a new instance of ResizePhoto for each image
                    $managePhoto->resizeImage(86, 50, 'auto');

                    // Save the resized image to the appropriate directory
                    foreach ($webpPaths as $webpPath) {
                        // Ensure the directory exists before saving
                        $dir = dirname($webpPath);
                        if (!is_dir($dir)) {
                            mkdir($dir, 0777, true); // Create directory if it doesn't exist
                        }
                        $managePhoto->saveImage('/home/limmobilier/public_html/public/uploads/photos/86x50/' . $photo->getNom());
                    }
                }
            }
        }
    }
}
