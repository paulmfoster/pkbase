<?php

/**
 * Default controller
 */

class welcome extends controller
{
    public $cfg, $pkb;
    public $title, $page, $sidebar;

    function __construct()
    {
        global $cfg, $pkb;
        $this->cfg = $cfg;
        $this->pkb = $pkb;
    }

    function index($tree = NULL)
    {
        if (is_null($tree)) {
            $sidebar = $this->pkb->get_sidebar($this->cfg['content_dir']);
        }
        else {
            $sidebar = $this->pkb->get_sidebar($this->pkb->unhide($tree));
        }

        $pd = load('Parsedown');
        $readme = file_get_contents('README.md');
        $content = $pd->text($readme);

        $this->title = 'Welcome';
        $this->page = '';
        $this->sidebar = $sidebar;

        $this->view('index.view.php', ['content' => $content]);
    }
}
