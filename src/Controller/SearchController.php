<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use App\Entity\Annonces;
use App\Entity\Photos;
use App\Entity\User;
use App\Entity\Villes;
use App\Entity\Gouvernorat;
use App\Entity\Delegation;
use App\Entity\Kind;
use App\Form\MessageType;
use App\Form\ContactType;
use App\Form\AnnoncesType;
use Symfony\Component\Finder\SplFileInfo;
use App\Form\NewsletterType;
USE App\Form\NewsletterGeoType;
use App\Entity\Newsletter;
use App\Entity\Message;
use App\Entity\Contact;
use App\Entity\NewsletterGeo;
use App\Repository\VillesRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Finder\Finder;
use Cocur\Slugify\Slugify;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\Search;
use \Liip\ImagineBundle\Imagine\Cache\CacheManager;
use App\Service\ManagePhoto;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match as MatchQuery;
use Elastica\Query\MultiMatch;

class SearchController extends AbstractController {

    /**
     * @Route("/recherche-annonce.html", name="search_list")
     */
    public function searchListe(Request $request, Search $search) {

        $em = $this->getDoctrine()->getManager();

        $type = $request->request->get('type');
        $offre = ucfirst($request->request->get('offre'));
        $user = ucfirst($request->request->get('user'));
        $gouvernorat = $request->request->get('gouvernorat');
        $delegation = $request->request->get('delegation');
        $ville = $request->request->get('ville');
        $surfaceMin = $request->request->get('surfaceMin');
        $surfaceMax = $request->request->get('surfaceMax');
        $prixMin = $request->request->get('prixMin');
        $prixMax = $request->request->get('prixMax');
        $chambres = $request->request->get('chambres');
        $piscine = $request->request->get('piscine');
        $parking = $request->request->get('parking');
        $jacuzzi = $request->request->get('jacuzzi');
        $cuisine = $request->request->get('cuisine');
        $chauffage = $request->request->get('chauffage');
        $climatiseur = $request->request->get('climatiseur');
        $securite = $request->request->get('securite');
        $internet = $request->request->get('internet');
        $cheminee = $request->request->get('cheminee');
        $vue = $request->request->get('vue');
        $meuble = $request->request->get('meuble');
        $jardin = $request->request->get('jardin');
        $keyWord = $request->request->get('keyWord');
        $sort = $request->request->get('sort');

        $annonces = $this->search($offre, $type, $gouvernorat, $delegation, $ville, $surfaceMin, $surfaceMax, $prixMin, $prixMax, $chambres, $keyWord, $piscine, $parking, $jacuzzi, $cuisine, $chauffage, $climatiseur, $securite, $internet, $cheminee, $vue, $meuble, $jardin, $user, $sort);
        $search->saveRecherche($request);

        foreach ($annonces as $k => $value) {

            $slugify = new Slugify();
            //$query = "SELECT logo FROM user u WHERE u.id = '" . $value['user_id_10'] . "' ";
            //$statement = $em->getConnection()->prepare($query);
            //$statement->execute();
            //$resultSet = $statement->executeQuery();
            //$user = $resultSet->fetchAssociative();
            if (isset($value['logo'])) {
                $annonces[$k]['logo'] = $value['logo'];
            }

            $annonces[$k]['slug'] = $slugify->slugify($value['label_2']);
            $annonces[$k]['ville'] = $value['ville_label'];
            $annonces[$k]['delegation'] = $value['delegation_label'];
            /* $photo = $this->getDoctrine()
              ->getRepository(Photos::class)
              ->findOneBy(array('annonce' => $value['id_1'], 'featured' => 1)); */

            if (!isset($value['photo'])) {
                $annonces[$k]['photo'] = 'default-img.png';
            }
        }

        return $this->json($annonces);
    }

