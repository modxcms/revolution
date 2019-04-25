<?php

/**
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetShortcuts extends modDashboardWidgetInterface
{
    /** @var modX $modx */
    public $modx;

    /**
     * An array containing all possible shortcuts.
     *
     * @var array
     */
    public $shortcuts = [
        'clear_cache' => [
            'shortcuts' =>  [
                'mac' => 'CMD + SHIFT + U',
                'win' => 'CTRL + SHIFT + U'
            ],
            'permission' => 'empty_cache'
        ],
        'toggle_panel' => [
            'shortcuts' => [
                'mac' => 'CMD + SHIFT + H',
                'win' => 'CTRL + SHIFT + H'
            ]
        ],
        'preview_resource' => [
            'shortcuts' => [
                'mac' => 'CMD + OPTION + P',
                'win' => 'CTRL + ALT + P'
            ]
        ],
        'save_resource' => [
            'shortcuts' => [
                'mac' => 'CMD + S',
                'win' => 'CTRL + ALT + S'
            ]
        ]
    ];

    /**
     * @return string
     * @throws Exception
     */
    public function render()
    {
        $operatingSystem = strpos(getenv('HTTP_USER_AGENT'), 'Mac') !== false ? 'mac' : 'win';

        $data['shortcuts'] = [];
        foreach ($this->shortcuts as $key => $shortcut) {
            if (isset($shortcut['permission'])) {
                if (!$this->modx->hasPermission($shortcut['permission'])) {
                    continue;
                }
            }

            $data['shortcuts'][] = [
                'description' => $this->modx->lexicon('widget_shortcuts_' . $key),
                'shortcut'    => $shortcut['shortcuts'][$operatingSystem]
            ];
        }

        $this->modx->getService('smarty', 'smarty.modSmarty');
        foreach ($data as $key => $value) {
            $this->modx->smarty->assign($key, $value);
        }

        return $this->modx->smarty->fetch('dashboard/shortcuts.tpl');
    }
}

return 'modDashboardWidgetShortcuts';
