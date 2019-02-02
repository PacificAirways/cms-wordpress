<?php namespace Pacificairways\Cms\Wordpress;

use GuzzleHttp\ClientInterface;
use Pacificairways\Interfaces\Cms\PageInterface;
use Pacificairways\Interfaces\Cms\PageServiceInterface;

class PageService implements PageServiceInterface
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * PageService constructor.
     *
     * @param ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $slug
     *
     * @return PageInterface
     */
    public function getBySlug($slug)
    {
        return $this->httpClient->request('GET', $slug);
    }
}