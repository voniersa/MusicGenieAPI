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
                $interpret = urlencode($_GET['Artist']);
            }
            if($_GET['SongTitle'] == null)
            {
                $songTitle = "";
            } else {
                $songTitle = urlencode($_GET['SongTitle']);
            }
            if($_GET['fromLanguage'] == null)
            {
                $fromLang = "";
            } else {
                $fromLang = urlencode($_GET['fromLanguage']);
            }
            if($_GET['toLanguage'] == null)
            {
                $toLang = "";
            } else {
                $toLang = urlencode($_GET['toLanguage']);
            }
            
            switch ($url)
            {
                case '/':
                case '/?Artist='.$interpret:
                case '/?SongTitle='.$songTitle:
                case '/?fromLanguage='.$fromLang:
                case '/?toLanguage='.$toLang:
                case '/?Artist='.$interpret.'&SongTitle='.$songTitle:
                case '/?Artist='.$interpret.'&fromLanguage='.$fromLang:
                case '/?Artist='.$interpret.'&toLanguage='.$toLang:
                case '/?SongTitle='.$songTitle.'&fromLanguage='.$fromLang:
                case '/?SongTitle='.$songTitle.'&toLanguage='.$toLang:
                case '/?fromLanguage='.$fromLang.'&toLanguage='.$toLang:
                case '/?SongTitle='.$songTitle.'&fromLanguage='.$fromLang.'&toLanguage='.$toLang:
                case '/?Artist='.$interpret.'&fromLanguage='.$fromLang.'&toLanguage='.$toLang:
                case '/?Artist='.$interpret.'&SongTitle='.$songTitle.'&toLanguage='.$toLang:
                case '/?Artist='.$interpret.'&SongTitle='.$songTitle.'&fromLanguage='.$fromLang:
                case '/?Artist='.$interpret.'&SongTitle='.$songTitle.'&fromLanguage='.$fromLang.'&toLanguage='.$toLang:
                    return $this->page->run();
                case '/api/?Artist='.$interpret.'&SongTitle='.$songTitle:
                    return $this->apiCreator->getAllInformationExceptTranslation();
                case '/api/?Artist='.$interpret.'&SongTitle='.$songTitle.'&fromLanguage='.$fromLang.'&toLanguage='.$toLang:
                    return $this->apiCreator->getAllInformation();
                case '/api/availableLanguages':
                    return $this->apiCreator->getAvailableLanguages();
            }
            
            if(strpos($url,"/api/")!==false)
            {
                return $this->apiCreator->getErrorMessage();
            }
            
            http_response_code(404);
            return $this->page->showFourZeroFour();
        }
    }
    
?>