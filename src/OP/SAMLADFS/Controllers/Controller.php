<?php

namespace OP\SAMLADFS\Controllers;

use SilverStripe\Core\Environment;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\SAML\Control\SAMLController;
use SilverStripe\SAML\Helpers\SAMLHelper;

class Controller extends SAMLController
{
    private static $allowed_actions = [
        'acs'
    ];

    public function acs()
    {
        $auth = Injector::inst()->get(SAMLHelper::class)->getSAMLAuth();
        $auth->processResponse();
        $request = $this->getRequest();
        $attributes = $auth->getAttributes();
        echo '<Pre>'; var_dump($attributes); die();
    }

    public static function SP()
    {
        return [
            'entityId' => Environment::getEnv("SAMLADFS_SP_ENTITY_ID")
        ];
    }

    public static function IDP()
    {
        return [
            'entityId' => Environment::getEnv("SAMLADFS_IDP_ENTITY_ID"),
            'singleSignOnService' => Environment::getEnv("SAMLADFS_IDP_SINGLE_SIGNON_SERVICE"),
            'singleLogoutService' => Environment::getEnv("SAMLADFS_IDP_SINGLE_LOGOUT_SERVICE"),
            'metadata' => Environment::getEnv("SAMLADFS_IDP_METADATA")
        ];
    }
}
