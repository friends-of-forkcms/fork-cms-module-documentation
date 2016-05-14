<?php

namespace Frontend\Modules\Documentation\Engine;

use Frontend\Core\Engine\Navigation as FrontendNavigation;
use Frontend\Modules\Documentation\Resources\Navigation;
use Frontend\Modules\Documentation\Resources\NavigationItem;
use Github\Api\Markdown;
use Github\Api\Repo;
use Github\Client;
use Github\HttpClient\CachedHttpClient;
use Frontend\Core\Engine\Model as FrontendModel;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use MatthiasMullie\Scrapbook\Adapters\Flysystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class GithubDocumentation
 * @package Frontend\Modules\Documentation\Engine
 */
class GithubDocumentation implements RepositoryInterface
{
    /**
     * @var GithubDocumentation
     */
    private static $instance;

    /**
     * @var CachedHttpClient The Github client instance
     */
    private $client;

    /**
     * @var string
     */
    private $organization;

    /**
     * @var string
     */
    private $repository;

    /**
     * @var Navigation
     */
    private $navigation;

    /**
     * @var Flysystem
     */
    private $cache;

    /**
     * List of excluded files/folders to parse
     */
    const EXCLUSIONS = [
        '.gitignore',
        'README.md',
        'assets',
    ];

    /**
     * Returns the Singleton instance of this class
     *
     * @return GithubDocumentation The Singleton instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the singleton via the new operator from outside of
     * this class.
     */
    protected function __construct()
    {
        // Set client
        $this->client = new Client(
            new CachedHttpClient(array('cache_dir' => FRONTEND_CACHE_PATH . '/Documentation'))
        );

        // Settings
        $this->organization = FrontendModel::get('fork.settings')->get('Documentation', 'organization');
        $this->repository = FrontendModel::get('fork.settings')->get('Documentation', 'repository');
        $token = FrontendModel::get('fork.settings')->get('Documentation', 'auth_token');

        // Authenticate
        if (isset($token)) {
            $this->client->authenticate($token, null, Client::AUTH_HTTP_TOKEN);
        }

        // Init cache
        $adapter = new Local(FRONTEND_CACHE_PATH . '/Documentation/', LOCK_EX);
        $filesystem = new Filesystem($adapter);
        $this->cache = new Flysystem($filesystem);

        // Build/set the navigation
        $this->navigation = $this->getNavigation();
    }

    /**
     * Fetch and build the navigation tree for our documentation
     *
     * @return Navigation
     */
    private function buildNavigation()
    {
        // Get contents from the Github repository
        /** @var Repo $githubRepoApi */
        $githubRepoApi = $this->client->api('repo');
        $repoContents = $githubRepoApi->contents()->show($this->organization, $this->repository);

        // Build a navigation tree from the Github repository
        $navigation = $this->buildTree($repoContents);

        return $navigation;
    }

