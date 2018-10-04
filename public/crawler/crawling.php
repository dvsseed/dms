<?php

// our fopen is right, so let’s go
// American Diabetes Association, News, Press Releases, 2017
if(!$fp = fopen("http://www.diabetes.org/newsroom/press-releases/2017/" ,"r" )) {
	return false;
}

$content = "";
while(!feof($fp)) {
	// while it is not the last line, we will add the current line to our $content
	$content .= fgets($fp, 1024);
}

// we are done here, don’t need the main source anymore
fclose($fp);

// it has all page contents without any CSS or JS
// echo $content;

// our magic regex(regular expression) here
// preg_match_all("/glucose/i", $content, $matches, PREG_SET_ORDER);
// preg_match_all("/[-| ]*glucose[-| ]*[a]*/i", $content, $matches, PREG_SET_ORDER);
preg_match_all("/<a href=(.*)(glucose)(.*)<\/a>/i", $content, $matches, PREG_SET_ORDER);
// preg_match("/insulin/i", $content, $matches, PREG_OFFSET_CAPTURE);
// preg_match("/glucose/i", $content, $matches);
// echo $matches[0][0] . PHP_EOL;
// print_r($matches);
foreach ($matches as $key => $val) {
    echo "key: " . $key . "\n";
    echo "matched: " . $val[0] . "\n";
    echo "part 1: " . $val[1] . "\n";
    echo "part 2: " . $val[2] . "\n";
    echo "part 3: " . $val[3] . "\n";
    echo "part 4: " . $val[4] . "\n\n";
}

?>