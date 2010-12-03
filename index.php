<?php

    /* define the key */
    $mapkey = 'ABQIAAAAijZqBZcz-rowoXZC1tt9iRT2yXp_ZAY8_ufC3CFXhHIE1NvwkxQQBCaF1R_k1GBJV5uDLhAKaTePyQ';

    if ($_SERVER['HTTP_X_FORWARD_FOR']) {
      $ip = $_SERVER['HTTP_X_FORWARD_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
  
    if($ip == '127.0.0.1') { $ip = "188.25.32.219"; }

       //using YQL with Open Data Table
       $root = 'http://query.yahooapis.com/v1/public/yql?q=';     

       $yql = "use 'http://thinkphp.ro/apps/YQL/ip.location2.xml' as ip.location; select * from ip.location where ip='".$ip."'";

       $url = $root . urlencode($yql). '&diagnostics=false&format=json';

       $content = get($url);

       $json = json_decode($content);

       $json = $json->query->results->Response;

       $lat = $json->Latitude;

       $lon = $json->Longitude;       

       $lat_phi = 7.749094381082658;

       $lon_phi = 98.77944946289062;


                          $src ='http://maps.google.com/maps/api/staticmap?'.
 
                                'center='.$lat.','.$lon.'&sensor=false&size=350x300&'.

                                'maptype=roadmap&key='.$mapkey.'&markers=color:blue'.

                                '|label:I|'.$lat.','.$lon.'&visible='.$lat.','.$lon.

                                '|'.$lat_phi.','.$lon_phi.'&markers=color:red|label:P|'.$lat_phi.','.$lon_phi;

    $sr = 'http://maps.google.com/maps/api/staticmap?'.  

              'center='.$lat.','.$lon.'&sensor=false&size=300x300&'.

              'maptype=roadmap&key='.$mapkey.'&markers=color:blue'.

              '|label:I|'.$lat.','.$lon.'&visible='.$lat.','.$lon;


    $img = '<img src='.$src.' alt="map">';              

    //@param url (String)
    function get($url) {

          $ch = curl_init();

          curl_setopt($ch,CURLOPT_URL,$url);

          curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

          curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,2);

          $data = curl_exec($ch);

          curl_close($ch);  

          if(empty($data)) {

            return 'Error retrieve data, please try again.';

          } else {return $data;}   

     }//end function get

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
   <title>Compute distance using latitude and longitude</title>
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/reset-fonts-grids/reset-fonts-grids.css" type="text/css">
   <link rel="stylesheet" href="http://yui.yahooapis.com/2.7.0/build/base/base.css" type="text/css">
   <style type="text/css">
   body,html{ background:#333;color:#ccc;font-family: helvetica,arial,verdana,sans-serif}

   #doc{background:#f8f8f8;color:#333;border:1em solid #f8f8f8;font-family:georgia,serif;}

   h1{font-size:220%;color:#2A84BA;text-shadow:1px 3px 3px #ccc}

   h2{font-size:150%;color:#2A84BA;}
 
   h3{font-size:140%;color:#2A84BA;}

   p,li{font-size:130%;}

   ul{margin:0 0 0 1.5em;}

   li{padding:.2em 0;}

   li strong{width:8em;float:left;display:block;}

   a{color:#2A84BA;}

   #info {margin-top: 40px}

   #ft p{font-size:100%;text-align:left;margin-top:10px;margin-bottom: -5px;color: #444}

   #map {position: relative;width: 300px;height: 300px;}
 
   #map span {position:absolute;background:rgba(0,0,0,.5);color: #fff;height:280px;width:280px;display: block;top:0;left:0;font-size:200%;padding:0;overflow: hidden;}

   #map span strong {display:block;color:#0f0;}
  
   </style>
</head>
<body>
<div id="doc" class="yui-t7">
   <div id="hd" role="banner"><h1>Compute distance using latitude and longitude</h1></div>
   <div id="bd" role="main">
   <p>This page shows you the distance between the island PHI-PHI and the location from your IP.</p>
   <div class="yui-g">       
     <div class="yui-u first">

       <h2>Map and info</h2>  
       <div id="map">

          <?php echo$img; ?>
 
       </div><!-- end area map -->
	
     </div>
     <div class="yui-u" id="info">
	
        <h3>From your IP:</h3>
 
        <ul>
           <?php 

             echo'<li><strong>Latitude:</strong><span id="oldlat">'.$lat.'</span></li>';

             echo'<li><strong>Longitude:</strong><span id="oldlon">'.$lon.'</span></li>';
           ?>
        </ul>


        <h3>Island PHi-PHI:</h3>
 
        <ul>
           <?php 

             echo'<li><strong>Latitude:</strong><span id="oldlat">'.$lat_phi.'</span></li>';

             echo'<li><strong>Longitude:</strong><span id="oldlon">'.$lon_phi.'</span></li>';
           ?>
        </ul>

        <h3>Distance:</h3>
         <ul>
           <?php 

             echo'<li><strong>d = </strong><span id="distance"></span></li>';
           ?>
        </ul>

     </div>
   </div>
	</div>
   <div id="ft" role="contentinfo"><p>Created by @<a href="http://twitter.com/thinkphp">thinkphp</a> using <a href="http://j.maxmind.com/">maxmind</a> , little math, and this Open Data Table <a href="http://thinkphp.ro/apps/YQL/ip.location2.xml">ip.location</a> download on <a href="https://github.com/thinkphp/geo-phi-phi">GitHub</a></p></div>

</div>

<script type="text/javascript" src="http://j.maxmind.com/app/geoip.js"></script>
<script type="text/javascript">


    (function(){

             var lat = geoip_latitude(); 

             var lon = geoip_longitude();

             var lat_phi = 7.749094381082658;

             var lon_phi = 98.77944946289062;

             var d = distance(lat,lat_phi,lon,lon_phi);

             document.getElementById('distance').innerHTML = d;
    })();


            //computing distance using latitude and longitude coordinates
            function distance(lat1,lon1,lat2,lon2) {

                var R = 3960.0;

                var phi_1 = (90.0 - lat1)*Math.PI / 180.0;

                var phi_2 = (90.0 - lat2)*Math.PI / 180.0;

                var theta_1 = lon1 * Math.PI / 180.0;

                var theta_2 = lon2 * Math.PI / 180.0;

                var d = R * Math.acos(

                    Math.sin(phi_1) *

                    Math.sin(phi_2) *

                    Math.cos(theta_1 - theta_2) +

                    Math.cos(phi_1) *

                    Math.cos(phi_2)
                );

                var output = Math.round(d) + " miles or " + Math.round(1.609344*d) + ' km';

               return output; 

            }//end function
</script>
</body>
</html>
