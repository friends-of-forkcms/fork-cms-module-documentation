<?php

namespace Frontend\Modules\Documentation\Actions;

use Exception;
use Frontend\Core\Engine\Base\Block as FrontendBaseBlock;
use Frontend\Modules\Documentation\Engine\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator;

/**
 * WebhookCacheClear
 *
 * Post-receive action for Github webhooks.
 * Go to Github repository > Settings > Webhooks > Add webhook.
 * Enter this action url as payload url (/webhook-cache-clear).
 * Choose application/json content-type and just use the push event.
 *
 * @author Jesse Dobbelaere <jesse@dobbelae.re>
 */
class WebhookCacheClear extends FrontendBaseBlock
{
    /**
     * Execute the action
     */
    public function execute()
    {
        parent::execute();
        $this->loadTemplate();

        // Fetch the request
        /** @var Request $request */
        $request = $this->get('request');

        // Clear the documentation cache after a 'push' webhook was received
        $cacheCleared = Model::onWebhookPostReceive($request);

        if (!$cacheCleared) {
            throw new Exception('Cache clear failed');
        }
    }
}
