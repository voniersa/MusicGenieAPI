<?php
    class Projector {
        
        public function showMainPage($nameCodeData, $interpret, $songTitle, $lyrics, $fromLang, $toLang, $translatedLyrics, $songInformationData)
        {
            $html = file_get_contents("../templates/pageTemplate.html");
            
            foreach($nameCodeData AS $ncd)
            {
                $html = str_replace("{nextLanguageOption}", "{languageOption} {nextLanguageOption}", $html);
                $html = str_replace("{languageOption}", "<option>".$ncd."</option>", $html);
            }
            $html = str_replace("{nextLanguageOption}", "", $html);
            
            if($interpret != NULL && $songTitle != NULL && $fromLang != NULL && $toLang != NULL)
            {
                $lyrics = str_replace("\n", "<br>", $lyrics);
                $translatedLyrics = str_replace("\n", "<br>", $translatedLyrics);
                $html = str_replace("{lyrics}", "<h1>Lyrics:</h1>".$lyrics, $html);
                $html = str_replace("{translatedLyrics}", "<h1>Translated Lyrics:</h1>".$translatedLyrics, $html);
                $html = str_replace("{interpret}", "<div id='informationBox'><strong>Interpret:</strong> <a href='{urlInterpret}'>".$songInformationData['track']['artist']['name']."</a>", $html);
                $html = str_replace("{artistValue}", $interpret, $html);
                $html = str_replace("{songTitleValue}", $songTitle, $html);
                $html = str_replace("{songTitle}", "<strong>Songtitle:</strong> <a href='{urlTrack}'>".$songInformationData['track']['name']."</a>", $html);
                $html = str_replace("{albumTitle}", "<strong>Album:</strong> <a href='{urlAlbum}'>".$songInformationData['track']['album']['title']."</a>", $html);
                $html = str_replace("{listeners}", "<strong>Total Listeners:</strong> ".$songInformationData['track']['listeners'], $html);
                $html = str_replace("{playCount}", "<strong>Total Played:</strong> ".$songInformationData['track']['playcount'], $html);
                $html = str_replace("{urlTrack}", $songInformationData['track']['url'], $html);
                $html = str_replace("{urlInterpret}", $songInformationData['track']['artist']['url'], $html);
                $html = str_replace("{urlAlbum}", $songInformationData['track']['album']['url'], $html);
                $html = str_replace("{albumCover}", "<div id='pictureBox'><img id='albumCover' src='".$songInformationData['track']['album']['image'][3]['#text']."' alt='Album Cover'></div>", $html);
                $html = str_replace("{tags}", "<strong>Tags:</strong> {tags}", $html);
                
                foreach($songInformationData['track']['toptags']['tag'] AS $tags)
                {
                    $html = str_replace("{tags}", "<a href='".$tags['url']."'>".$tags['name']."</a>, {tags}", $html);
                }
                $html = str_replace(", {tags}", "</div>", $html);
                
                $html = str_replace("{linkToMainAPI}", "<a class='apiLink' href='/api/?Artist=".urlencode($interpret)."&SongTitle=".urlencode($songTitle)."&fromLanguage=".urlencode($fromLang)."&toLanguage=".urlencode($toLang)."'><img class='linkIcon' src='linkIcon.png' alt=''> ".$_SERVER['SERVER_NAME']."/api/?Artist=".urlencode($interpret)."&SongTitle=".urlencode($songTitle)."&fromLanguage=".urlencode($fromLang)."&toLanguage=".urlencode($toLang)."</a>", $html);
                $html = str_replace("{linkToAPILanguages}", "<a class='apiLink' href='/api/availableLanguages'><img class='linkIcon' src='linkIcon.png' alt=''> Get all available languages</a>", $html);
            } else {
                $html = str_replace("<div class='box'>{lyrics}</div>", "Type in an artist and a songtitle and select the languages!", $html);
                $html = str_replace("<div class='box'>{translatedLyrics}</div>", "", $html);
                $html = str_replace("{linkToMainAPI}", "", $html);
                $html = str_replace("{linkToAPILanguages}", "", $html);
                $html = str_replace("{interpret}", "", $html);
                $html = str_replace("{songTitle}", "", $html);
                $html = str_replace("{artistValue}", "", $html);
                $html = str_replace("{songTitleValue}", "", $html);
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