<?php namespace Pacificairways\Cms\Wordpress;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Pacificairways\Interfaces\Cms\Exception\ContentNotFoundException;
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
     * @return Page
     * @throws ContentNotFoundException
     */
    public function getBySlug($slug)
    {
        try {
            $response = $this->httpClient
                ->request('GET', '?slug=' . $slug)
                ->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw new ContentNotFoundException('Content not found: ' . $e->getMessage());
        }

        $content = json_decode($response, true);
        if (count($content) === 0 || !isset($content[0]['content']['rendered'])) {
            throw new ContentNotFoundException('Content not found');
        }

        return Page::create($content[0]['content']['rendered']);
    }
}