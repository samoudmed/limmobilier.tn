<?php
namespace App\Service;

use App\Entity\Annonces;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfAnnonceGenerator
{
    public function generate(Annonces $annonce, array $photos): string
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $html = '<html><head><style>
            body { font-family: Arial, sans-serif; }
            .main-photo { width: 100%; max-height: 350px; object-fit: cover; margin-bottom: 20px; }
            .small-photos { display: flex; gap: 10px; margin-bottom: 20px; }
            .small-photos img { width: 120px; height: 90px; object-fit: cover; border-radius: 6px; }
            .desc { margin-bottom: 20px; }
            .ref { font-size: 12px; color: #888; }
        </style></head><body>';

        // Grande photo principale
        if (count($photos) > 0) {
            $html .= '<img class="main-photo" src="' . $photos[0] . '" alt="Photo principale" />';
        }
        // Petites photos
        if (count($photos) > 1) {
            $html .= '<div class="small-photos">';
            foreach (array_slice($photos, 1) as $photo) {
                $html .= '<img src="' . $photo . '" alt="Photo annonce" />';
            }
            $html .= '</div>';
        }
        // Description
        $html .= '<div class="desc"><strong>Description :</strong><br>' . nl2br(htmlentities($annonce->getDescription())) . '</div>';
        // Références
        $html .= '<div class="ref">Référence annonce : ' . $annonce->getId() . '<br>';
        $html .= 'Type : ' . $annonce->getKind() . '<br>';
        $html .= 'Ville : ' . $annonce->getVille() . '<br>';
        $html .= 'Prix : ' . number_format($annonce->getPrix(), 0, '.', ' ') . ' TND<br>';
        $html .= 'Contact : ' . $annonce->getUser()->getEmail() . '</div>';

        $html .= '</body></html>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}
