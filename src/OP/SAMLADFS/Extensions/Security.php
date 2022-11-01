<?php

namespace OP\SAMLADFS\Extensions;

use SilverStripe\SAML\Helpers\SAMLHelper;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataExtension;

class Security extends DataExtension
{
    public function onBeforeSecurityLogin()
    {
        $request = $this->owner->getRequest();
        $helper = Injector::inst()->get(SAMLHelper::class);
        $helper->redirect(null, $request, $request->getURL(true));
    }
}
