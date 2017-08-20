<?php

namespace Backend\Modules\Documentation\Installer;

use Backend\Core\Installer\ModuleInstaller;
use Common\ModuleExtraType;

/**
 * Installer for the documentation module
 *
 * @author Jesse Dobbelaere <jesse@dobbelae.re>
 */
class Installer extends ModuleInstaller
{
    public function install(): void
    {
        $this->addModule('Documentation');
        $this->importLocale(__DIR__ . '/Data/locale.xml');
        $this->configureSettings();
        $this->configureBackendNavigation();
        $this->configureBackendRights();
        $this->insertModuleBlockAndWidgets();
    }

    private function configureBackendNavigation(): void
    {
        // Set navigation for "Modules"
        $navigationSettingsId = $this->setNavigation(null, 'Settings');
        $navigationModulesId = $this->setNavigation($navigationSettingsId, 'Modules');
        $this->setNavigation(
            $navigationModulesId,
            $this->getModule(),
            'documentation/settings'
        );
    }

    private function configureSettings(): void
    {
        $this->setSetting($this->getModule(), 'doc_repository', '');
    }

    private function configureBackendRights(): void
    {
        $this->setModuleRights(1, $this->getModule());
        $this->setActionRights(1, $this->getModule(), 'Settings');
    }

    private function insertModuleBlockAndWidgets(): void
    {
        $this->insertExtra($this->getModule(), ModuleExtraType::block(), 'Documentation');
        $this->insertExtra($this->getModule(), ModuleExtraType::widget(), 'NavigationList', 'NavigationList');
    }
}
