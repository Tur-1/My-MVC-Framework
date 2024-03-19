<?php

namespace TurFramework\Pagination;

class Paginator
{

    /**
     * All of the data being paginated.
     *
     */
    protected $data;

    /**
     * The number of data to be shown per page.
     *
     * @var int
     */
    protected $perPage;

    /**
     * The current page being "viewed".
     *
     * @var int
     */
    protected $currentPage;

    /**
     * The base path to assign to all URLs.
     *
     * @var string
     */
    protected $path = '/';
    /**
     *  
     * 
     */
    protected $total;

    /**
     * The query string variable used to store the page.
     *
     * @var string
     */
    protected $pageName = 'page';



    public function __construct($data, $total, $perPage, $currentPage = null, $pageName = 'page')
    {
        $this->data = $data;
        $this->perPage = $perPage;
        $this->total = $total;
        $this->path = rtrim($this->path, '/');
        $this->currentPage = $currentPage;
        $this->pageName = $pageName;
    }
}
