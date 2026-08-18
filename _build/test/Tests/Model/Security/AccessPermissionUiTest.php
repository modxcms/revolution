<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
*/
namespace MODX\Revolution\Tests\Model\Security;

use MODX\Revolution\MODxTestCase;
use MODX\Revolution\Processors\Security\Group\Create as UserGroupCreate;
use ReflectionClass;

/**
 * Guards ACL permission keys for issue #14497.
 *
 * @group Model
 * @group Security
 * @group AccessPermissions
 */
class AccessPermissionUiTest extends MODxTestCase
{
    public function testUserGroupCreateProcessorUsesUsergroupNew(): void
    {
        $defaults = (new ReflectionClass(UserGroupCreate::class))->getDefaultProperties();

        $this->assertSame('usergroup_new', $defaults['permission']);
    }

    public function testUserGroupAclGridCreateUsesUsergroupNew(): void
    {
        $source = $this->readManagerAsset('assets/modext/widgets/security/modx.grid.user.group.base.js');

        $this->assertStringContainsString(
            "this.setUserCanCreate(['usergroup_new', 'usergroup_save']);",
            $source
        );
        $this->assertStringNotContainsString(
            "setUserCanCreate(['usergroup_create'",
            $source
        );
        $this->assertStringContainsString(
            'hidden: !MODx.perm.usergroup_new || !MODx.perm.usergroup_save',
            $source
        );
        $this->assertStringContainsString('getCreateAclButton', $source);
        $this->assertStringNotContainsString('hideCreateAclButtonWithoutPermission', $source);
    }

    /**
     * @dataProvider providerAclGridCreateButtons
     */
    public function testAclGridsUseCreateAclButtonFactory(string $relativePath): void
    {
        $source = $this->readManagerAsset($relativePath);

        $this->assertStringContainsString('this.getCreateAclButton(_(', $source);
    }

    public function providerAclGridCreateButtons(): array
    {
        $dir = 'assets/modext/widgets/security/';

        return [
            'context' => [$dir . 'modx.grid.user.group.context.js'],
            'category' => [$dir . 'modx.grid.user.group.category.js'],
            'namespace' => [$dir . 'modx.grid.user.group.namespace.js'],
            'source' => [$dir . 'modx.grid.user.group.source.js'],
            'resource' => [$dir . 'modx.grid.user.group.resource.js'],
        ];
    }

    public function testUserGroupTreeGatesCreateAndAddUser(): void
    {
        $source = $this->readManagerAsset('assets/modext/widgets/security/modx.tree.user.group.js');

        $this->assertStringContainsString(
            'hidden: !MODx.perm.usergroup_new || !MODx.perm.usergroup_save',
            $source
        );
        $this->assertStringContainsString(
            'MODx.perm.usergroup_user_edit && MODx.perm.usergroup_user_list',
            $source
        );
        $this->assertStringContainsString(
            'MODx.perm.usergroup_new && MODx.perm.usergroup_save && ui.hasClass(\'pcreate\')',
            $source
        );
    }

    public function testUserGroupUsersGridAddRequiresListPermission(): void
    {
        $source = $this->readManagerAsset('assets/modext/widgets/security/modx.panel.user.group.js');

        $this->assertStringContainsString(
            '!this.userCanEditGroupUsers || !MODx.perm.usergroup_user_list',
            $source
        );
    }

    public function testDeleteRoleLexiconDoesNotSayRemove(): void
    {
        $lexicon = file_get_contents(MODX_CORE_PATH . 'lexicon/en/permissions.inc.php');

        $this->assertNotFalse($lexicon);
        $this->assertStringContainsString(
            "\$_lang['perm.delete_role_desc'] = 'To delete any Roles.';",
            $lexicon
        );
        $this->assertStringNotContainsString(
            "\$_lang['perm.delete_role_desc'] = 'To delete or remove any Roles.';",
            $lexicon
        );
    }

    private function readManagerAsset(string $relativePath): string
    {
        $path = MODX_MANAGER_PATH . $relativePath;
        $this->assertFileExists($path);

        $source = file_get_contents($path);
        $this->assertNotFalse($source);

        return $source;
    }
}
