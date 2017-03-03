<?php
/**
 * @package modx
 */

/**
 * Represents extended user profile data.
 *
 * @property int     $internalKey The ID of the modUser record related to this User
 * @property string  $fullname A full name for the User
 * @property string  $email An email address for the User
 * @property string  $phone A phone number for the User
 * @property string  $mobilephone A mobile phone number for the User
 * @property boolean $blocked Whether or not this User is blocked, or prevented from logging in
 * @property int     $blockeduntil If set, the User will be blocked until this date
 * @property int     $blockedafter If set, the User will be blocked after this date
 * @property int     $logincount The total number of times this User has logged into MODX
 * @property int     $lastlogin A UNIX timestamp showing the last time the User logged in
 * @property int     $thislogin A UNIX timestamp showing the time this User currently logged in
 * @property int     $failedlogincount The number of failed logins this User has accumulated
 * @property int     $sessionid The PHP sessionid of the User
 * @property int     $dob The date of birth of the User, in UNIX timestamp format
 * @property int     gender The gender of the user; 1 for male, 2 for female, 0 for unknown
 * @property string  $address The address of the User
 * @property string  $country The country the User resides in
 * @property string  $city The city of the User
 * @property string  $state The state, province or region for the User
 * @property string  $zip The postal code for the User
 * @property string  $fax A facsimile address for the User
 * @property string  $photo Deprecated. Can still be used to store a photo location for the user.
 * @property string  $comment Any user-provided comments
 * @property string  $website The website for the User
 * @property json    $extended A JSON array of extended properties that can be used to store custom fields for the User
 *
 * @see modUser
 * @package modx
 */
class modUserProfile extends xPDOSimpleObject
{

    /**
     * Overrides xPDOObject::save to fire modX-specific events.
     *
     * {@inheritDoc}
     */
    public function save($cacheFlag = null)
    {
        $isNew = $this->isNew();

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnUserProfileBeforeSave', array(
                'mode'        => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'userprofile' => &$this,
                'cacheFlag'   => $cacheFlag,
            ));
        }

        $saved = parent:: save($cacheFlag);

        if ($saved && $this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnUserProfileSave', array(
                'mode'        => $isNew ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'userprofile' => &$this,
                'cacheFlag'   => $cacheFlag,
            ));
        }

        return $saved;
    }

    /**
     * Overrides xPDOObject::remove to fire modX-specific events
     *
     * {@inheritDoc}
     */
    public function remove(array $ancestors = array())
    {
        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnUserProfileBeforeRemove', array(
                'userprofile' => &$this,
                'ancestors'   => $ancestors,
            ));
        }

        $removed = parent:: remove($ancestors);

        if ($this->xpdo instanceof modX) {
            $this->xpdo->invokeEvent('OnUserProfileRemove', array(
                'userprofile' => &$this,
                'ancestors'   => $ancestors,
            ));
        }

        return $removed;
    }


}
