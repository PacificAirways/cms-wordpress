<?php

use Pacificairways\Interfaces\Cms\Exception\ContentNotFoundException;
use PHPUnit\Framework\TestCase;

class PageServiceTest extends TestCase
{
    /** @var \Mockery\MockInterface */
    protected $guzzle;

    public function setUp()
    {
        $this->guzzle = Mockery::mock(\GuzzleHttp\Client::class);
    }

    public function testGetBySlug()
    {
        $content = json_encode([
            ['content' => ['rendered' => 'random content']]
        ]);
        $streamMock = Mockery::mock(\Psr\Http\Message\StreamInterface::class);
        $streamMock->shouldReceive('getContents')->andReturn($content);

        $responseMock = Mockery::mock(\Psr\Http\Message\ResponseInterface::class);
        $responseMock->shouldReceive('getBody')->andReturn($streamMock);

        $this->guzzle->shouldReceive('request')->andReturn($responseMock);
        $pageService = new \Pacificairways\Cms\Wordpress\PageService($this->guzzle);
        $page = $pageService->getBySlug('test');
        $this->assertEquals('random content', $page->getContent());
    }

    public function testGetBySlugGuzzleException()
    {
        $this->expectException(ContentNotFoundException::class);
        $this->guzzle->shouldReceive('request')->andThrow(new \GuzzleHttp\Exception\TransferException());
        $pageService = new \Pacificairways\Cms\Wordpress\PageService($this->guzzle);
        $pageService->getBySlug('test');
    }

    public function testGetBySlugNoContentFound()
    {
        $content = json_encode([
            ['content' => []]
        ]);
        $streamMock = Mockery::mock(\Psr\Http\Message\StreamInterface::class);
        $streamMock->shouldReceive('getContents')->andReturn($content);

        $responseMock = Mockery::mock(\Psr\Http\Message\ResponseInterface::class);
        $responseMock->shouldReceive('getBody')->andReturn($streamMock);

        $this->guzzle->shouldReceive('request')->andReturn($responseMock);
        $pageService = new \Pacificairways\Cms\Wordpress\PageService($this->guzzle);
        $this->expectException(ContentNotFoundException::class);
        $page = $pageService->getBySlug('test');
        $this->assertEquals('random content', $page->getContent());
    }

    public function testGetBySlugNoRenderedField()
    {
        $content = json_encode([
            ['content' => ['randomInvalidKey' => 'random content']]
        ]);

        $streamMock = Mockery::mock(\Psr\Http\Message\StreamInterface::class);
        $streamMock->shouldReceive('getContents')->andReturn($content);

        $responseMock = Mockery::mock(\Psr\Http\Message\ResponseInterface::class);
        $responseMock->shouldReceive('getBody')->andReturn($streamMock);

        $this->guzzle->shouldReceive('request')->andReturn($responseMock);
        $pageService = new \Pacificairways\Cms\Wordpress\PageService($this->guzzle);

        $this->expectException(ContentNotFoundException::class);
        $page = $pageService->getBySlug('test');
    }
}