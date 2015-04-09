<?php

####################################################################################################
#
# TRIPADVISOR DATA SCRAPER / UNOFFICIAL API
#
# Released under Creative Commons license Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)
# http://creativecommons.org/licenses/by-sa/4.0/
#
#
# DESCRIPTION: this is a rough PHP implementation of a data scraping system from a hotel page
# on TripAdvisor. The system uses regular expressions and substring matching to extract the
# following information from a hotel page on TripAdvisor: TripAdvisor ID, location full name, 
# number of reviews (including totals and types of review), text of the last 10 Reviews.
# The output of this script is a valid JSON file containing the data extracted.
#
# NB. The HTML source code of the pages retrieved might change at any time, therefore the text 
# matching rules might require fine tuning. To do so, edit below parts associated to the 
# comment "### Fine tune/edit if needed".
# 
#
# USAGE:	Send to the script (via GET or POST) the unique location ID using the variable tID 
# 			Example: TAscraper.php?tID=2079052
#
#
# DISCLAIMER: 
# This script was developed just an exercise for study purposes and comes with ABSOLUTELY NO WARRANTY.
# The author cannot be held responsible for any improper user use of this code.
# For a real-world use of TripAdvisor data you must request access to the official API and respect 
# the terms and conditions: https://developer-tripadvisor.com/content-api/terms-and-conditions/
#
# Copyright (C) 2015 Alberto Betella
# betella.net - github.com/albertobeta
#
####################################################################################################

function get_string_between($string, $start, $end){
$string = " ".$string;
$ini = strpos($string,$start);
if ($ini == 0) return "";
$ini += strlen($start);
$len = strpos($string,$end,$ini) - $ini;
return substr($string,$ini,$len);
}


function depurate ($string) {
$string = strip_tags($string);
// Erase the comma as thousand separator
$string = str_replace(',', '', $string);
// Some manual strings
$string = str_replace("\n", '', $string); //NB \n requires double quotes
$string = str_replace('More', '', $string);
$string = str_replace('Was this review helpful?', '', $string);
$string = htmlentities($string);
return $string;
}

if (isset($_POST["tID"])) $tripAdvisorUniqueID = $_POST["tID"];
else if (isset($_GET["tID"])) $tripAdvisorUniqueID = $_GET["tID"];

	if (isset($tripAdvisorUniqueID) && $tripAdvisorUniqueID != NULL) {

	// Retrieve all the client-side code of the page requested
	$pageContent = file_get_contents('http://www.tripadvisor.com/'.$tripAdvisorUniqueID);


	/////////////////
	// Extract Location Name
	$REGEX_name = '/<title>(.*?) - (.*?) TripAdvisor<\/title>/is'; ### Fine tune/edit if needed
	
	$itemNameExtracted = preg_match_all($REGEX_name, $pageContent, $itemNameMatches);
	$itemName = $itemNameMatches[1][0];

	/////////////////
	//Extract Total Number of Reviews
	// Match substring between start and end strings using funcion get_string_between
	$REGEX_NtotalReviews = array (  ### Fine tune/edit if needed
	
	array('class="tabs_header reviews_header">',' Reviews'),
	array('reviews_header">',' reviews')
	);	
	$numberTotalReviews = get_string_between($pageContent, $REGEX_NtotalReviews[0][0],$REGEX_NtotalReviews[0][1]);
	// if there is no match (the code in the page is slightly different sometimes) try other strings
		for ($i = 1; $numberTotalReviews == null && $i < sizeof($REGEX_NtotalReviews); $i++) {
		$numberTotalReviews = get_string_between($pageContent, $REGEX_NtotalReviews[$i][0], $REGEX_NtotalReviews[$i][1]);
		}	
	$numberTotalReviews = depurate($numberTotalReviews);
	
	
	/////////////////
	// Extract Numbers of reviews by type
	$REGEX_typeOfReview = '/<span class="compositeCount">(.*?)<\/span>/is';  ### Fine tune/edit if needed
	
	$typeOfReview = preg_match_all($REGEX_typeOfReview, $pageContent, $reviewsMatches);
	if (isset($reviewsMatches[0][0])) $numberExcellentReviews = depurate($reviewsMatches[0][0]);
	else $numberExcellentReviews = 0; //Excellent
	if (isset($reviewsMatches[0][1])) $numberVeryGoodReviews = depurate($reviewsMatches[0][1]);
	else $numberVeryGoodReviews = 0; //Very Good
	if (isset($reviewsMatches[0][2])) $numberAverageReviews = depurate($reviewsMatches[0][2]);
	else $numberAverageReviews = 0; //Average
	if (isset($reviewsMatches[0][3])) $numberPoorReviews = depurate($reviewsMatches[0][3]);
	else $numberPoorReviews = 0; //Poor
	if (isset($reviewsMatches[0][4])) $numberTerribleReviews = depurate($reviewsMatches[0][4]);
	else $numberTerribleReviews = 0; //Terrible
	
	
	/////////////////
	// Match traveler reviews
	$REGEX_travelerReviews = '/<p class="partial_entry">(.*?)<\/span>/is'; ### Fine tune/edit if needed
	$REGEX_travelerReviewsIgnore = 'response_'; ### Fine tune/edit if needed

	$matchedUserReviews = array();
	$count = preg_match_all($REGEX_travelerReviews, $pageContent, $matches);
		for ($i = 0; $i < $count; ++$i) {
			//Ignore Responses by managers
			if (strpos($matches[0][$i],$REGEX_travelerReviewsIgnore) === false) {
			$matchedUserReviews[] = depurate($matches[0][$i]);
			}
	}

	
	//Create Output
	$output = array(
	"TripAdvisorID" => $tripAdvisorUniqueID,
	"LocationName" => $itemName,
	"NumberOfReviews" => array(
	"total" => $numberTotalReviews,
	"Excellent" => $numberExcellentReviews,
	"VeryGood" => $numberVeryGoodReviews,
	"Average" => $numberAverageReviews,
	"Poor" => $numberPoorReviews,
	"Terrible" => $numberTerribleReviews
	),
	"Last10Reviews" => $matchedUserReviews
	);

	//Print Output in JSON Format
	echo json_encode($output);

}

?>