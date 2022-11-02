<?php

namespace OP\SAMLADFS\Controllers;

use SilverStripe\Assets\FilenameParsing\HashFileIDHelper;
use SilverStripe\Core\Environment;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataObject;
use SilverStripe\SAML\Control\SAMLController;
use SilverStripe\SAML\Helpers\SAMLHelper;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;
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
        $data = [
            "guid" => SAMLHelper::singleton()->binToStrGuid(base64_decode($auth->getNameId())),
            "attributes" => $auth->getAttributes()
        ];
        $this->extend('handleAuthData', $data);
        $member = Member::get()->filter("SAMLADFSGUID", $data["guid"])->First();
        Security::setCurrentUser($member);
        $this->extend('handleRedirect');
    }

    public static function SettingSP()
    {
        $siteconfig = DataObject::get_one(SiteConfig::class);
        return [
            'entityId' => Environment::getEnv("SAMLADFS_SP_ENTITY_ID"),
            'privateKey' => self::getProtectedFilePath($siteconfig->SP_Private_Key()),
            'x509cert' => self::getProtectedFilePath($siteconfig->SP_X509_Cert())
        ];
    }

    public static function SettingIDP()
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

    public static function SettingDisableAuthnContexts()
    {
        return Environment::getEnv("SAMLADFS_DISABLE_AUTHN_CONTEXTS");
    }

    public static function SettingNameIdEncrypted()
    {
        return Environment::getEnv("SAMLADFS_NAME_ID_ENCRYPED");
    }

    public static function SettingStrict()
    {
        return Environment::getEnv("SAMLADFS_STRICT");
    }

    public static function SettingDebug()
    {
        return Environment::getEnv("SAMLADFS_DEBUG");
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
