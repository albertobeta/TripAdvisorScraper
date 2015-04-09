TRIPADVISOR DATA SCRAPER / UNOFFICIAL API

DESCRIPTION: this is a rough PHP implementation of a data scraping system from a hotel page
on TripAdvisor. The system uses regular expressions and substring matching to extract the
following information from a hotel page on TripAdvisor: TripAdvisor ID, location full name, 
number of reviews (including totals and types of review), text of the last 10 Reviews.
The output of this script is a valid JSON file containing the data extracted.

NB. The HTML source code of the pages retrieved might change at any time, therefore the text 
matching rules might require fine tuning. To do so, edit below parts associated to the 
comment " Fine tune/edit if needed".


USAGE:	Send to the script (via GET or POST) the unique location ID using the variable tID 
		Example: TAscraper.php?tID=2079052


DISCLAIMER: 
This script was developed just an exercise for study purposes and comes with ABSOLUTELY NO WARRANTY.
The author cannot be held responsible for any improper user use of this code.
For a real-world use of TripAdvisor data you must request access to the official API and respect 
the terms and conditions: https://developer-tripadvisor.com/content-api/terms-and-conditions/


Released under The MIT License (MIT)
Copyright (C) 2015 Alberto Betella
betella.net - github.com/albertobeta