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
            $html = file_get_contents("../templates/pageTemplate.html");
            
            //Translator API
            $nameCodeData = $this->apiConnector->getPossibleLanguages();

            foreach($nameCodeData AS $ncd)
            {
                $html = str_replace("{nextLanguageOption}", "{languageOption} {nextLanguageOption}", $html);
                $html = str_replace("{languageOption}", "<option>".$ncd."</option>", $html);
            }
            $html = str_replace("{nextLanguageOption}", "", $html);

            if($_POST['Interpret'] != NULL && $_POST['Songtitel'] != NULL)
            {
                //Song Information API
                $songInformationData = $this->apiConnector->getSongInformation($_POST['Interpret'], $_POST['Songtitel']);

                //Lyrics API
                $lyrics = $this->apiConnector->getLyricsData($_POST['Interpret'], $_POST['Songtitel']);

                //Translator API
                $i = 0;
                foreach($nameCodeData AS $ncd)
                {
                    if($ncd == $_POST['fromLanguage'])
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
                    if($ncd == $_POST['toLanguage'])
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

                $html = str_replace("{lyrics}", $lyrics, $html);
                $html = str_replace("{translatedLyrics}", $response, $html);
                $html = str_replace("{interpret}", "<strong>Interpret:</strong> <a href='{urlInterpret}'>".$songInformationData['track']['artist']['name']."</a>", $html);
                $html = str_replace("{songTitle}", "<strong>Songtitel:</strong> <a href='{urlTrack}'>".$songInformationData['track']['name']."</a>", $html);
                $html = str_replace("{albumTitle}", "<strong>Album:</strong> <a href='{urlAlbum}'>".$songInformationData['track']['album']['title']."</a>", $html);
                $html = str_replace("{listeners}", "<strong>Hörer:</strong> ".$songInformationData['track']['listeners'], $html);
                $html = str_replace("{playCount}", "<strong>Abgespielt:</strong> ".$songInformationData['track']['playcount'], $html);
                $html = str_replace("{urlTrack}", $songInformationData['track']['url'], $html);
                $html = str_replace("{urlInterpret}", $songInformationData['track']['artist']['url'], $html);
                $html = str_replace("{urlAlbum}", $songInformationData['track']['album']['url'], $html);
                $html = str_replace("{albumCover}", "<img src='".$songInformationData['track']['album']['image'][3]['#text']."' alt='Album Cover'>", $html);
                $html = str_replace("{tags}", "<strong>Tags:</strong> {tags}", $html);
                foreach($songInformationData['track']['toptags']['tag'] AS $tags)
                {
                    $html = str_replace("{tags}", "<a href='".$tags['url']."'>".$tags['name']."</a>, {tags}", $html);
                }
                $html = str_replace(", {tags}", "", $html);
            } else {
                $html = str_replace("{lyrics}", "Gib bitte einen Songtitel und einen Interpreten ein!", $html);
                $html = str_replace("{translatedLyrics}", "", $html);
                $html = str_replace("{interpret}", "", $html);
                $html = str_replace("{songTitle}", "", $html);
                $html = str_replace("{albumTitle}", "", $html);
                $html = str_replace("{listeners}", "", $html);
                $html = str_replace("{playCount}", "", $html);
                $html = str_replace("{albumCover}", "", $html);
                $html = str_replace("{tags}", "", $html);
            }
            echo $html;
        }
        
        function showFourZeroFour()
        {
            return file_get_contents("../templates/pageNotFoundTemplate.html");
        }
    }