<?php

namespace App\Api;

use App\WeatherForecast;
use Illuminate\Support\Facades\Http;


class Yr
{

  public function __construct()
  {

    $this->json = null;
    try
    {
      // $xml = file_get_contents("https://api.met.no/weatherapi/locationforecast/2.0/classic?altitude=0&lat=50.962388952197706&lon=13.941356461376616");

      $ep = "https://api.met.no/weatherapi/locationforecast/2.0/classic?altitude=0&lat=50.962388952197706&lon=13.941356461376616";

      $client = new \GuzzleHttp\Client();
      $response = $client->request('GET', $ep, [
          'headers' => [
            'User-Agent' => 'eventarit'
          ]
       ]);

      $xml = $response->getBody();


      $this->json = $this->namespacedXMLToArray($xml);
    }
    catch(Exception $e)
    {
      // nothing
    }


  }

  public function getForecast( $from_date )
  {

    if ( isset($this->json['product']['time']) )
    {

      $times = $this->json['product']['time'];

      foreach ( $times as $key => $time)
      {
        if ( isset($time['location']['symbol']) && isset($time['location']['maxTemperature']) )
        {
          $temperature = $time['location']['maxTemperature']['@attributes']['value'];
          if ( !empty($time['location']['minTemperature']['@attributes']['value'])) {
            $temperature = ($temperature + $time['location']['minTemperature']['@attributes']['value']) / 2;
          }

          $date = date("Y-m-d",  strtotime($time["@attributes"]["from"]) );
          $forecast = WeatherForecast::firstOrNew( ["date"=>$date] );
          $forecast->date        = $date;
          $forecast->icon        = str_replace(" ", "_", strtolower( $time['location']['symbol']["@attributes"]["code"] ) );
          $forecast->temperature = (int)$temperature;
          $forecast->save();
        }
      }
    }
  }


  public function removeNamespaceFromXML( $xml )
  {
    // Because I know all of the the namespaces that will possibly appear in
    // in the XML string I can just hard code them and check for
    // them to remove them
    $toRemove = ['rap', 'turss', 'crim', 'cred', 'j', 'rap-code', 'evic'];
    // This is part of a regex I will use to remove the namespace declaration from string
    $nameSpaceDefRegEx = '(\S+)=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?';

    // Cycle through each namespace and remove it from the XML string
   foreach( $toRemove as $remove ) {
        // First remove the namespace from the opening of the tag
        $xml = str_replace('<' . $remove . ':', '<', $xml);
        // Now remove the namespace from the closing of the tag
        $xml = str_replace('</' . $remove . ':', '</', $xml);
        // This XML uses the name space with CommentText, so remove that too
        $xml = str_replace($remove . ':commentText', 'commentText', $xml);
        // Complete the pattern for RegEx to remove this namespace declaration
        $pattern = "/xmlns:{$remove}{$nameSpaceDefRegEx}/";
        // Remove the actual namespace declaration using the Pattern
        $xml = preg_replace($pattern, '', $xml, 1);
    }

    // Return sanitized and cleaned up XML with no namespaces
    return $xml;
  }


  public function namespacedXMLToArray($xml)
  {
      // One function to both clean the XML string and return an array
      return json_decode(json_encode(simplexml_load_string($this->removeNamespaceFromXML($xml))), true);
  }

}