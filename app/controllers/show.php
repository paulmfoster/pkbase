<?php

class show extends controller
{
    function __construct()
    {
        global $cfg, $pkb;
        $this->cfg = $cfg;
        $this->pkb = $pkb;
    }

    function page($page = NULL)
    {
        if (is_null($page)) {
            redirect('index.php');
        }
        $upage = $this->pkb->unhide($page);

        $this->sidebar = $this->pkb->get_sidebar($upage);
        $this->title = $this->pkb->get_title($upage);
        $this->page = $upage;
        $content = $this->pkb->get_content($upage);
        $this->view('show.view.php', ['content' => $content]);
    }
}
