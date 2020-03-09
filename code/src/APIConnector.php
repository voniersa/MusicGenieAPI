<?php
    class APIConnector
    {
        public function getLyricsData($interpret, $songTitle)
        {
            $url = "https://api.lyrics.ovh/v1/".str_replace(" ", "%20", $interpret)."/".str_replace(" ", "%20", $songTitle);
            $json = file_get_contents($url);
            $data = json_decode($json, true);
            return $data['lyrics'];
        }
        
        public function getSongInformation($interpret, $songTitle)
        {
            $songInformation = "http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=0a26133ca26a243bb9029bfcbbf0058f&artist=".str_replace(" ", "%20", $interpret)."&track=".str_replace(" ", "%20", $songTitle."&format=json");
            $songInformationJson = file_get_contents($songInformation);
            return json_decode($songInformationJson, true);
        }
        
        public function getPossibleLanguages()
        {
            $languageNameCodes = "http://api.whatsmate.net/v1/translation/supported-codes";
            $languageNameCodesJson = file_get_contents($languageNameCodes);
            return json_decode($languageNameCodesJson, true);
        }
        
        public function getTranslatedLyrics($fromLang, $toLang, $lyrics)
        {
            $postData = array(
                'fromLang' => $fromLang,
                'toLang' => $toLang,
                'text' => $lyrics
            );

            $headers = array(
                'Content-Type: application/json',
                'X-WM-CLIENT-ID: FREE_TRIAL_ACCOUNT',
                'X-WM-CLIENT-SECRET: PUBLIC_SECRET'
            );

            $url = 'http://api.whatsmate.net/v1/translation/translate';
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

            $response = curl_exec($ch);
            curl_close($ch);
            
            return $response;
        }
    }
?>