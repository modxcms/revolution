<?php

/**
 * @package modx
 * @subpackage dashboard
 */
class modDashboardWidgetWelcome extends modDashboardWidgetInterface
{
    /** @var modX $modx */
    public $modx;

    /**
     * @return string
     * @throws Exception
     */
    public function render()
    {
        $data = [
            'background' => $this->getBackgroundImage(),
            'title' => $this->modx->lexicon('welcome_' . $this->getCurrentDayPart(), [
                'name' => $this->modx->getUser()->Profile->get('fullname') ?: $this->modx->getUser()->get('username')
            ])
        ];

        $this->modx->getService('smarty', 'smarty.modSmarty');
        foreach ($data as $key => $value) {
            $this->modx->smarty->assign($key, $value);
        }

        return $this->modx->smarty->fetch('dashboard/welcome.tpl');
    }

    /**
     * @return string
     */
    public function process()
    {
        $this->cssBlockClass = 'widget--welcome';

        return parent::process();
    }

    /**
     * This will set the background image for the welcome widget.
     *
     * @return string
     */
    protected function getBackgroundImage()
    {
        $background = $this->modx->getOption('widget_welcome_background', null, 'templates/default/images/widgets/default-welcome-bg.jpg');
        $background = str_replace('{manager_theme}', $this->modx->getOption('manager_theme', null, 'default'), $background);

        return $background;
    }

    /**
     * Returns a string for the current part of the day.
     *
     * @return string
     */
    protected function getCurrentDayPart()
    {
        $hour = date('H');

        $timeOfDay = 'evening';
        if ($hour > 6 && $hour <= 11) {
            $timeOfDay = 'morning';
        } else if($hour > 11 && $hour <= 16) {
            $timeOfDay = 'afternoon';
        }

        return $timeOfDay;
    }
}

return 'modDashboardWidgetWelcome';
