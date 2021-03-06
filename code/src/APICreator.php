<?php

    class APICreator
    {
        private $apiConnector;

        public function __construct(APIConnector $apiConnector)
        {
            $this->apiConnector = $apiConnector;
        }
        
        public function getAvailableLanguages()
        {
            $languages = $this->apiConnector->getPossibleLanguages();
            header("Content-type:application/json");
            return json_encode(array('languages' => $languages));
        }
        
        public function getErrorMessage($gotSongInformation)
        {            
            header("Content-type:application/json");
            
            if(!$gotSongInformation)
            {
                return json_encode(array('error' => 'No track found with this specification'));
            }
            if($_GET['Artist'] == "")
            {
                return json_encode(array('error' => 'The API needs to recieve an artist'));
            }
            if($_GET['SongTitle'] == "")
            {
                return json_encode(array('error' => 'The API needs to recieve a songname'));
            }
            if($_GET['fromLang'] == "")
            {
                return json_encode(array('error' => 'The API needs to recieve the language of the song. If you don\'t know which languages are available just go to /api/availableLanguages'));
            }
            if($_GET['toLang'] == "")
            {
                return json_encode(array('error' => 'The API needs to recieve the return language. If you don\'t know which languages are available just go to /api/availableLanguages'));
            }
        }
        
        public function getAllInformationExceptTranslation()
        {
            $songInformationData = $this->apiConnector->getSongInformation($_GET['Artist'], $_GET['SongTitle']);
            if($songInformationData == "'".$_GET['SongTitle']."' by '".$_GET['Artist']."' not found, please try anything else.")
            {
                return $this->getErrorMessage(false);
            }
            
            $lyrics = $this->apiConnector->getLyricsData($_GET['Artist'], $_GET['SongTitle']);
            header("Content-type:application/json");
            $array = array(
                "track" => array(
                    "songTitle" => $songInformationData['track']['name'],
                    "url" => $songInformationData['track']['url'],
                    "artist" => array(
                        "name" => $songInformationData['track']['artist']['name'],
                        "url" => $songInformationData['track']['artist']['url'],
                    ),
                    "album" => array(
                        "name" => $songInformationData['track']['album']['title'],
                        "url" => $songInformationData['track']['album']['url'],
                        "cover" => $songInformationData['track']['album']['image'][3]['#text']
                    ),
                    "lyrics" => array(
                        "originalLyrics" => $lyrics
                    ),
                    "listeners" => $songInformationData['track']['listeners'],
                    "playCount" => $songInformationData['track']['playcount'],
                    "tags" => $songInformationData['track']['toptags']['tag']
                )
            );
            return json_encode($array);
        }
        
        public function getAllInformation()
        {
            $songInformationData = $this->apiConnector->getSongInformation($_GET['Artist'], $_GET['SongTitle']);
            if($songInformationData == "'".$_GET['SongTitle']."' by '".$_GET['Artist']."' not found, please try anything else.")
            {
                return $this->getErrorMessage(false);
            }
            
            $lyrics = $this->apiConnector->getLyricsData($_GET['Artist'], $_GET['SongTitle']);
            $translatedLyrics = $this->apiConnector->getTranslatedLyrics($_GET['fromLanguage'], $_GET['toLanguage'], $lyrics);
            header("Content-type:application/json");
            $array = array(
                "track" => array(
                    "songTitle" => $songInformationData['track']['name'],
                    "url" => $songInformationData['track']['url'],
                    "artist" => array(
                        "name" => $songInformationData['track']['artist']['name'],
                        "url" => $songInformationData['track']['artist']['url'],
                    ),
                    "album" => array(
                        "name" => $songInformationData['track']['album']['title'],
                        "url" => $songInformationData['track']['album']['url'],
                        "cover" => $songInformationData['track']['album']['image'][3]['#text']
                    ),
                    "lyrics" => array(
                        "originalLyrics" => $lyrics,
                        "translatedLyrics" => $translatedLyrics
                    ),
                    "listeners" => $songInformationData['track']['listeners'],
                    "playCount" => $songInformationData['track']['playcount'],
                    "tags" => $songInformationData['track']['toptags']['tag']
                )
            );
            return json_encode($array);
        }
    }

?>