    /**
     * Build a navigation out of documentation elements (file/dir) from the repository.
     *
     * @param array $elements
     * @param string $parentSlug
     * @param NavigationItem|null $parent
     * @return Navigation
     */
    private function buildTree(array $elements, $parentSlug = '', $parent = null)
    {
        // Create our Navigation object that will function as a collection of items.
        $navigation = new Navigation();

        /** @var Repo $githubRepoApi */
        $githubRepoApi = $this->client->api('repo');

        // Loop every file/dir in the repository.
        foreach ($elements as $element) {
            // Don't process files/folders that are in the exclusions list, skip to the next foreach item
            if (in_array($element['name'], self::EXCLUSIONS)) {
                continue;
            }

            // Create the navigation item
            $name = DocumentationHelper::cleanupName($element['name']);
            $urlSlug = DocumentationHelper::filenameToUrl($name);
            $fullUrl = FrontendNavigation::getURLForBlock('Documentation', 'Detail') . '/' . $parentSlug . $urlSlug;
            $navItem = new NavigationItem(
                DocumentationHelper::filenameToLabel($name),
                $element['name'],
                $element['type'],
                $element['path'],
                $urlSlug,
                $fullUrl
            );

            // Set the parent navigationItem
            if ($parent !== null) {
                $navItem->setParent($parent);
            }

            // Set the edit-on-github URL
            if ($navItem->isFile()) {
                $navItem->setEditUrl($this->getArticleEditLink($navItem));
            }

            // If directory, we fetch the children items
            if ($element['type'] === 'dir') {
                $repoContents = $githubRepoApi->contents()->show(
                    $this->organization,
                    $this->repository,
                    $element['path']
                );
                $parentSlugUpdated = $parentSlug .
                    DocumentationHelper::filenameToUrl(DocumentationHelper::cleanupName($element['name'])) . '/';
                $children = $this->buildTree($repoContents, $parentSlugUpdated, $navItem);
                if (!empty($children->getItems())) {
                    $navItem->setChildren($children);
                }
            }

            // Add the navigation item to our navigation
            $navigation->addItem($navItem);
        }

        return $navigation;
    }

    /**
     * Get the content of a Github file in our repository
     *
     * @param string $filePath
     * @return string
     */
    private function getContent($filePath)
    {
        // Make URL to the file in our repository
        $organisationName = urlencode($this->organization);
        $repositoryName = urlencode($this->repository);
        $url = "repos/$organisationName/$repositoryName/contents/$filePath";

        // Fetch the response
        $response = $this->client->getHttpClient()->get($url)->json();

        return $response['content'];
    }

    /**
     * Convert base64 content to html output using a markdown parser.
     *
     * @param string $content Base64 content
     * @return string HTML output
     */
    private function parseBase64ToMarkdown($content)
    {
        /** @var Markdown $markdownApi */
        $markdownApi = $this->client->api('markdown');
        return $markdownApi->render(base64_decode($content));
    }

    /**
     * Fetch the content of the article from the NavigationItem.
     *
     * @param NavigationItem $navigationItem
     * @return string
     */
    public function getArticleData(NavigationItem $navigationItem)
    {
        // Get original filepath
        $originalFilePath = $navigationItem->getOriginalFilePath();

        // Get content from filename
        $articleBody = $this->getContent($originalFilePath);
        $articleBodyHtml = $this->parseBase64ToMarkdown($articleBody);

        return $articleBodyHtml;
    }

    /**
     * Fetch the navigation and cache it
     * @return Navigation
     */
    public function getNavigation()
    {
        $cachedNavigation = $this->cache->get('navigation');

        // If no cached navigation is available, generate it.
        if (empty($cachedNavigation)) {
            $navigation = $this->buildNavigation();
            $this->cache->set('navigation', $navigation);
            return $navigation;
        } else {
            return $cachedNavigation;
        }
    }

    /**
     * @param NavigationItem $navigationItem
     * @return string
     */
    private function getArticleEditLink(NavigationItem $navigationItem)
    {
        if ($navigationItem->isDir()) {
            return null;
        }
        
        $slug = $navigationItem->getOriginalFilePath();
        $editLink = "https://github.com/{$this->organization}/{$this->repository}/edit/master/{$slug}";
        return $editLink;
    }

    /**
     * @param Request $request
     * @throws BadRequestHttpException If content is empty or json is not valid.
     * @return bool
     */
    public function onWebhookPostReceive(Request $request)
    {
        $dataJson = $request->getContent();
        $data = json_decode($dataJson, true);

        // Validate request
        if (empty($dataJson)) {
            throw new BadRequestHttpException("Content is empty");
        }
        if ($request->get('content-type') !== 'application/json' && $data === null) {
            throw new BadRequestHttpException("Content is not valid json");
        }

        // Did we receive a push/commit event from github? Clear the documentation cache
        if ($request->headers->get('x-github-event') === 'push') {
            Model::clearCache();
            return true;
        }

        return false;
    }
}
