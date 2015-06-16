<?php

# imports file want to clean up into an array
$lines = file('June-Dec1969ID&Date.txt', FILE_IGNORE_NEW_LINES);

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
	$newstring = str_replace("search.proquest.com", "search.proquest.com.mutex.gmu.edu/hnplatimes", $rawstring);
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

# establishes base of wget command
$wget = 'wget --load-cookies cookies.txt -O ';

# creates dummy array for testing foreach loop
$commands = array();

# combines my url and date arrays together and concatinates with my wget command to create a new array of individual wget commands for each url and renaming the grabbed file based on the publication date
foreach (array_combine($wanted_urls, $newdates) as $wanted_url => $newdate) {
	$command = $wget .$newdate ." " .$wanted_url ."\n";
# line 52 is the eventual correct code, for now I just want to make sure my string structure is working and correct 
#	exec($command);
	$commands[] = $command;
}

# eventually deleted when want to run the exec command
$newfile = 'commands.txt';
file_put_contents($newfile, $commands);

?>