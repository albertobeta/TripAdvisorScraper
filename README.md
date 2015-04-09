# TripAdvisor data scraper / unofficial API

## Description
This is a rough PHP implementation of a data scraping system from a hotel page
on TripAdvisor. The system uses regular expressions and strings matching to extract the
following information from a hotel page on TripAdvisor: 
* Location full name
* Number of reviews (including totals by type of review)
* Text of the last 10 Reviews

The output of this script is a valid JSON file containing the data extracted, e.g.

```json
{ 
"TripAdvisorID":"123456",
"LocationName":"Full name of the Hotel (City)",
"NumberOfReviews":{  
	"total":"616",
	"Excellent":"81",
	"VeryGood":"186",
	"Average":"196",
	"Poor":"79",
	"Terrible":"74"
},
"Last10Reviews":[  
	"Text review 1 (until the More button)",
	"Text review 2 (until the More button)",
	"Text review 3 (until the More button)",
	"Text review 4 (until the More button)",
	"Text review 5 (until the More button)",
	"Text review 6 (until the More button)",
	"Text review 7 (until the More button)",
	"Text review 8 (until the More button)",
	"Text review 9 (until the More button)",
	"Text review 10 (until the More button)"
]
}
```

## Usage
Send to the script (via GET or POST) the unique location ID using the variable **tID**

Example: `TAscraper.php?tID=2079052`

The **example** folder contains a working example featuring a form with real-time auto completion
of the hotel names (including location IDs).

## Potential Issues
This script retrieves in real time the entire HTML source code of a page and extracts from it useful data.
To do so, regular expressions are used to isolate data between tags (a pretty dirty method indeed). 
Since these tags can change at any time, the text matching rules might require fine tuning. To do so, it
is possible to edit those parts of the code which are associated to the comment "Fine tune/edit if needed".

## Disclaimer 
This script was developed as a simple exercise for educational purposes and comes with ABSOLUTELY NO WARRANTY.
The author cannot be held responsible for any improper user use of this code.
For a real-world use of TripAdvisor data you must request access to the official API and respect 
the terms and conditions: https://developer-tripadvisor.com/content-api/terms-and-conditions/

# License
Released under The MIT License (MIT)
Copyright (C) 2015 Alberto Betella