<?php

class search extends controller
{
    function __construct()
    {
        global $cfg, $pkb;
        $this->cfg = $cfg;
        $this->pkb = $pkb;
    }

    function index()
    {
        redirect('index.php');
    }

    function page()
    {
        $search_for = fork('search_query', 'P', 'index.php');

        $results = $this->pkb->get_search_results($search_for);

        $this->page = '';
        $this->title = 'Search Results';
        $this->sidebar = $this->pkb->get_sidebar($this->cfg['content_dir']);
        $this->view('search.view.php', ['results' => $results]);
    }
}

