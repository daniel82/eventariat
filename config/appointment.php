<?php

return [

    'day_start' => "00:00:00",
    'day_end' => "23:59:59",

    'type' =>
    [
        [ "id" => 4, "text"=>"Arbeit" ],
        [ "id" => 1, "text"=>"Urlaub" ],
        [ "id" => 2, "text"=>"Ereignis"],
        [ "id" => 3, "text"=>"Ferienwohnung"],
        [ "id" => 5, "text"=>"Termin"],
        [ "id" => 6, "text"=>"Frei"],
        [ "id" => 7, "text"=>"Krank"],
        [ "id" => 8, "text"=>"Berufsschule"],
    ],


    'recurring' =>
    [
        [ "id" => 0, "text"=>"nein" ],
        [ "id" => "weekly", "text"=>"wöchentlich" ],
    ],

    'weekend_map' =>
    [
        [ "days"=>6, "weekend"=>1 ],
        [ "days"=>7, "weekend"=>2 ],
        [ "days"=>13, "weekend"=>3 ],
        [ "days"=>14, "weekend"=>4 ],
        [ "days"=>20, "weekend"=>5 ],
        [ "days"=>21, "weekend"=>6 ],
        [ "days"=>27, "weekend"=>7 ],
        [ "days"=>28, "weekend"=>8 ],
    ]

];

