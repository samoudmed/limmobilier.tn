<?php

include("resize-class.php");


$nom_repertoire = 'C:/xampp2/htdocs/limmo/public/uploads/photos/';

//ouvre le repertoire
$pointeur = opendir($nom_repertoire);
$i = 0;

//stocke les noms de fichiers images dans un tableau
while ($fichier = readdir($pointeur)) {
    echo $fichier.'<br>';
    if (!file_exists('C:/xampp2/htdocs/limmo/public/uploads/photos/848x682/webp/'. $fichier.'.webp')) {
        //*** 1) Initialise /load image
        $resizeObj = new resize('C:/xampp2/htdocs/limmo/public/uploads/photos/' . $fichier);

        $resizeObj->resizeImage(86, 50, 'crop');
        //*** 3) Save image ('image-name', 'quality [int]')
        $resizeObj->saveImage('C:/xampp2/htdocs/limmo/public/uploads/photos/86x50/' . $fichier, 100);

        $resizeObj->resizeImage(263, 175, 'crop');

        //*** 3) Save image ('image-name', 'quality [int]')
        //$resizeObj->saveImage('C:/xampp/htdocs/limmo/public/uploads/photos/263x175/' . $fichier, 100);
        $resizeObj->saveImage('C:/xampp2/htdocs/limmo/public/uploads/photos/263x175/webp/'. $fichier.'.webp', 100);
        $resizeObj->resizeImage(339, 226, 'crop');

        $resizeObj->resizeImage(848, 682, 'crop');

        //*** 3) Save image ('image-name', 'quality [int]')
        $resizeObj->saveImage('C:/xampp2/htdocs/limmo/public/uploads/photos/848x682/'. $fichier, 100);
        
        //*** 3) Save image ('image-name', 'quality [int]')
        $resizeObj->saveImage('C:/xampp2/htdocs/limmo/public/uploads/photos/339x226/webp/' . $fichier, 100);

        //*** 2) Resize image (options: exact, portrait, landscape, auto, crop)
        $resizeObj->resizeImage(848, 682, 'crop');

        //*** 3) Save image ('image-name', 'quality [int]')
        $resizeObj->saveImage('C:/xampp2/htdocs/limmo/public/uploads/photos/848x682/webp/'. $fichier.'.webp', 100);
    }
}



// On ouvre un dossier et on le parcours en captant le nom des fichiers présents
/* if (is_dir($dir)) {
  $scandir = scandir("C:/xampp2/htdocs/limmo/public/asset/images/uploads/photos");
  if ($dh = opendir($dir)) {
  foreach($scandir as $file) {
  $image_dest = "C:/xampp2/htdocs/limmo/public/media/cache/my_thumb/asset/images/uploads/photos";
  // Extensions et mimes autorisés
  $extensions = array('jpg', 'jpeg', 'png', 'gif');
  $mimes = array('image/jpeg', 'image/gif', 'image/png');

  // Récupération de l'extension de l'image
  $tab_ext = extension($file);
  $extension = strtolower($tab_ext[count($tab_ext) - 1]);

  // Récupération des informations de l'image
  $image_data = getimagesize($file);

  // Test si l'extension est autorisée
  if (in_array($extension, $extensions) && in_array($image_data['mime'], $mimes)):

  // On stocke les dimensions dans des variables
  $img_width = $image_data[0];
  $img_height = $image_data[1];

  // On vérifie quel coté est le plus grand
  if ($img_width >= $img_height && $type != "height"):

  // Calcul des nouvelles dimensions à partir de la largeur
  if ($max_size >= $img_width):
  return 'no_need_to_resize';
  endif;

  $new_width = $max_size;
  $reduction = ( ($new_width * 100) / $img_width );
  $new_height = round(( ($img_height * $reduction ) / 100), 0);

  else:

  // Calcul des nouvelles dimensions à partir de la hauteur
  if ($max_size >= $img_height):
  return 'no_need_to_resize';
  endif;

  $new_height = $max_size;
  $reduction = ( ($new_height * 100) / $img_height );
  $new_width = round(( ($img_width * $reduction ) / 100), 0);

  endif;

  // Création de la ressource pour la nouvelle image
  $dest = imagecreatetruecolor($new_width, $new_height);

  // En fonction de l'extension on prépare l'iamge
  switch ($extension) {
  case 'jpg':
  case 'jpeg':
  $src = imagecreatefromjpeg($file); // Pour les jpg et jpeg
  break;

  case 'png':
  $src = imagecreatefrompng($file); // Pour les png
  break;

  case 'gif':
  $src = imagecreatefromgif($file); // Pour les gif
  break;
  }

  // Création de l'image redimentionnée
  if (imagecopyresampled($dest, $src, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height)):

  // On remplace l'image en fonction de l'extension
  switch ($extension) {
  case 'jpg':
  case 'jpeg':
  imagejpeg($dest, $image_dest, $qualite); // Pour les jpg et jpeg
  break;

  case 'png':
  $black = imagecolorallocate($dest, 0, 0, 0);
  imagecolortransparent($dest, $black);

  $compression = round((100 - $qualite) / 10, 0);
  imagepng($dest, $image_dest, $compression); // Pour les png
  break;

  case 'gif':
  imagegif($dest, $image_dest); // Pour les gif
  break;
  }

  return 'success';

  else:
  return 'resize_error';
  endif;

  else:
  return 'no_img';
  endif;
  }
  closedir($dh); //on ferme tout ça et la vie est belle !!!
  }
  } else {
  echo 'no dir';
  } */
?>