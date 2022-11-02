<?php

namespace OP\SAMLADFS\Extensions;

use SilverStripe\SAML\Helpers\SAMLHelper;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;
use SilverStripe\SiteConfig\SiteConfig;

class Security extends DataExtension
{
    public function onBeforeSecurityLogin()
    {
        if ($this->isConfigured()) {
            $request = $this->owner->getRequest();
            $helper = Injector::inst()->get(SAMLHelper::class);
            $helper->redirect(null, $request, $request->getURL(true));
        }
    }

    public function isConfigured()
    {
        $siteconfig = DataObject::get_one(SiteConfig::class);
        return $siteconfig->SP_Private_Key()->ID &&
            $siteconfig->SP_X509_Cert()->ID &&
            $siteconfig->IDP_X509_Cert()->ID;
    }
}
