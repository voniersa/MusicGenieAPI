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
            $nameCodeData = $this->apiConnector->getMySupportedLanguages();

            
            if($_GET['Artist'] != NULL && $_GET['SongTitle'] != NULL)
            {
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
            }
            
            $data = $this->apiConnector->getOurAPI($_GET['Artist'], $_GET['SongTitle'], $fromLang, $toLang);
            
            return $this->projector->showMainPage($_GET['Artist'], $_GET['SongTitle'], $data, $fromLang, $toLang, $nameCodeData);
        }
        
        function showFourZeroFour()
        {
            return $this->projector->showFourZeroFour();
        }
    }

?>