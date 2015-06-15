<?php

# imports file want to clean up into an array
$lines = file('June-Dec1969ID&Date.txt');

# creates new arrays from only lines that contain 'http' and 'date' from $lines array
$urls = preg_grep('/(http)/um', $lines);
$datelines = preg_grep('/(date)/um', $lines);

# creates new arrays for cleaned up urls and dates
$newurls = array();
$newdates = array();

# URL CLEANUP
# replaces portion of url and puts into new array
foreach ($urls as $url) {
	$rawstring = $url;
	$newstring = str_replace("search.proquest.com", "search.proquest.com.mutex.gmu.edu/hnpwashingtonpost", $rawstring);
	$newurls[] = $newstring;
}

# deletes the lines from $newurls array that contain the words 'Condition' or 'Contact' and puts the rest of the #newurls array into $wanted_urls array
$unwanted_words = array("Condition", "Contact");
$unwanted_words_match = '(?:' . join('|', array_map(function($word) {
    return preg_quote($word, '/'); }, $unwanted_words)) . ')';
$wanted_urls = preg_grep("/$unwanted_words_match/", $newurls, PREG_GREP_INVERT);

# DATE CLEANUP
# replaces the written month with the number and dash, deletes the phrase 'Publication Date:', deletes spaces but not line breaks, and replaces the commas with a dash
foreach ($datelines as $dateline) {
	$rawstring = $dateline;
	$month = str_replace(
		array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"),
		array("1-", "2-", "3-", "4-", "5-", "6-", "7-", "8-", "9-", "10-", "11-", "12-"),
		$rawstring);
	$alldate = str_replace("Publication date:", "", $month);
	$nospace = preg_replace("/[ ]/um", "", $alldate);
	$comma = preg_replace("/,/um", "-", $nospace);
	$newdates[] = $comma;
}

# creates a new file for the finished urls and dates
# eventually will be replaced with wget command for each url in the $wanted_urls array and renaming the downloaded PDF based on the $newdates array
$newfile = 'June-Dec1969URLs.txt';
file_put_contents($newfile, $wanted_urls);

$newfile2 = 'June-Dec1969dates.txt';
file_put_contents($newfile2, $newdates);
?>