<?php

namespace OP\SAMLADFS\Controllers;

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
}
