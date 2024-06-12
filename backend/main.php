<?php
//including necessary files
include("database.php");
include("api.php");
//Allow cross-origin requests and setting content type to JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
//setting database connection
$connection = connect_database("localhost", "root", "", "weather");

//checking if the 'city' parameter is set in the request
if (isset($_GET["city"])) {
    $city= $_GET["city"];
$existingData = null;
//get weather data for the specified city
$allData = get_weather_data($connection, $city);
if ($allData !== null) {  
    // Check if $allData is not null before using count()
    if (count($allData) == 0) {
        $existingData = null;
    } else {
        //get the latest weather data
        $lastIndex = count($allData) - 1;
        $existingData = $allData[$lastIndex];
    }
} else {
    $existingData = null;
}

//check if 'history' parameter is set in the request
if (isset($_GET["history"])) {
    //returning all weather data as JSON
    echo json_encode($allData);
    exit;
}
//setting refresh time to 24 hour
$refreshTime = 24*60*60; // full day
//checking if existing data needs to be refreshed
if ($existingData) {
    $dataTimeStamp = 0;
    if (isset($existingData["date"])){
        $dataTimeStamp = $existingData["date"];
    }
    $currentTime = time();
    if ($currentTime - $dataTimeStamp > $refreshTime) {

        $newData = fetch_currentWeather_data($city);
        if ($newData) {
                //formatting and return the new data as JSON 
                insert_weather_data($connection,$newData);
                $databaseFormat = ['coord' => $newData['coord'],
                'icon'=>$newData['weather'][0]['icon'],
                'description' => $newData['weather'][0]['description'],
                'main'=>$newData['weather'][0]['main'],
                'temperature' => $newData['main']['temp'],
                'pressure' => $newData['main']['pressure'],
                'humidity' => $newData['main']['humidity'],
                'windspeed' => $newData['wind']['speed'],
                'name' => $newData['name'],
                'date' => $newData['dt']];
                echo json_encode($databaseFormat);
            
        } else {
            echo '{"error": "Data could not be fetched!"}';
            exit;
        }
    } else {
        //returning existing data as JSON
        echo json_encode($existingData);
        exit;
    }
} else {
    //fetching new data if no existing data is found
    $newData = fetch_currentWeather_data($city);
    if ($newData) {
        //inserting new data into the database
        insert_weather_data($connection,$newData);
            //formatting and returning the new data as JSON
            $databaseFormat = [
                  'coord' => $newData['coord'],
                'icon'=>$newData['weather'][0]['icon'],
                'description' => $newData['weather'][0]['description'],
                'main'=>$newData['weather'][0]['main'],
                'temperature' => $newData['main']['temp'],
                'pressure' => $newData['main']['pressure'],
                'humidity' => $newData['main']['humidity'],
                'windspeed' => $newData['wind']['speed'],
            'name' => $newData['name'],
            'date' => $newData['dt']];
            echo json_encode($databaseFormat);

    } else {
        echo '{"error": "Data could not be fetched!"}';
        exit;
    }
}
} else {
    //Return an error if 'city' parameter is not provided.
    echo '{"error": "No city provided!"}';
}?>