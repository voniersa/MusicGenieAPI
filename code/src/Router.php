<?php

    class Router
    {
        private $page;

        public function __construct(Page $page)
        {
            $this->page = $page;
        }

        public function getPageFromUrl(String $url)
        {
            switch ($url)
            {
                case '/':
                    return $this->page->run();
            }
            return $this->page->showFourZeroFour();
        }
    }
    
?>