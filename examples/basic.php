<?php

require_once __DIR__ . '/../vendor/autoload.php';

$query = new \AlexaCRM\FetchXML\Fetch();

$aliasedEmailAddress1 = new \AlexaCRM\FetchXML\FetchAttribute( 'emailaddress1', 'email' );

$query->setDistinct( true )
    ->setCount( 3 )
    ->setEntity( 'contact' )
    ->addAttributes( [ 'firstname', 'lastname' => 'surname', $aliasedEmailAddress1 ] )
    ->setOrder( 'lastname' );

echo $query->toXML() . PHP_EOL;
