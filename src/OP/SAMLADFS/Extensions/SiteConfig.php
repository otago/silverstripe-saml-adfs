<?php

namespace OP\SAMLADFS\Extensions;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;

class SiteConfig extends DataExtension
{
    private static $has_one = [
        'SP_Private_Key' => File::class,
        'SP_X509_Cert' => File::class,
        'IDP_X509_Cert' => File::class
    ];

    private static $owns = [
        'SP_Private_Key',
        'SP_X509_Cert',
        'IDP_X509_Cert',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab(
            'Root.SAML ADFS',
            [
                UploadField::create('SP_Private_Key'),
                UploadField::create('SP_X509_Cert'),
                UploadField::create('IDP_X509_Cert')
            ]
        );
    }
}
