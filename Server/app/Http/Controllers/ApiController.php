<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class ApiController extends Controller {

//    public function __construct() {
//        $this->middleware('auth', ['except' => ['exampleGet']]);
//    }

    public function getCard() {

        $url = "http://www.carddass.com/dbh/sdbh/cardlist/index.php?search=true&category=418101";

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

//            $type = $node->filterXPath('//div[contains(@class="battletype")]')->attr('class');
//            $type = $node->filterXPath('//div[@class="battletype"]')->attr('class');
            $energy = substr($node->filterXPath('//li[@class="energy"]')->text(), -1);

//            echo $energy;

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

            return $returnArray;
        });
//        var_dump($returnArray);

//        $crawlerDiv = $crawler->filterXPath('//div[@class="card-middle"]');
//        echo($crawlerDiv->html());


        return response()->json($returnArray, 200);
    }


        public function exampleGet() {

        $test = array("name" => "Charlie");

        return response()->json($test, 200);
    }

    public function examplePost(Request $request) {
        return response()->json("Added", 200);
    }

    public function examplePut(Request $request, $id) {
        return response()->json("Updated", 200);
    }

    public function exampleDelete(Request $request, $id) {
        return response()->json("Deleted", 204);
    }
}
