<?php

namespace OP\SAMLADFS\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

class Member extends DataExtension
{
    private static $db = [
        'SAMLADFSGUID' => 'Varchar(255)'
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab(
            'Root.SAML ADFS',
            [
                TextField::create('SAMLADFSGUID', 'GUID')
            ]
        );
    }
}
