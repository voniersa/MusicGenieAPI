<?php
    class Projector {
        
        public function showMainPage($nameCodeData, $interpret, $songTitle, $lyrics, $formLang, $toLang, $translatedLyrics, $songInformationData)
        {
            $html = file_get_contents("../templates/pageTemplate.html");
            
            foreach($nameCodeData AS $ncd)
            {
                $html = str_replace("{nextLanguageOption}", "{languageOption} {nextLanguageOption}", $html);
                $html = str_replace("{languageOption}", "<option>".$ncd."</option>", $html);
            }
            $html = str_replace("{nextLanguageOption}", "", $html);
            
            if($interpret != NULL && $songTitle != NULL)
            {
                $lyrics = str_replace("\n", "<br>", $lyrics);
                $translatedLyrics = str_replace("\n", "<br>", $translatedLyrics);
                $html = str_replace("{lyrics}", "<h1>Lyrics:</h1>".$lyrics, $html);
                $html = str_replace("{translatedLyrics}", "<h1>Lyrics:</h1>".$translatedLyrics, $html);
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
            return $html;
        }
        
        public function showFourZeroFour()
        {
            return file_get_contents("../templates/pageNotFoundTemplate.html");
        }
    }
?>