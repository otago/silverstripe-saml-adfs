<?php

use OP\SAMLADFS\Controllers\Controller;
use SilverStripe\Core\Config\Config;
use SilverStripe\SAML\Services\SAMLConfiguration;

Config::modify()->set(SAMLConfiguration::class, 'SP', Controller::SP());
Config::modify()->set(SAMLConfiguration::class, 'IdP', Controller::IDP());
