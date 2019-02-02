<?php namespace Pacificairways\Cms\Wordpress;

use Pacificairways\Interfaces\Cms\PageInterface;

class Page implements PageInterface
{
    /** @var string */
    private $content;

    /** @var array */
    private $customFields;

    public function __construct($content, $customFields = [])
    {
        $this->content = $content;
        $this->customFields = $customFields;
    }

    /**
     * @param string $content
     * @param array  $customFields
     *
     * @return Page
     */
    public static function create($content, $customFields = [])
    {
        return new self($content, $customFields);
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getCustomFields()
    {
        return $this->customFields;
    }
}