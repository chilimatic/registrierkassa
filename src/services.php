<?php
use MED\Kassa\Model\RequestConfig;
use MED\Kassa\Model\RestConfig;

return [
    'http-client' => function() {
        return new GuzzleHttp\Client();
    },
    'rest-config' => function() {
        $restConfig = new RestConfig();
        $restConfig
            ->setApiRootUrl('https://hs-abnahme.a-trust.at/RegistrierkasseMobile')
            ->setApiVersion('v2')
            ->setApiUriMap(
                [
                    'sign'          => 'Sign/JWS',
                    'certificate'   => 'Certificate',
                    'zda'           => 'ZDA'
                ]
            );

        return $restConfig;
    },
    'request-config' => function() {
        $requestConfig = new RequestConfig();
        $requestConfig
            ->setName('u123456789')
            ->setPassword('123456789')
            ->setPayload('_R1-AT100_CASHBOX-DEMO-1_CASHBOX-DEMO-1-Receipt-ID-82_2016-03-11T04:24:46_0,00_0,00_0,00_0,00_0,00_NLoiSHL3bsM=_eee257579b03302f_cg8hNU5ihto=');
        return $requestConfig;
    }
];