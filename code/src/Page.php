<?php

    class Page
    {
        private $projector;
        private $apiConnector;

        public function __construct(Projector $projector, APIConnector $apiConnector)
        {
            $this->projector = $projector;
            $this->apiConnector = $apiConnector;
        }
        
        function run()
        {
            //Translator API
            $nameCodeData = $this->apiConnector->getPossibleLanguages();

            
            if($_GET['Artist'] != NULL && $_GET['SongTitle'] != NULL)
            {
                //Song Information API
                $songInformationData = $this->apiConnector->getSongInformation($_GET['Artist'], $_GET['SongTitle']);

                //Lyrics API
                $lyrics = $this->apiConnector->getLyricsData($_GET['Artist'], $_GET['SongTitle']);

                //Translator API
                $i = 0;
                foreach($nameCodeData AS $ncd)
                {
                    if($ncd == $_GET['fromLanguage'])
                    {
                        $j = 0;
                        foreach(array_keys($nameCodeData) AS $key)
                        {
                            if($j == $i)
                            {
                                $fromLang = $key;
                                break;
                            }
                            $j++;
                        }
                        break;
                    }
                    $i++;
                }

                $i = 0;
                foreach($nameCodeData AS $ncd)
                {
                    if($ncd == $_GET['toLanguage'])
                    {
                        $j = 0;
                        foreach(array_keys($nameCodeData) AS $key)
                        {
                            if($j == $i)
                            {
                                $toLang = $key;
                                break;
                            }
                            $j++;
                        }
                        break;
                    }
                    $i++;
                }

                $response = $this->apiConnector->getTranslatedLyrics($fromLang, $toLang, $lyrics);
            }
            return $this->projector->showMainPage($nameCodeData, $_GET['Artist'], $_GET['SongTitle'], $lyrics, $formLang, $toLang, $response, $songInformationData);
        }
        
        function showFourZeroFour()
        {
            return $this->projector->showFourZeroFour();
        }
    }

?>