<?php

namespace MODX\Revolution;

use xPDO\Om\xPDOSimpleObject;
use xPDO\xPDO;

/**
 * Class modDashboard
 *
 * @property string                        $name
 * @property string                        $description
 * @property boolean                       $hide_trees
 * @property boolean                       $customizable
 *
 * @property modDashboardWidgetPlacement[] $Placements
 *
 * @package MODX\Revolution
 */
class modDashboard extends xPDOSimpleObject
{
    /**
     * Get the default MODX dashboard
     *
     * @param xPDO $xpdo A reference to an xPDO instance
     *
     * @return modDashboard|null
     */
    public static function getDefaultDashboard(xPDO &$xpdo)
    {
        /** @var modDashboard $defaultDashboard */
        $defaultDashboard = $xpdo->getObject(modDashboard::class, [
            'id' => 1,
        ]);
        if (empty($defaultDashboard)) {
            $defaultDashboard = $xpdo->getObject(modDashboard::class, [
                'name' => 'Default',
            ]);
        }

        return $defaultDashboard;
    }


    /**
     * Override xPDOObject::remove() to revert to the default dashboard any user groups using this Dashboard
     *
     * @param array $ancestors
     *
     * @return boolean
     */
    public function remove(array $ancestors = [])
    {
        $dashboardId = $this->get('id');
        $removed = parent::remove($ancestors);
        if ($removed) {
            $defaultDashboard = modDashboard::getDefaultDashboard($this->xpdo);
            if (empty($defaultDashboard)) {
                /** @var modDashboard $defaultDashboard */
                $defaultDashboard = $this->xpdo->newObject(modDashboard::class);
                $defaultDashboard->set('id', 0);
            }
            $userGroups = $this->xpdo->getCollection(modUserGroup::class, [
                'dashboard' => $dashboardId,
            ]);
            /** @var modUserGroup $userGroup */
            foreach ($userGroups as $userGroup) {
                $userGroup->set('dashboard', $defaultDashboard->get('id'));
                $userGroup->save();
            }
        }

        return $removed;
    }


    /**
     * Render the Dashboard
     *
     * @param modManagerController $controller
     * @param modUser              $user
     *
     * @return string
     */
    public function render(modManagerController $controller, $user = null)
    {
        if (!$user) {
            /** @noinspection PhpUndefinedFieldInspection */
            $user = $this->xpdo->user;
        }
        $output = [];
        $where = [
            'dashboard' => $this->get('id'),
        ];
        // Check customizable
        if ($this->get('customizable')) {
            $where['user'] = $user->get('id');
            if (!$this->xpdo->getCount(modDashboardWidgetPlacement::class, $where)) {
                $this->addUserWidgets($user);
            }
        } else {
            $where['user'] = 0;
        }

        // Get widgets
        $c = $this->xpdo->newQuery(modDashboardWidgetPlacement::class, $where);
        $c->sortby($this->xpdo->escape('rank'), 'ASC');
        if ($placements = $this->xpdo->getIterator(modDashboardWidgetPlacement::class, $c)) {
            /** @var modDashboardWidgetPlacement $placement */
            foreach ($placements as $placement) {
                /** @var modDashboardWidget $widget */
                if ($widget = $placement->getOne('Widget')) {
                    if ($permission = $widget->get('permission')) {
                        if (method_exists($this->xpdo, 'hasPermission') && !$this->xpdo->hasPermission($permission)) {
                            continue;
                        }
                    }
                    if ($this->get('customizable')) {
                        $widget->set('size', $placement->get('size'));
                    }
                    $widget->set('customizable', $this->get('customizable'));
                    if ($content = $widget->getContent($controller)) {
                        $output[] = $content;
                    }
                }
            }
        }

        return implode("\n", $output);
    }


    /**
     * @param int  $user
     * @param bool $force
     */
    public function sortWidgets($user = 0, $force = false)
    {
        if (!$force) {
            // Check if need to update ranks
            $c = $this->xpdo->newQuery(modDashboardWidgetPlacement::class, [
                'dashboard' => $this->id,
                'user' => $user,
            ]);
            $c->groupby($this->xpdo->escape('rank'));
            $c->select('COUNT(rank) as idx');
            $c->sortby('idx', 'DESC');
            $c->limit(1);
            if ($c->prepare() && $c->stmt->execute()) {
                if ($c->stmt->fetchColumn() == 1) {
                    return;
                }
            }
        }

        // Update ranks
        $c = $this->xpdo->newQuery(modDashboardWidgetPlacement::class, [
            'dashboard' => $this->id,
            'user' => $user,
        ]);
        $c->sortby('rank ASC, widget', 'ASC');
        $idx = 0;
        $items = $this->xpdo->getIterator(modDashboardWidgetPlacement::class, $c);
        /** @var modDashboardWidgetPlacement $item */
        foreach ($items as $item) {
            $item->set('rank', $idx++);
            $item->save();
        }
    }


    /**
     * @param modUser $user
     */
    protected function addUserWidgets(modUser $user)
    {
        $c = $this->xpdo->newQuery(modDashboardWidgetPlacement::class);
        $c->where([
            'dashboard' => $this->get('id'),
            'user' => 0,
        ]);
        $placements = $this->getMany('Placements', $c);
        /** @var modDashboardWidgetPlacement $placement */
        foreach ($placements as $placement) {
            $new = $this->xpdo->newObject(modDashboardWidgetPlacement::class);
            $new->fromArray($placement->toArray(), '', true);
            $new->set('user', $user->get('id'));
            $new->save();
        }
    }
}
