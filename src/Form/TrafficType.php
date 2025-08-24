<?php

namespace App\Form;

use App\Entity\Traffic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrafficType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('argv')
            ->add('argc')
            ->add('gatewayInterface')
            ->add('serverAddr')
            ->add('serverName')
            ->add('serverSoftware')
            ->add('serverProtocol')
            ->add('requestMethod')
            ->add('requestTime')
            ->add('requestTime_float')
            ->add('queryString')
            ->add('documentRoot')
            ->add('httpAccept')
            ->add('httpAcceptCharset')
            ->add('httpAcceptEncoding')
            ->add('httpAcceptLanguage')
            ->add('httpConnection')
            ->add('httpHost')
            ->add('httpReferer')
            ->add('httpUserAgent')
            ->add('https')
            ->add('remoteAddr')
            ->add('hostName')
            ->add('remoteHost')
            ->add('remotePort')
            ->add('remoteUser')
            ->add('redirectRemoteUser')
            ->add('scriptFilename')
            ->add('serverAdmin')
            ->add('serverPort')
            ->add('serverSignature')
            ->add('pathTranslated')
            ->add('scriptName')
            ->add('requestUri')
            ->add('phpAuthDigest')
            ->add('phpAuthUser')
            ->add('phpAuthPw')
            ->add('authType')
            ->add('pathInfo')
            ->add('origPathInfo')
            ->add('connectedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Traffic::class,
        ]);
    }
}
