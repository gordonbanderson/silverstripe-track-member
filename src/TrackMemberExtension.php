<?php
/**
 * Created by PhpStorm.
 * User: gordon
 * Date: 26/6/2561
 * Time: 20:31 น.
 */

namespace Suilven\TrackMember;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Security\Security;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\DataExtension;

/**
 * Class TrackMemberExtension
 *
 * Shamelessly stolen from https://docs.silverstripe.org/en/4/developer_guides/extending/how_tos/track_member_logins/
 *
 * @package Suilven\TrackMember
 */
class TrackMemberExtension extends DataExtension
{
    private static $db = [
        'LastVisited' => 'Datetime',
        'NumVisit' => 'Int',
    ];

    public function memberLoggedIn()
    {
        $this->logVisit();
    }

    public function memberAutoLoggedIn()
    {
        $this->logVisit();
    }

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab('Root.Main', [
            ReadonlyField::create('LastVisited', 'Last visited'),
            ReadonlyField::create('NumVisit', 'Number of visits')
        ]);
    }

    protected function logVisit()
    {
        if(!Security::database_is_ready()) return;

        DB::query(sprintf(
            'UPDATE "Member" SET "LastVisited" = %s, "NumVisit" = "NumVisit" + 1 WHERE "ID" = %d',
            DB::get_conn()->now(),
            $this->owner->ID
        ));
    }
}
