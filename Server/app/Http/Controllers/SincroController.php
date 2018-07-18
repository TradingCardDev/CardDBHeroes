<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class SincroController extends Controller {

//    public function __construct() {
//        $this->middleware('auth');
//    }

    public function sincronization() {
        // ALL
        $url = "http://www.carddass.com/dbh/sdbh/cardlist/index.php?search=true&category=";
        $sdbh = array(418001,418002,418003,418004,418005,418006,418007,418008,418101,418901, 418902);
        $gdm = array(133301,133302,133303,133304,133305,133306,133307,133308,133309,133310,133904);
        $jm = array(133201,133202,133203,133204,133205,133206,133207,133208,133903);
        $gm = array(133101,133102,133103,133104,133105,133106,133107,133108,133109,133110,133902);
        $gm1 = array (133001,133002,133003,133004,133005,133006,133007,133008,133901,);

        $arrayCategories =array_merge($sdbh,$gdm,$jm,$gm,$gm1);
        foreach ($arrayCategories as $idCategory){
            $this->getCard($url,$idCategory);
        }
    }

    private function getCard($url, $idCategory) {

        $$url = $url . $idCategory;

        /* Convierte el array en el formato adecuado para cURL */
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handler, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($handler, CURLOPT_TIMEOUT, 20);
        $html = curl_exec($handler);
        curl_close($handler);

        $crawler = new Crawler($html);

        $returnArray = $crawler->filterXPath('//div[@class="card-middle"]')->each(function (Crawler $node, $i) {
            // CODE & TITLE
            $codeTitle = $node->filterXPath('//div[@class="titleCol"]//span')->text();
            $codeTitle = explode(" ", $codeTitle);
            $code = $codeTitle[0];
            $title = $codeTitle[1];

            $rare = $node->filterXPath('//dl[@class="rareCol"]//dt')->text();
            $rareNumber = $node->filterXPath('//dl[@class="rareCol"]//dd')->text();
            $image = str_replace("../", "http://www.carddass.com/dbh/sdbh/", $node->filter('img')->attr('src'));

            $status = $node->filterXPath('//dl[@class="status"]//dd');
            $hp = $status->filterXPath('//span')->text();
            $damage = $status->nextAll()->nextAll()->filterXPath('//span')->text();
            $defense = $status->nextAll()->nextAll()->nextAll()->nextAll()->filterXPath('//span')->text();

//            $abilityName = $node->filterXPath('//dl[@class="ability"]//dd[@class="name"]')->text();
//            $abilityDesc = $node->filterXPath('//dl[@class="ability"]//dd[@class="text"]')->text();
            $type = $node->filterXPath('//div[contains(@class, "battletype")]')->attr('class');
            $type = str_replace("battletype ", "", $type);
            $energy = substr($node->filterXPath('//li[@class="energy"]')->text(), -1);

            $returnArray["code"] = $code;
            $returnArray["title"] = $title;
            $returnArray["image"] = $image;
            $returnArray["hp"] = $hp;
            $returnArray["damage"] = $damage;
            $returnArray["defense"] = $defense;
            $returnArray["rare"] = $rare;
            $returnArray["rareNumber"] = $rareNumber;
//            $returnArray["abilityName"] = $abilityName;
//            $returnArray["abilityDesc"] = $abilityDesc;
            $returnArray["type"] = $type;
            $returnArray["energy"] = $energy;

            // Save in Database
//            return $returnArray;
        });

//        return response()->json($returnArray, 200);
    }
}
