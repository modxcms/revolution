<?php
/**
 * The core MODx user class.
 *
 * @package modx
 */
class modUser extends modPrincipal {
    /**
     * A collection of contexts which the current principal is authenticated in.
     * @var array
     * @access public
     */
    public $sessionContexts= array ();

    /**
     * Loads the principal attributes that define a modUser security profile.
     *
     * {@inheritdoc}
     *
     * @todo Remove the legacy docgroup support.
     */
    public function loadAttributes($target, $context = '', $reload = false) {
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if ($this->_attributes === null || $reload) {
            $this->_attributes = array();
            if (isset($_SESSION["modx.user.{$this->id}.attributes"])) {
                if ($reload) {
                    unset($_SESSION["modx.user.{$this->id}.attributes"]);
                } else {
                    $this->_attributes = $_SESSION["modx.user.{$this->id}.attributes"];
                }
            }
        }
        if (!isset($this->_attributes[$context])) $this->_attributes[$context] = array();
        if (!isset($this->_attributes[$context][$target])) {
            $accessTable = $this->xpdo->getTableName($target);
            $policyTable = $this->xpdo->getTableName('modAccessPolicy');
            $memberTable = $this->xpdo->getTableName('modUserGroupMember');
            $memberRoleTable = $this->xpdo->getTableName('modUserGroupRole');
            if ($this->get('id') > 0) {
                switch ($target) {
                    case 'modAccessResourceGroup' :
                        $legacyDocGroups = array();
                        $sql = "SELECT acl.target, acl.principal, mr.authority, acl.policy, p.data FROM {$accessTable} acl " .
                                "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                                "JOIN {$memberTable} mug ON acl.principal_class = 'modUserGroup' " .
                                "AND (acl.context_key = :context OR acl.context_key IS NULL OR acl.context_key = '') " .
                                "AND mug.member = :principal " .
                                "AND mug.user_group = acl.principal " .
                                "JOIN {$memberRoleTable} mr ON mr.id = mug.role " .
                                "AND mr.authority <= acl.authority " .
                                "GROUP BY acl.target, acl.principal, acl.authority, acl.policy";
                        $bindings = array(
                            ':principal' => $this->get('id'),
                            ':context' => $context
                        );
                        $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
                        if ($query->stmt && $query->stmt->execute()) {
                            while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                                $this->_attributes[$context][$target][$row['target']][] = array(
                                    'principal' => $row['principal'],
                                    'authority' => $row['authority'],
                                    'policy' => $row['data'] ? xPDO :: fromJSON($row['data'], true) : array(),
                                );
                                $legacyDocGroups[$row['target']]= $row['target'];
                            }
                        }
                        $_SESSION[$context . 'Docgroups']= array_values($legacyDocGroups);
                        break;
                    case 'modAccessContext' :
                        $sql = "SELECT acl.target, acl.principal, mr.authority, acl.policy, p.data FROM {$accessTable} acl " .
                                "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                                "JOIN {$memberTable} mug ON acl.principal_class = 'modUserGroup' " .
                                "AND mug.member = :principal " .
                                "AND mug.user_group = acl.principal " .
                                "JOIN {$memberRoleTable} mr ON mr.id = mug.role " .
                                "AND mr.authority <= acl.authority " .
                                "GROUP BY acl.target, acl.principal, acl.authority, acl.policy";
                        $bindings = array(
                            ':principal' => $this->get('id')
                        );
                        $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
                        if ($query->stmt && $query->stmt->execute()) {
                            while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                                $this->_attributes[$context][$target][$row['target']][] = array(
                                    'principal' => $row['principal'],
                                    'authority' => $row['authority'],
                                    'policy' => $row['data'] ? xPDO :: fromJSON($row['data'], true) : array(),
                                );
                            }
                        }
                        break;
                    case 'modAccessElement' :
                        $sql = "SELECT acl.target, acl.principal, mr.authority, acl.policy, p.data FROM {$accessTable} acl " .
                                "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                                "JOIN {$memberTable} mug ON acl.principal_class = 'modUserGroup' " .
                                "AND (acl.context_key = :context OR acl.context_key IS NULL OR acl.context_key = '') " .
                                "AND mug.member = :principal " .
                                "AND mug.user_group = acl.principal " .
                                "JOIN {$memberRoleTable} mr ON mr.id = mug.role " .
                                "AND mr.authority <= acl.authority " .
                                "GROUP BY acl.target, acl.principal, acl.authority, acl.policy";
                        $bindings = array(
                            ':principal' => $this->get('id'),
                            ':context' => $context
                        );
                        $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
                        if ($query->stmt && $query->stmt->execute()) {
                            while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                                $this->_attributes[$context][$target][$row['target']][] = array(
                                    'principal' => $row['principal'],
                                    'authority' => $row['authority'],
                                    'policy' => $row['data'] ? xPDO :: fromJSON($row['data'], true) : array(),
                                );
                            }
                        }
                        break;
                    default :
                        break;
                }
            } else {
                switch ($target) {
                    case 'modAccessResourceGroup' :
                        $legacyDocGroups = array();
                        $sql = "SELECT acl.target, acl.principal, 0 AS authority, acl.policy, p.data FROM {$accessTable} acl " .
                                "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                                "WHERE acl.principal_class = 'modUserGroup' " .
                                "AND acl.principal = 0 " .
                                "AND (acl.context_key = :context OR acl.context_key IS NULL OR acl.context_key = '') " .
                                "GROUP BY acl.target, acl.principal, acl.authority, acl.policy";
                        $bindings = array(
                            ':context' => $context
                        );
                        $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
                        if ($query->stmt && $query->stmt->execute()) {
                            while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                                $this->_attributes[$context][$target][$row['target']][] = array(
                                    'principal' => 0,
                                    'authority' => $row['authority'],
                                    'policy' => $row['data'] ? xPDO :: fromJSON($row['data'], true) : array(),
                                );
                                $legacyDocGroups[$row['target']]= $row['target'];
                            }
                        }
                        $_SESSION[$context . 'Docgroups']= array_values($legacyDocGroups);
                        break;
                    case 'modAccessContext' :
                        $sql = "SELECT acl.target, acl.principal, 0 AS authority, acl.policy, p.data FROM {$accessTable} acl " .
                                "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                                "WHERE acl.principal_class = 'modUserGroup' " .
                                "AND acl.principal = 0 " .
                                "GROUP BY acl.target, acl.principal, acl.authority, acl.policy";
                        $query = new xPDOCriteria($this->xpdo, $sql);
                        if ($query->stmt && $query->stmt->execute()) {
                            while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                                $this->_attributes[$context][$target][$row['target']][] = array(
                                    'principal' => 0,
                                    'authority' => $row['authority'],
                                    'policy' => $row['data'] ? xPDO :: fromJSON($row['data'], true) : array(),
                                );
                            }
                        }
                        break;
                    case 'modAccessElement' :
                        $sql = "SELECT acl.target, acl.principal, 0 AS authority, acl.policy, p.data FROM {$accessTable} acl " .
                                "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                                "WHERE acl.principal_class = 'modUserGroup' " .
                                "AND acl.principal = 0 " .
                                "AND (acl.context_key = :context OR acl.context_key IS NULL OR acl.context_key = '') " .
                                "GROUP BY acl.target, acl.principal, acl.authority, acl.policy";
                        $bindings = array(
                            ':context' => $context
                        );
                        $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
                        if ($query->stmt && $query->stmt->execute()) {
                            while ($row = $query->stmt->fetch(PDO::FETCH_ASSOC)) {
                                $this->_attributes[$context][$target][$row['target']][] = array(
                                    'principal' => 0,
                                    'authority' => $row['authority'],
                                    'policy' => $row['data'] ? xPDO :: fromJSON($row['data'], true) : array(),
                                );
                            }
                        }
                        break;
                    default :
                        break;
                }
            }
            if (!isset($this->_attributes[$context][$target])) {
                $this->_attributes[$context][$target] = array();
            }
            $_SESSION["modx.user.{$this->id}.attributes"] = $this->_attributes;
        }
    }

    /**
     * Determines if this user is authenticated in a specific context.
     *
     * Separate session contexts can allow users to login/out of specific sub-sites
     * individually (or in collections).
     *
     * @access public
     * @param string $sessionContext The context to determine if the user is
     * authenticated in.
     * @return boolean true, if the user is authenticated in the specified
     * context, false otherwise.
     */
    public function isAuthenticated($sessionContext= 'web') {
        $isAuthenticated= false;
        if (!empty ($sessionContext) && is_string($sessionContext)) {
            if ($this->hasSessionContext($sessionContext)) {
                $isAuthenticated= true;
            }
            elseif (isset ($_SESSION[$sessionContext . "Validated"])) {
                $isAuthenticated= ($_SESSION[$sessionContext . "Validated"] == 1);
            }
        }
        return $isAuthenticated;
    }

    /**
     * Ends a user session completely, including all contexts.
     *
     * @access public
     */
    public function endSession() {
        $this->getSessionContexts();
        if (!empty($this->sessionContexts)) {
            $this->removeSessionContext($this->sessionContexts);
        }
        @ session_destroy();
    }

    /**
     * Change the user password.
     *
     * @access public
     * @param string $newPassword Password to set.
     * @param string $oldPassword Current password for validation.
     * @return boolean Indicates if password was successfully changed.
     * @todo Add support for configurable password encoding.
     */
    public function changePassword($newPassword, $oldPassword) {
        $changed= false;
        if ($this->get('password') === md5($oldPassword)) {
            if (!empty ($newPassword)) {
                $this->set('password', $newPassword);
                $changed= $this->save();
                $contextKey= $this->xpdo->context->get('key');
                switch ($contextKey) {
                    case 'web':
                        $this->xpdo->invokeEvent('OnWebChangePassword', array (
                            'userid' => $this->get('id'),
                            'username' => $this->get('username'),
                            'userpassword' => $newPassword,
                        ));
                        break;
                    case 'mgr':
                        $this->xpdo->invokeEvent('OnManagerChangePassword', array (
                            'userid' => $this->get('id'),
                            'username' => $this->get('username'),
                            'userpassword' => $newPassword,
                        ));
                        break;
                    default:
                        $this->xpdo->invokeEvent('OnUserChangePassword', array (
                            'userid' => $this->get('id'),
                            'username' => $this->get('username'),
                            'userpassword' => $newPassword,
                        ));
                        break;
                }
            }
        }
        return $changed;
    }

    /**
     * Returns an array of user session context keys.
     *
     * @access public
     * @return array An array of session contexts.
     */
    public function getSessionContexts() {
        if (!is_array($this->sessionContexts) || empty ($this->sessionContexts)) {
            $this->sessionContexts= array ();
            if (isset ($_SESSION['modx.user.contextTokens'])) {
                $this->sessionContexts= $_SESSION['modx.user.contextTokens'];
            } else {
                $legacyContextTokens= array ();
                if (isset ($_SESSION["webValidated"]) && $_SESSION["webValidated"] == 1) {
                    $legacyContextTokens[]= 'web';
                }
                if (isset ($_SESSION["mgrValidated"]) && $_SESSION["mgrValidated"] == 1) {
                    $legacyContextTokens[]= 'mgr';
                }
                foreach ($legacyContextTokens as $token)
                    $this->addSessionContext($token);
            }
            $_SESSION['modx.user.contextTokens']= $this->sessionContexts;
        }
        return $this->sessionContexts;
    }

    /**
     * Adds a new context to the user session context array.
     *
     * @access public
     * @param string $context The context to add to the user session.
     */
    public function addSessionContext($context) {
        $this->getSessionContexts();
        $regenerated= false;
        if (version_compare(XPDO_PHP_VERSION, '4.3.2', '>=')) {
            if (version_compare(XPDO_PHP_VERSION, '5.1.0', '>=')) {
                session_regenerate_id(true);
                $regenerated= true;
            } else {
                $oldSessionId= session_id();
                session_regenerate_id();
                if (!version_compare(XPDO_PHP_VERSION, '4.3.3','>=')) {
                    $cookiePath = $this->xpdo->getOption('session_cookie_path',null,$this->xpdo->getOption('base_path'));

                    $cookieExpiration= 0;
                    $cookieLifetime= intval($this->xpdo->getOption('session_cookie_lifetime',null,(@ini_get('session.cookie_lifetime'))));

                    if (isset ($_SESSION['modx.' . $context . '.session.cookie.lifetime']) && is_numeric($_SESSION['modx.' . $context . '.session.cookie.lifetime'])) {
                        $cookieLifetime= intval($_SESSION['modx.' . $context . '.session.cookie.lifetime']);
                    }
                    if ($cookieLifetime) {
                        $cookieExpiration= time() + $cookieLifetime;
                    }
                    setcookie(session_name(), session_id(), $cookieExpiration, $cookiePath);
                }
                /* HACK: [PHP < 5.1.0] Removing the old session object from the database so it can't be hijacked. */
                if ($oldSession= $this->xpdo->getObject('modSession', array ('id' => $oldSessionId), false)) {
                    $oldSession->remove();
                }
                $regenerated= true;
            }
        }

        $this->getOne('Profile');
        if ($this->Profile && $this->Profile instanceof modUserProfile) {
            $ua= & $this->Profile;
            if ($context == 'web') {
                $_SESSION['webShortname']= $this->get('username');
                $_SESSION['webFullname']= $ua->get('fullname');
                $_SESSION['webEmail']= $ua->get('email');
                $_SESSION['webValidated']= 1;
                $_SESSION['webInternalKey']= $this->get('id');
                $_SESSION['webValid']= base64_encode($this->get('password'));
                $_SESSION['webUser']= base64_encode($this->get('username'));
                $_SESSION['webFailedlogins']= $ua->get('failedlogincount');
                $_SESSION['webLastlogin']= $ua->get('lastlogin');
                $_SESSION['webnrlogins']= $ua->get('logincount');
                $_SESSION['webUserGroupNames']= '';
            }
            elseif ($context == 'mgr') {
                $_SESSION['usertype']= 'manager';
                $_SESSION['mgrShortname']= $this->get('username');
                $_SESSION['mgrFullname']= $ua->get('fullname');
                $_SESSION['mgrEmail']= $ua->get('email');
                $_SESSION['mgrValidated']= 1;
                $_SESSION['mgrInternalKey']= $this->get('id');
                $_SESSION['mgrFailedlogins']= $ua->get('failedlogincount');
                $_SESSION['mgrLastlogin']= $ua->get('lastlogin');
                $_SESSION['mgrLogincount']= $ua->get('logincount');
            }
            if ($ua && !isset ($this->sessionContexts[$context]) || $this->sessionContexts[$context] != $this->get('id')) {
                $ua->set('failedlogincount', 0);
                $ua->set('logincount', $ua->logincount + 1);
                $ua->set('lastlogin', $ua->thislogin);
                $ua->set('thislogin', time());
                $ua->set('sessionid', session_id());
                $ua->save();
            }
        }
        $this->sessionContexts[$context]= $this->get('id');
        $_SESSION['modx.user.contextTokens']= $this->sessionContexts;
    }

    /**
     * Removes a user session context.
     *
     * @access public
     * @param string|array $context The context key or an array of context keys.
     */
    public function removeSessionContext($context) {
        if ($this->getSessionContexts()) {
            $contextToken= array ();
            if (is_array($context)) {
                foreach ($context as $ctx) {
                    $contextToken[$ctx]= $this->get('id');
                    $this->removeSessionContextVars($ctx);
                }
            } else {
                $contextToken[$context]= $this->get('id');
                $this->removeSessionContextVars($context);
            }
            $this->sessionContexts= array_diff_assoc($this->sessionContexts, $contextToken);
            $_SESSION['modx.user.contextTokens']= $this->sessionContexts;
        }
    }

    /**
     * Removes the session vars associated with a specific context.
     *
     * @access public
     * @param string $context The context key.
     */
    public function removeSessionContextVars($context) {
        if (is_string($context) && !empty ($context)) {
            switch ($context) {
                case 'web' :
                    unset ($_SESSION['webShortname']);
                    unset ($_SESSION['webFullname']);
                    unset ($_SESSION['webEmail']);
                    unset ($_SESSION['webValidated']);
                    unset ($_SESSION['webInternalKey']);
                    unset ($_SESSION['webValid']);
                    unset ($_SESSION['webUser']);
                    unset ($_SESSION['webFailedlogins']);
                    unset ($_SESSION['webLastlogin']);
                    unset ($_SESSION['webnrlogins']);
                    unset ($_SESSION['webUsrConfigSet']);
                    unset ($_SESSION['webUserGroupNames']);
                    unset ($_SESSION['webDocgroups']);
                    break;

                case 'mgr' :
                    unset ($_SESSION['usertype']);
                    unset ($_SESSION['mgrShortname']);
                    unset ($_SESSION['mgrFullname']);
                    unset ($_SESSION['mgrEmail']);
                    unset ($_SESSION['mgrValidated']);
                    unset ($_SESSION['mgrInternalKey']);
                    unset ($_SESSION['mgrFailedlogins']);
                    unset ($_SESSION['mgrLastlogin']);
                    unset ($_SESSION['mgrLogincount']);
                    unset ($_SESSION['mgrRole']);
                    unset ($_SESSION['mgrDocgroups']);
                    break;

                default :
                    break;
            }
            $this->removeSessionCookie($context);
        }
    }

    /**
     * Removes a session cookie for a user.
     *
     * TODO Implement this.
     *
     * @access public
     * @param string $context The context to remove.
     */
    public function removeSessionCookie($context) {

    }

    /**
     * Checks if the user has a specific session context.
     *
     * @access public
     * @param mixed $context Either a name of a context or array of context
     * names to check against.
     * @return boolean True if the user has the context(s) specified.
     */
    public function hasSessionContext($context) {
        $hasContext= false;
        if ($this->getSessionContexts()) {
            $contextTokens= array ();
            if (is_array($context)) {
                foreach ($context as $ctx) {
                    $contextTokens[$ctx]= $this->get('id');
                }
            }
            elseif (is_string($context)) {
                $contextTokens[$context]= $this->get('id');
            }
            $hasContext= (count(array_intersect_assoc($contextTokens, $this->sessionContexts)) == count($contextTokens));
        }
        return $hasContext;
    }

    /**
     * Gets a count of {@link modUserMessage} objects ascribed to the user.
     *
     * @access public
     * @param mixed $read
     * @return integer The number of messages.
     */
    public function countMessages($read = '') {
        if ($read == 'read') { $read = 1; } elseif ($read == 'unread') { $read = 0; }
        $criteria= array ('recipient' => $this->get('id'));
        if ($read) {
            $criteria['messageread']= $read;
        }
        return $this->xpdo->getCount('modUserMessage', $criteria);
    }

    /**
     * Gets all user settings in array format.
     *
     * @access public
     * @return array A key -> value array of settings.
     */
    public function getSettings() {
        $settings = array();
        $uss = $this->getMany('modUserSetting');
        foreach ($uss as $us) {
            $settings[$us->get('key')] = $us->get('value');
        }
        $this->settings = $settings;
        return $settings;
    }

    /**
     * Gets all Resource Groups this user is assigned to. This may not work in
     * the new model.
     *
     * @deprecated
     * @todo refactor this to actually work.
     * @access public
     * @return array An array of Resource Group names.
     */
    public function getResourceGroups() {
        $resourceGroups= array ();
        if (isset($_SESSION["modx.user.{$this->id}.resourceGroups"])) {
            $resourceGroups= $_SESSION["modx.user.{$this->id}.resourceGroups"];
        } else {
            if ($memberships= $this->getMany('UserGroupMembers')) {
                foreach ($memberships as $membership) {
                    if ($documentGroupAccess= $membership->getMany('UserGroupResourceGroups')) {
                        foreach ($documentGroupAccess as $dga) {
                            $resourceGroups[]= $dga->get('documentgroup');
                        }
                    }
                }
            }
            $_SESSION["modx.user.{$this->id}.resourceGroups"]= $resourceGroups;
        }
        return $resourceGroups;
    }

    /**
     * Gets all the User Group IDs of the groups this user belongs to.
     *
     * @access public
     * @return array An array of User Group IDs.
     */
    public function getUserGroups() {
        $groups= array();
        if (isset($_SESSION["modx.user.{$this->id}.userGroups"])) {
            $groups= $_SESSION["modx.user.{$this->id}.userGroups"];
        } else {
            $memberGroups= $this->xpdo->getCollectionGraph('modUserGroup', '{"UserGroupMembers":{}}', array('`UserGroupMembers`.member' => $this->get('id')));
            if ($memberGroups) {
                foreach ($memberGroups as $group) $groups[]= $group->get('id');
            }
            $_SESSION["modx.user.{$this->id}.userGroups"]= $groups;
        }
        return $groups;
    }

    /**
     * Gets all the User Group names of the groups this user belongs to.
     *
     * @access public
     * @return array An array of User Group names.
     */
    public function getUserGroupNames() {
        $groupNames= array();
        if (isset($_SESSION["modx.user.{$this->id}.userGroupNames"])) {
            $groupNames= $_SESSION["modx.user.{$this->id}.userGroupNames"];
        } else {
            $memberGroups= $this->xpdo->getCollectionGraph('modUserGroup', '{"UserGroupMembers":{}}', array('`UserGroupMembers`.member' => $this->get('id')));
            if ($memberGroups) {
                foreach ($memberGroups as $group) $groupNames[]= $group->get('name');
            }
            $_SESSION["modx.user.{$this->id}.userGroupNames"]= $groupNames;
        }
        return $groupNames;
    }

    /**
     * States whether a user is a member of a group or groups. You may specify
     * either a string name of the group, or an array of names.
     *
     * @access public
     * @param string/array $groups Either a string of a group name or an array
     * of names.
     * @param boolean $matchAll If true, requires the user to be a member of all
     * the groups specified. If false, the user can be a member of only one to
     * pass. Defaults to false.
     * @return boolean True if the user is a member of any of the groups
     * specified.
     */
    public function isMember($groups,$matchAll = false) {
        $isMember= false;
        $groupNames= $this->getUserGroupNames();
        if ($groupNames) {
            if (is_array($groups)) {
                if ($matchAll) {
                    $matches= array_diff($groups, $groupNames);
                    $isMember= empty($matches);
                } else {
                    $matches= array_intersect($groups, $groupNames);
                    $isMember= !empty($matches);
                }
            } else {
                $isMember= (array_search($groups, $groupNames) !== false);
            }
        }
        return $isMember;
    }

    /**
     * Join a User Group, and optionally assign a Role.
     *
     * @access public
     * @param mixed $groupId Either the name or ID of the User Group to join.
     * @param mixed $roleId Optional. Either the name or ID of the Role to
     * assign to for the group.
     * @return boolean True if successful.
     */
    public function joinGroup($groupId,$roleId = null) {
        $joined = false;

        $groupPk = is_string($groupId) ? array('name' => $groupId) : $groupId;
        $usergroup = $this->xpdo->getObject('modUserGroup',$groupPk);
        if ($usergroup == null) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'User Group not found with key: '.$groupId);
            return $joined;
        }

        if (!empty($roleId)) {
            $rolePk = is_string($roleId) ? array('name' => $roleId) : $roleId;
            $role = $this->xpdo->getObject('modUserGroupRole',$rolePk);
            if ($role == null) {
                $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'Role not found with key: '.$role);
                return $joined;
            }
        }

        $member = $this->xpdo->newObject('modUserGroupMember');
        $member->set('member',$this->get('id'));
        $member->set('user_group',$usergroup->get('id'));
        if (!empty($role)) {
            $member->set('role',$role->get('id'));
        }
        $saved = $member->save();
        if (!$saved) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'An unknown error occurred preventing adding the User to the User Group.');
        }
        return $saved;
    }

    /**
     * Removes the User from the specified User Group.
     *
     * @access public
     * @param mixed $groupId Either the name or ID of the User Group to join.
     * @return boolean True if successful.
     */
    public function leaveGroup($groupId) {
        $left = false;

        $c = $this->xpdo->newQuery('modUserGroupMember');
        $c->innerJoin('modUserGroup','UserGroup');
        $c->where(array('member' => $this->get('id')));

        $fk = is_string($groupId) ? 'name' : 'id';
        $c->where(array(
            'member' => $this->get('id'),
            'UserGroup.'.$fk => $groupId,
        ));

        $member = $this->xpdo->getObject('modUserGroupMember',$c);
        if ($member == false) {
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR,'User could not leave group with key "'.$groupId.'" because the User was not a part of that group.');
            return $left;
        }

        $left = $member->remove();
        return $left;
    }
}