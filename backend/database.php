<?php
//making a function to set database connection
function connect_database($server, $username, $password, $db){
    $connection = null;
    try {
        $connection = new mysqli($server, $username, $password, $db);
        if ($connection -> connect_errno){
            //returning an error message if the connection fails
            echo '{"error": "Database connection failed!"}';
        }
        return $connection;
    } catch (Exception $th) {
        //returning null in the case of an exception
        return null;
    }
}
//making function to retrieve weather data for a specific city
function get_weather_data($connection, $city){
    try {
        //query to select all weather data  for a city
        $result = $connection -> query('SELECT * FROM weathers WHERE City= "'.$city.'" ORDER BY date ASC ;');
        if ($result){
            //fetching and returning data as an associative array
            $data = $result -> fetch_all(MYSQLI_ASSOC);
            return $data;
        } else {
            //returning null if the query fails
            return null;
        }

    } catch (Exception $th) {
        return null;
    }
}
//making a function to retrieve weather data for a specific city and timestamp
function get_weather_data_with_timestamp($connection, $city, $timestamp){
    try {
        //query to select weather data for a city and timestamp
        $result = $connection -> query('SELECT * FROM weathers WHERE City = "'.$city.'" AND date = '.$timestamp.';');
        if ($result){
            //fetching and returning data as an associative array
            $data = $result -> fetch_all(MYSQLI_ASSOC);
            return $data;
        } else {
            //returning null if the query fails
            return null;
        }

    } catch (Exception $th) {
        //returning null incase of an exception
        return null;
    }
}
//creating function to insert weather data into the database
function insert_weather_data($connection, $data){
    try {
        //extracting necesary data from the array
        $date=$data["dt"];
        $city=$data["name"];
        $temp=$data["main"]["temp"];
        $humidity=$data["main"]["humidity"];
        $wind=$data["wind"]["speed"];
        $pressure=$data["main"]["pressure"];
        $icon=$data["weather"][0]["icon"];
        $description=$data["weather"][0]["description"];
        //checking if data for the same time stamp and city already exists
        if (!get_weather_data_with_timestamp($connection, $city,$date)){
        //query to insert weather data into the database   
        $result = $connection -> query('INSERT INTO weathers (date, City, temperature, humidity, windSpeed, Pressure, icon, description) VALUES (
           '.$date.',
           "'.$city.'",
           '.$temp.',
           '.$humidity.',
           '.$wind.',
           '.$pressure.',
           "'.$icon.'",
           "'.$description.'"
        );');

        if ($result){
            //returning true if the insertion is successful
            return true;
        }else {
            //returning false if the insertion fails
            return false;
        }
    }
    } catch (Exception $th) {
        //echo the exception 
        echo $th;
        //return null incase of an exception
        return null;
    }
}
?>