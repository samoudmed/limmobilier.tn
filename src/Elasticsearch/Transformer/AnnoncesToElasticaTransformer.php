<?php

// src/Elasticsearch/Transformer/AnnoncesToElasticaTransformer.php

namespace App\Elasticsearch\Transformer;

use App\Entity\Annonces;
use FOS\ElasticaBundle\Transformer\ElasticaToModelTransformerInterface;
use FOS\ElasticaBundle\Transformer\ModelToElasticaTransformerInterface;
use Elastica\Document;

class AnnoncesToElasticaTransformer implements ModelToElasticaTransformerInterface
{
    public function transform($object, array $fields): Document
    {
        /** @var Annonces $object */
        $data = [
            'label' => $object->getLabel(),
            'slug' => $object->getSlug(),
            'prix' => $object->getPrix(),
            'description' => $object->getDescription(),
            'ville' => $object->getVille()?->getLabel(),
            'gouvernorat' => $object->getGouvernorat()?->getLabel(),
            'delegation' => $object->getDelegation()?->getLabel(),
        ];

        return new Document($object->getId(), $data);
    }
}
