<?php

    class Router
    {
        private $page;
        private $apiCreator;

        public function __construct(Page $page, APICreator $apiCreator)
        {
            $this->page = $page;
            $this->apiCreator = $apiCreator;
        }

        public function getPageFromUrl(String $url)
        {
            if($_GET['Artist'] == null)
            {
                $interpret = "";
            } else {
                $interpret = $_GET['Artist'];
            }
            if($_GET['SongTitle'] == null)
            {
                $songTitle = "";
            } else {
                $songTitle = $_GET['SongTitle'];
            }
            if($_GET['fromLanguage'] == null)
            {
                $fromLang = "";
            } else {
                $fromLang = $_GET['fromLanguage'];
            }
            if($_GET['toLanguage'] == null)
            {
                $toLang = "";
            } else {
                $toLang = $_GET['toLanguage'];
            }
            
            switch ($url)
            {
                case '/':
                case '/?Artist='.$interpret.'&SongTitle='.$songTitle.'&fromLanguage='.$fromLang.'&toLanguage='.$toLang:
                    return $this->page->run();
                case '/api/?Artist='.$interpret.'&SongTitle='.$songTitle.'&fromLanguage='.$fromLang.'&toLanguage='.$toLang:
                    return $this->apiCreator->getAllInformation();
                case '/api/availableLanguages':
                    return $this->apiCreator->getAvailableLanguages();
            }
            if(strpos($url,"/api/")!==false)
            {
                return $this->apiCreator->getErrorMessage();
            }
            
            return $this->page->showFourZeroFour();
        }
    }
    
?>