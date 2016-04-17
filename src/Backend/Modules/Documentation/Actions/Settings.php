<?php

namespace Backend\Modules\Documentation\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Engine\Language as BL;

/**
 * This is the settings-action, it will display a form to set general blog settings
 *
 * @author Jesse Dobbelaere <jesse@dobbelae.re>
 */
class Settings extends BackendBaseActionEdit
{
    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();

        $this->loadForm();
        $this->validateForm();

        $this->parse();
        $this->display();
    }

    /**
     * Loads the settings form
     */
    private function loadForm()
    {
        $this->frm = new BackendForm('settings');

        // Where do we get our documentation from?
        $organization = $this->frm->addText(
            'organization',
            $this->get('fork.settings')->get($this->URL->getModule(), 'organization')
        );
        $organization->setAttribute('placeholder', 'Github user/org');

        $repository = $this->frm->addText(
            'repository',
            $this->get('fork.settings')->get($this->URL->getModule(), 'repository')
        );
        $repository->setAttribute('placeholder', 'Github repo name');

        // Authenticate?
        $this->frm->addPassword(
            'auth_token',
            $this->get('fork.settings')->get($this->URL->getModule(), 'auth_token')
        );
    }

    /**
     * Validates the settings form
     */
    private function validateForm()
    {
        if ($this->frm->isSubmitted()) {
            if ($this->frm->isCorrect()) {
                // set our settings
                $organization = strtolower($this->frm->getField('organization')->getValue());
                $repository = strtolower($this->frm->getField('repository')->getValue());
                $authToken = $this->frm->getField('auth_token')->getValue();

                $this->get('fork.settings')->set($this->URL->getModule(), 'organization', $organization);
                $this->get('fork.settings')->set($this->URL->getModule(), 'repository', $repository);
                $this->get('fork.settings')->set($this->URL->getModule(), 'auth_token', $authToken);

                // Trigger event
                BackendModel::triggerEvent($this->getModule(), 'after_saved_settings');

                // Redirect to the settings page
                $this->redirect(BackendModel::createURLForAction('Settings') . '&report=saved');
            }
        }
    }
}
