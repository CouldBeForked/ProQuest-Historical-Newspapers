<?php

# imports url list as an array
$urls = file('urls.txt');

# imports date list as an array
$dates = file('dates.txt'); 

# establishes base of wget command
$wget = 'wget --load-cookies cookies.txt -O ';

# creates dummy array for testing foreach loop
$commands = array();

foreach (array_combine($urls, $dates) as $url => $date) {
	$command = $wget .$date ." " .$url;
# line 18 is the eventual correct code, but there is a bug in the concatinating variable in line 16 so for now just writing the string to an array and then printing to a file while working out the bug
#	exec($command);
	$commands[] = $command;
}

# eventually deleted when work out the bug in line 16
$newfile = 'commands.txt';
file_put_contents($newfile, $commands);


?>