<?php
//creating function to fetch current weather data for a specified city
function fetch_currentWeather_data($city){
    try{
    //url with city name and API key
$APIURL="https://api.openweathermap.org/data/2.5/weather?units=metric&q=".$city."&appid=7e5a8c646fc24d483fe02d2982677ed2";
//fetching weather data from the API using file_get_contents
$weatherD=@file_get_contents($APIURL);
//decoding the retrieved JSON data into associative array
$data=json_decode($weatherD,true);
return $data;
    }catch(Exception $th){
        return null;
    }
}
?>