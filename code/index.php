<?php

    $html = file_get_contents("page.html");
    $languageNameCodes = "http://api.whatsmate.net/v1/translation/supported-codes";
    $languageNameCodesJson = file_get_contents($languageNameCodes);
    $nameCodeData = json_decode($languageNameCodesJson, true);
    
    foreach($nameCodeData AS $ncd)
    {
        $html = str_replace("{nextLanguageOption}", "{languageOption} {nextLanguageOption}", $html);
        $html = str_replace("{languageOption}", "<option>".$ncd."</option>", $html);
    }
    $html = str_replace("{nextLanguageOption}", "", $html);

    if($_POST['Interpret'] != NULL && $_POST['Songtitel'] != NULL)
    {
        //Song Information API
        $songInformations = "http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=0a26133ca26a243bb9029bfcbbf0058f&artist=".$_POST['Interpret']."&track=".$_POST['Songtitel']."&format=json";
        $songInformationsJson = file_get_contents($songInformations);
        $songInformationData = json_decode($songInformationsJson, true);
        
        //Lyrics API
        $url = "https://api.lyrics.ovh/v1/" . $_POST['Interpret']. "/". $_POST['Songtitel'];
        $json = file_get_contents($url);
        $data = json_decode($json, true);
        $lyrics = str_replace("\n", "<br>", "<h1>Lyrics:</h1>".$data['lyrics']);
        
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
        curl_close($ch);
    } else {
        $html = str_replace("{lyrics}", "Gib bitte einen Songtitel und einen Interpreten ein!", $html);
        $html = str_replace("{translatedLyrics}", "", $html);
    }
    echo $html;


    /*class API {
        function Select(){
            $url = "https://api.lyrics.ovh/v1/257ers/Holz";
            $json = file_get_contents($url);
            $data = json_decode($json, true);
            $array = array('Interpret' => '257ers', 'Songtitel' => 'Holz', 'Lyrics' => $data['lyrics']);
            return json_encode($array);
        }
    }

    $API = new API();
    header("Content-type:application/json");
    echo $API->Select();*/
?>