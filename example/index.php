<html>
	<head>

	<title>Example TripAdvisor Scraper</title>

	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

	<script>

	$(function() {
		function log( message ) {
		$( "<div>" ).text( message ).prependTo( "#log" );
		$( "#log" ).scrollTop( 0 );
		}

		$( "#tID" ).autocomplete({
			source: function( request, response ) {
				$.ajax({
				url: "http://www.tripadvisor.com/TypeAheadJson",
				dataType: "jsonp",
				data: {
				query: request.term,
				types: "hotel", //hotel,eat,attr
				action: "API"
			},

			success: function( data ) {
				response( $.map( data.results, function( item ) {
				return {
					//label and value are expected by the autocomplete funcion
					label: item.name,
					value: item.value,

					//other data in results for the same items
					data_type: item.data_type,
					name: item.name,
					type: item.type,
					url: item.url,
					coords: item.coords
					}
				// console.log(data); //Debug
				}));
			}
		});
	},
	//   minLength: 3,
	select: function( event, ui ) {
		//assign value back to the form element
		if(ui.item){
		$(event.target).val(ui.item.value);
		}
		//submit the form
		$(event.target.form).submit();       
		}
		});
	});
	</script>


	<style type="text/css">

		input {
		-moz-border-radius: 8px;
		border-radius: 8px;
		border:1px solid #000;	
		width:100%;
		padding:5px;
		}

		.ui-widget {
		font-size:0.7em;
		}

	</style>

	</head>

	<body>

		<h1>TRIPADVISOR DATA SCRAPER</h1>
		
		<p>Usage example (the form below features real-time auto completion using jquery UI)</p>
		
		<div class="ui-widget">
		
		<form method="POST" action="TAscraper.php">
			<input id="tID" name="tID" type="search" value="Search for a location or hotel" onfocus="this.value='';" onblur="this.value='Search for a location or hotel';">
		</form>
		</div>
		
		<p>github.com/albertobeta/TripAdvisorScraper</p>
		
	</body>
</html>





