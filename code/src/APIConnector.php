<?php
    class APIConnector
    {
        private $ip = "100.115.92.196";
        
        public function getOurAPI($interpret, $songTitle, $fromLang, $toLang)
        {
            $url = "http://".$this->ip."/api/?Artist=".urlencode($interpret)."&SongTitle=".urlencode($songTitle)."&fromLanguage=".urlencode($fromLang)."&toLanguage=".urlencode($toLang);
            $json = file_get_contents($url);
            $data = json_decode($json, true);
            return $data;
        }
        
        public function getMySupportedLanguages()
        {
            $url = "http://".$this->ip."/api/availableLanguages";
            $json = file_get_contents($url);
            $data = json_decode($json, true);
            return $data['languages'];
        }
        
        public function getLyricsData($interpret, $songTitle)
        {
            $url = "https://api.lyrics.ovh/v1/".urlencode($interpret)."/".urlencode($songTitle);
            $json = file_get_contents($url);
            $data = json_decode($json, true);
            return $data['lyrics'];
        }
        
        public function getSongInformation($interpret, $songTitle)
        {
            $songInformation = "http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=0a26133ca26a243bb9029bfcbbf0058f&artist=".urlencode($interpret)."&track=".urlencode( $songTitle)."&format=json";
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