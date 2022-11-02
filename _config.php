<?php

use OP\SAMLADFS\Controllers\Controller;
use SilverStripe\Core\Config\Config;
use SilverStripe\SAML\Services\SAMLConfiguration;

Config::modify()->set(SAMLConfiguration::class, 'strict', Controller::SettingStrict());
Config::modify()->set(SAMLConfiguration::class, 'debug', Controller::SettingDebug());
Config::modify()->set(SAMLConfiguration::class, 'disable_authn_contexts', Controller::SettingDisableAuthnContexts());
Config::modify()->set(SAMLConfiguration::class, 'nameIdEncrypted', Controller::SettingNameIdEncrypted());
Config::modify()->set(SAMLConfiguration::class, 'SP', Controller::SettingSP());
Config::modify()->set(SAMLConfiguration::class, 'IdP', Controller::SettingIDP());