    public function search($offre = null, $type = null, $gouvernorat = null, $delegation = null, $ville = null, $surfaceMin = null, $surfaceMax = null, $prixMin = null, $prixMax = null, $chambres = null, $keyWord = null, $piscine = null, $parking = null, $jacuzzi = null, $cuisine = null, $chauffage = null, $climatiseur = null, $securite = null, $internet = null, $cheminee = null, $vue = null, $meuble = null, $jardin = null, $user = null, $sort = null) {

        $date = new \DateTime('now');
        $offre = ucfirst($offre);
        $em = $this->getDoctrine()->getManager();

        $where = '';

        if (($offre != 'ALL') && ($offre != null) && ($offre != 'Undefined')) {
            $where .= ' AND  a.offre = :offre ';
        }

        if (($type != 'ALL') && ($type != NULL)) {
            $where .= ' AND a.kind_id = :type ';
        }

        if (($ville != 'null') && ($ville != NULL) && ($ville != 'ALL')) {
            $where .= ' AND a.ville_id = :ville ';
        }

        if (($gouvernorat != 'ALL') && ($gouvernorat != NULL) && ($gouvernorat != 'ALL')) {
            $where .= ' AND a.gouvernorat_id = :gouvernorat ';
        }

        if (($delegation != 'null') && ($delegation != NULL) && ($delegation != 'ALL')) {
            $where .= ' AND a.delegation_id = :delegation ';
        }

        if (($user != 'null') && ($user != NULL) && ($user != 'ALL')) {
            $where .= ' AND a.user_id = :user';
        }

        if ($prixMin != '') {

            $where .= ' AND a.prix >= :prixMin ';
        }

        if ($prixMax != '') {
            $where .= ' AND a.prix <= :prixMax ';
        }

        if ($surfaceMin != '') {
            $where .= ' AND a.surface >= :surfaceMin ';
        }

        if ($surfaceMax != '') {
            $where .= ' AND a.surface <= :surfaceMax ';
        }

        if ($chambres != '') {
            $where .= ' AND a.pieces = :chambres ';
        }

        if ($piscine == 'true') {
            $where .= ' AND a.piscine = 1';
        }

        if ($parking == 'true') {
            $where .= ' AND a.parking = 1';
        }

        if ($jacuzzi == 'true') {
            $where .= ' AND a.jacuzzi = 1';
        }

        if ($cuisine == 'true') {
            $where .= ' AND a.cuisine_equipe = 1';
        }

        if ($chauffage == 'true') {
            $where .= ' AND a.chauffage = 1';
        }

        if ($climatiseur == 'true') {
            $where .= ' AND a.climatiseur = 1';
        }

        if ($securite == 'true') {
            $where .= ' AND a.securite = 1';
        }

        if ($meuble == 'true') {
            $where .= ' AND a.meuble = 1';
        }

        if ($jardin == 'true') {
            $where .= ' AND a.jardin = 1';
        }

        if ($internet == 'true') {
            $where .= ' AND a.internet = 1';
        }

        if ($cheminee == 'true') {
            $where .= ' AND a.cheminee = 1';
        }

        if ($vue == 'true') {
            $where .= ' AND a.vue = 1';
        }

        if ($keyWord != '') {
            $where .= ' AND a.label like :keyWord ';
        }

        switch ($sort) {
            case 'default':
                $order = 'a.id ASC';
                break;
            case 'low-price':
                $order = '`a`.`prix` DESC';
                break;
            case 'height-price':
                $order = '`a`.`prix` ASC';
                break;
            case 'low-surface':
                $order = 'surface_3 DESC';
                break;
            case 'height-surface':
                $order = 'surface_3 ASC';
                break;
            default :
                $order = 'a.id ASC';
                break;
        }

        $query = 'SELECT a.id AS id_1, a.offre AS offre_2, a.prix AS prix_3, a.user_id AS user_id_10, a.label AS label_2, a.surface AS surface_3, a.adresse AS adresse_4, a.ville_id AS ville_id_11, v.label AS ville_label, d.label AS delegation_label, a.kind_id AS kind_id_14, a.user_id AS user_id_15, a.pieces AS nbrPieces, u.logo, p.nom as photo , t.label as tlabel FROM annonces a left join villes v on a.ville_id = v.id left join delegation d on a.delegation_id = d.id left join user u on a.user_id = u.id left join photos p on p.annonce_id = a.id left join types t on t.id = a.kind_id WHERE p.featured=1 and a.statut=1 and a.deleted=0 and a.published=1 and a.expired_at >= "' . $date->format('Y-m-d') . '" ' . $where . ' GROUP BY a.id, a.offre, a.prix, a.user_id, a.label, a.surface, a.adresse, a.ville_id, v.label, d.label, a.kind_id, a.pieces, u.logo, p.nom, t.label order by ' . $order;

        $statement = $em->getConnection()->prepare($query);

        // Set parameters 
        if (($offre != 'ALL') && ($offre != null) && ($offre != 'Undefined')) {
            $statement->bindValue('offre', $offre);
        }
        if (($type != 'ALL') && ($type != NULL)) {
            $statement->bindValue('type', $type);
        }
        if (($ville != 'null') && ($ville != NULL) && ($ville != 'ALL')) {
            $statement->bindValue('ville', $ville);
        }
        if (($delegation != 'null') && ($delegation != NULL) && ($delegation != 'ALL')) {
            $statement->bindValue('delegation', $delegation);
        }
        if (($gouvernorat != 'ALL') && ($gouvernorat != NULL) && ($gouvernorat != 'ALL')) {
            $statement->bindValue('gouvernorat', $gouvernorat);
        }
        if (($user != 'null') && ($user != NULL) && ($user != 'ALL')) {
            $statement->bindValue('user', $user);
        }
        if ($surfaceMin != '') {
            $statement->bindValue('surfaceMin', $surfaceMin);
        }
        if ($surfaceMax != '') {
            $statement->bindValue('surfaceMax', $surfaceMax);
        }
        if ($prixMin != '') {
            $statement->bindValue('prixMin', $prixMin);
        }
        if ($prixMax != '') {
            $statement->bindValue('prixMax', $prixMax);
        }
        if ($chambres != '') {
            $statement->bindValue('chambres', $chambres);
        }
        if ($keyWord != '') {
            $statement->bindValue('keyWord', '%' . $keyWord . '%');
        }

        $result = $statement->execute()->fetchAll();

        return $result;
    }

