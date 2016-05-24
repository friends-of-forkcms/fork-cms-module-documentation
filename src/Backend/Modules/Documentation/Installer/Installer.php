<?php

namespace Backend\Modules\Documentation\Installer;

use Backend\Core\Installer\ModuleInstaller;

/**
 * Installer for the documentation module
 *
 * @author Jesse Dobbelaere <jesse@dobbelae.re>
 */
class Installer extends ModuleInstaller
{
    /**
     * Install the module
     */
    public function install()
    {
        // Add Documentation as a module
        $this->addModule('Documentation');

        // Install locale
        $this->importLocale(dirname(__FILE__) . '/Data/locale.xml');

        // Configure rights
        $this->setModuleRights(1, 'Documentation');
        $this->setActionRights(1, 'Documentation', 'Settings');

        // Settings
        $this->setSetting('Documentation', 'doc_repository', '');

        // Settings navigation
        $navigationSettingsId = $this->setNavigation(null, 'Settings');
        $navigationModulesId = $this->setNavigation($navigationSettingsId, 'Modules');
        $this->setNavigation($navigationModulesId, 'Documentation', 'documentation/settings');

        // Insert module block & widgets
        $this->insertExtra('Documentation', 'block', 'Documentation');
        $this->insertExtra('Documentation', 'widget', 'GuidesList', 'GuidesList');
        $this->insertExtra('Documentation', 'block', 'WebhookCacheClear', 'WebhookCacheClear');
    }
}
