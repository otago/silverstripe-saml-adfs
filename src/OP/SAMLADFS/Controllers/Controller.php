<?php

namespace OP\SAMLADFS\Controllers;

use SilverStripe\Assets\FilenameParsing\HashFileIDHelper;
use SilverStripe\Core\Environment;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataObject;
use SilverStripe\SAML\Control\SAMLController;
use SilverStripe\SAML\Helpers\SAMLHelper;
use SilverStripe\SiteConfig\SiteConfig;

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
        $siteconfig = DataObject::get_one(SiteConfig::class);
        return [
            'entityId' => Environment::getEnv("SAMLADFS_SP_ENTITY_ID"),
            'privateKey' => self::getProtectedFilePath($siteconfig->SP_Private_Key()),
            'x509cert' => self::getProtectedFilePath($siteconfig->SP_X509_Cert())
        ];
    }

    public static function IDP()
    {
        $siteconfig = DataObject::get_one(SiteConfig::class);
        return [
            'entityId' => Environment::getEnv("SAMLADFS_IDP_ENTITY_ID"),
            'singleSignOnService' => Environment::getEnv("SAMLADFS_IDP_SINGLE_SIGNON_SERVICE"),
            'singleLogoutService' => Environment::getEnv("SAMLADFS_IDP_SINGLE_LOGOUT_SERVICE"),
            'metadata' => Environment::getEnv("SAMLADFS_IDP_METADATA"),
            'x509cert' => self::getProtectedFilePath($siteconfig->IDP_X509_Cert())
        ];
    }

    public static function getProtectedFilePath($protected_file)
    {
        $parent = $protected_file->Parent();
        $path = '';
        do {
            $path = $parent->Name . DIRECTORY_SEPARATOR . $path;
            $parent = $parent->Parent();
        } while ($parent->ID);
        $subfolder = substr($protected_file->getHash(), 0, HashFileIDHelper::HASH_TRUNCATE_LENGTH);
        return ASSETS_PATH . DIRECTORY_SEPARATOR . '.protected' . DIRECTORY_SEPARATOR . $path . $subfolder . DIRECTORY_SEPARATOR . $protected_file->Name;
    }
}