    /**
     * @Route("/recherche.html", name="annonce_search")
     */
    public function searchAnnonce(Request $request, PaginatedFinderInterface $annonceFinder, ManagePhoto $managePhoto) {
        $userQuery = $request->query->get('q');

        $boolQuery = new BoolQuery();

        // Add filter for 'offre' field if query contains 'location' or 'vente'
        /*if (stripos($userQuery, 'location') !== false) {
            $boolQuery->addMust(new MatchQuery('offre', 'location'));
        } elseif (stripos($userQuery, 'vente') !== false) {
            $boolQuery->addMust(new MatchQuery('offre', 'vente'));
        }*/

        // Search general fields for the query text
        $textQuery = new MultiMatch();
        $textQuery->setQuery($userQuery);
        $textQuery->setFields(['title', 'description', 'ville.nom', 'gouvernorat.nom']);
        $boolQuery->addMust($textQuery);

        // Wrap in the main Elasticsearch query
        $query = new Query($boolQuery);

        // Execute paginated search
        $results = $annonceFinder->findPaginated($query);

        // Extract results for current page and add photos
        $annoncesList = $managePhoto->getFeaturedPhoto($results->getCurrentPageResults());

        // Render your template with search results
        return $this->render('default/index.html.twig', [
                    'annonces' => $annoncesList,
        ]);
    }

    public function countListingType(Request $request, $offre, $type) {

        $offre = ucfirst($offre);
        $annonces = $this->search($offre, $type);

        return new Response(count($annonces));
    }

}
