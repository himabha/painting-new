<?php

class Helper
{
    const lang = 'hb';
    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }



    public function getHebrewText($code)

    {

        $results = $this->wpdb->get_results("select lang_".self::lang." from translations where code = '".addslashes($code)."' and active = 1");

        if (empty($results)) {

            return "";

        }

        $row = $results;
        $lang = 'lang_'.self::lang;
        return $row[0]->$lang;

    }


    public function getAllTranslations($lang){
        $results = $this->wpdb->get_results("select code, lang_".$lang." from translations where active = 1");
        if (empty($results)) {
            return "";
        }
        $json_lang = [];
        $lang = "lang_".$lang;
        foreach ($results as $key => $value) {
            $json_lang[$value->code] = $value->$lang;
        }
        $json_lang = json_encode($json_lang, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $fh = fopen(plugin_dir_path(__FILE__)."language.json", "w");
        fwrite($fh, $json_lang);
        fclose($fh);
    }

    public function getLang(){
        return self::lang;
    }
}

