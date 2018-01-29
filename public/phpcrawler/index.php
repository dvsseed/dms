<!DOCTYPE html>
<html>
 <head>
  <title>PHP Advanced Crawler</title>
 </head>
 <body>
  <div id="content">
   <center><h1>PHP Advanced Crawler</h1></center>
   <form action="" method="POST">
    <input name="url" style="width:300px;" placeholder="A URL please...." /><br/>
    <input type="submit" name="submit" value="Crawl"/>
   </form>
   <?php
   if(isset($_POST['submit'])){
    $crawlURL=$_POST['url'];
    function isDomainAvailible($domain){
     if(!filter_var($domain, FILTER_VALIDATE_URL)){
      return false;
     }
     $curlInit = curl_init($domain);
     curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
     curl_setopt($curlInit,CURLOPT_HEADER,true);
     curl_setopt($curlInit,CURLOPT_NOBODY,true);
     curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
     $response = curl_exec($curlInit);
     curl_close($curlInit);
     if ($response) return true;
     return false;
    }
    if(!isDomainAvailible($crawlURL)){
     echo "<h2>A valid URL please.</h2>The URL you gave me was unable to access.";
    }else{
     echo "<p></p>Crawling $crawlURL</p>";
     flush();
     include("crawler.php");
    }
   }
   ?>
  </div>
  <?php
  /* http://subinsb.com/php-create-advanced-web-crawler */
  ?>
 </body>
</html>