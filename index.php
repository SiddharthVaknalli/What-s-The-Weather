<?php
//    //Display errors if any
//    ini_set('display_errors', 1);
//    ini_set('display_startup_errors', 1);
//    error_reporting(E_ALL);
    
//    // create a new cURL resource
//    $ch = curl_init();
//
//    // set URL and other appropriate options
//    curl_setopt($ch, CURLOPT_URL, "http://api.openweathermap.org/data/2.5/weather?q=London,uk&appid=f148078a2e8b97ceba286500223a7101&mode=xml");
//    curl_setopt($ch, CURLOPT_HEADER, 0);
//
//    // grab URL and pass it to the browser
//    $weatherXML = curl_exec($ch);
//
//    // close cURL resource, and free up system resources
//    curl_close($ch);
//
//    $xml=simplexml_load_string($weatherXML) or die("Error: Cannot create object");
//    echo $xml->city.name;

    $xml = "";

    if (isset($_GET['city'])) {
        $city = $_GET['city'];
        $query = $city;

        // country is optional to find a location
        if(isset($_GET['country'])) {
            $country = $_GET['country'];
            $query .= "," . $country;
        }     
        $xml = simplexml_load_file("http://api.openweathermap.org/data/2.5/weather?q=".$query."&appid=f148078a2e8b97ceba286500223a7101&mode=xml&units=metric");
            
        $imgPath = "http://openweathermap.org/img/w/".$xml->weather['icon'].".png";
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags always come first -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css" crossorigin="anonymous">
        <!--Custom CSS-->
        <link rel="stylesheet" type="text/css" href="styles/main.css">
        <title>What's The Weather</title>
    </head>
    <body>
        <div class="container" id="heading">
            WHAT'S THE WEATHER?
        </div>
        <h3 style="text-align: center;">Please enter a city and the country</h3>
        <form class="form-inline" style="text-align: center;" method="GET">
            <div class="form-group">
                <input class="form-control" type="text" name="city" placeholder="City">
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="country" placeholder="Country">
            </div>
            <button class="btn btn-default" type="submit">Go!</button>
        </form>
        <br>
        <?php if($xml) { ?>
            <div class="row text-center">
                <div class="col-sm-4 col-sm-offset-4 transbox" style="text-align: center;">
                    <h3><?php echo htmlspecialchars($xml->city['name'].", ".$xml->city->country); ?></h3>
                    <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="Weather Image">
                    <div class="caption">
                        <h4><?php echo htmlspecialchars((round($xml->temperature['value']))."°C"); ?></h4>
                        <h4><?php echo htmlspecialchars(ucfirst($xml->weather['value'])); ?></h4>
                        <br>
                        <?php echo htmlspecialchars("Min: ".(round($xml->temperature['min']))."°C"); ?>
                        <br>
                        <?php echo htmlspecialchars("Max: ".(round($xml->temperature['max']))."°C"); ?>
                        <br>
                        <?php echo htmlspecialchars("Humidity: ".(round($xml->humidity['value']))."%"); ?>
                        <br>
                        <?php echo htmlspecialchars("Wind: ".$xml->wind->speed['name']." (".($xml->wind->speed['value'] * 3.6)."km/h ".$xml->wind->direction['code'].")"); ?>
                    </div>
                </div>
            </div>
        <?php } else if (isset($_GET['city']) || isset($_GET['country'])) { ?>
            <div class="row">
                <div class="col-sm-4 alert alert-danger">
                    Requested location not found! Please try again.
                </div>
            </div>
        <?php } ?>

    </body>
</html>
