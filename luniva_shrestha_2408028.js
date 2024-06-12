// setting the initial API key and url

const apiUrl="http://localhost/WEATHER-app/backend/main.php?city=";

// taking the button and input box from the html by their class name
const searchBox= document.querySelector(".search input");
const searchBtn= document.querySelector(".search button");
const weatherIcon= document.querySelector(".weather-icon");

// making the function to fetch city from the website
async function checkWeather(city){

    // fetching data 
    const response= await fetch(apiUrl+ city);

    console.log(response)

    // converting the data to json
    var data= await response.json()
    console.log(data);
    if(data.error){
      alert("No data found");
    }
    // showing the result from the fetched API
    document.querySelector(".city").innerText = data.City;
    document.querySelector(".temp").innerText = (data.temperature) + "°C";
    document.querySelector(".weather-condition").innerText = data.description;
    document.querySelector(".humidity").innerText = data.humidity +"%";
    document.querySelector(".Wind").innerText = data.windSpeed + "km/h";
    document.querySelector(".pressure").innerText = data.Pressure+ "Pa";
    document.querySelector(".time").innerText = new Date(data.date * 1000).toLocaleDateString(
      "en-US",
      {
        weekday: "short",
        day: "numeric",
        month: "short",
        hour: "numeric",
      }
    );

    // setting image according to the weather
    if(data.main=="Clouds"){
        weatherIcon.src="https://raw.githubusercontent.com/CarolCosta9/img/main/weather%20card%20img/clouds.png";
    }
    else if(data.main=="Clear"){
        weatherIcon.src="https://raw.githubusercontent.com/CarolCosta9/img/main/weather%20card%20img/clear.png";
    }
    else if(data.main=="Drizzle"){
        weatherIcon.src="https://raw.githubusercontent.com/CarolCosta9/img/main/weather%20card%20img/drizzle.png";
    }
    else if(data.main=="Snow"){
        weatherIcon.src="https://raw.githubusercontent.com/CarolCosta9/img/main/weather%20card%20img/snow.png";
    }
    else if(data.main=="Rain"){
        weatherIcon.src="https://raw.githubusercontent.com/CarolCosta9/img/main/weather%20card%20img/rain.png";
    }
}

// searching city when search button is clicked
searchBtn.addEventListener("click", function (){
    checkWeather(searchBox.value);
})

// searching city when enter key is clicked
searchBox.addEventListener("keyup", function (e) {
    if (e.code === "Enter") {
      searchBtn.click();
    }
  });

// setting the default city given by the college
checkWeather("Berlin")