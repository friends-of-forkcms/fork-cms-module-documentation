<?php

namespace Backend\Modules\Documentation\Actions;

use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Form as BackendForm;

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
    public function execute(): void
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
        $this->form = new BackendForm('settings');

        // Where do we get our documentation from?
        $organization = $this->form->addText('organization', $this->get('fork.settings')->get($this->url->getModule(), 'organization'));
        $organization->setAttribute('placeholder', 'Github user/org');

        $repository = $this->form->addText('repository', $this->get('fork.settings')->get($this->url->getModule(), 'repository'));
        $repository->setAttribute('placeholder', 'Github repo name');

        $repository = $this->form->addText('subfolder', $this->get('fork.settings')->get($this->url->getModule(), 'subfolder'));
        $repository->setAttribute('placeholder', 'Optional subfolder in the repository');

        // Authenticate?
        $this->form->addPassword('auth_token', $this->get('fork.settings')->get($this->url->getModule(), 'auth_token'));
    }

    /**
     * Validates the settings form
     */
    private function validateForm()
    {
        if ($this->form->isSubmitted() && $this->form->isCorrect()) {
            $organization = strtolower($this->form->getField('organization')->getValue());
            $repository = strtolower($this->form->getField('repository')->getValue());
            $subfolder = strtolower($this->form->getField('subfolder')->getValue());
            $authToken = $this->form->getField('auth_token')->getValue();

            $this->get('fork.settings')->set($this->url->getModule(), 'organization', $organization);
            $this->get('fork.settings')->set($this->url->getModule(), 'repository', $repository);
            $this->get('fork.settings')->set($this->url->getModule(), 'subfolder', $subfolder);
            $this->get('fork.settings')->set($this->url->getModule(), 'auth_token', $authToken);

            // Redirect to the settings page
            $this->redirect(BackendModel::createUrlForAction('Settings') . '&report=saved');
        }
    }
}
