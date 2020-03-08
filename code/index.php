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
        //Lyrics API
        $url = "https://api.lyrics.ovh/v1/" . $_POST['Interpret']. "/". $_POST['Songtitel'];
        $json = file_get_contents($url);
        $data = json_decode($json, true);
        $lyrics = str_replace("\n", "<br>", $data['lyrics']);
        
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