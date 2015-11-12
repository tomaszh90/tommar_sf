$(document).ready(function () {
	
	var keywords = [];	
	
	// Display website information as typed.
    $('#titleInput').keyup(function(e){
		
	  // Add inpuuted text to preview
      $('#title').html($(this).val().substring(0, limit));
	  
	  // Truncate text
	  var limit = 50;
	  var outputLength = document.getElementById('titleInput').value;
	  //console.log(titleLength);
	  if (outputLength.length > limit) {
		  $('#title').html($(this).val().substring(0, limit) + "...");
	  }
	  
	  // Wrap title text with domain link
	  if ((e.which !== 0) && ($('#urlInput').val())) {
		$('#title a').contents().unwrap();
		var ahref = document.getElementById('urlInput').value;
		//$('#title').wrapInner( '<a href="http://' + ahref + '"></a>');
		$('#title').wrapInner( '<a href="#"></a>');
	  }
	
	// Transitions for display
	if (e.which !== 0) {
		  $('#title').fadeIn();
		  $('.hideMe').fadeIn();
	  }
    });
	
	
	$('#urlInput').keyup(function(e){
	  
	  // Add inputted text to preview
      $('#url').html($(this).val());
	  
	  // Transitions for display
	  if (e.which !== 0) {
		  $('#url').fadeIn();
		  $('.hideMe').fadeIn();
	  }

	  // Wrap title text with domain link
	  if ((e.which !== 0) && ($('#titleInput').val())) {
		$('#title a').contents().unwrap();
		var ahref = document.getElementById('urlInput').value;
		//$('#title').wrapInner( '<a href="http://' + ahref + '"></a>');
		$('#title').wrapInner( '<a href="#"></a>');
	  }

	  
	  // Truncate text
	  var limit = 70;
	  var outputLength = document.getElementById('urlInput').value;
	  if (outputLength.length > limit) {
		  $('#url').html($(this).val().substring(0, limit) + "...");
	  }
						  
    });
	
	// Wrap title text with domain link
	$('#urlInput').focusout(function() {
			$('#title a').contents().unwrap();
			var ahref = document.getElementById('urlInput').value;
			$('#title').wrapInner( '<a href="#"></a>');
			//$('#title').wrapInner( '<a href="http://' + ahref + '"></a>');
	});
	
	$('#descInput').keyup(function(e){
	  
	  // Add inpuuted text to preview
      $('#desc').html($(this).val());
	  //$('#desc').append('<span class="ellipsis">&#133;</span><span class="fill"></span>');
	 
	  // Transitions for display
	  if (e.which !== 0) {
		  $('#desc').fadeIn();
		  $('.hideMe').fadeIn();
	  }
	  
	  // Truncate text
	  var limit = 150;
	  var outputLength = document.getElementById('descInput').value;
	  if (outputLength.length > limit) {
		  $('#desc').html($(this).val().substring(0, limit) + "...");
	  }
	  
	});
	$('#searchInput').keyup(function(e){
      $('.google-search').html($(this).val());
	  	  if (e.which !== 0) {
			
			$('.results strong').contents().unwrap();
			
			var searchInputContents = document.getElementById('searchInput').value;
			//console.log(searchInputContents);
			var searchArray = searchInputContents.split(/\s+/);
			
			console.log(searchArray);
			
			var biggerWords = searchArray.filter(function(word) {
				return word.length >= 3;
			});
			
			console.log(biggerWords);
			
			if (biggerWords.length > 0) {	
				for (i=0; i < biggerWords.length; i++) { 
						if (biggerWords[i].length < 2) {
							//var doit = [i];
							//biggerWords.splice(doit);
							
						}else{
							$('#title, #desc,#url').wrapInTag({
							  tag: 'strong',
							  words: biggerWords
							});
						}
						
					} 
			}
	  }
	  
    });
	
	
	// Function for finding keywords in search results
	$.fn.wrapInTag = function(opts) {

	  var tag = opts.tag || 'strong'
		, words = opts.words || []
		, regex = RegExp(words.join('|'), 'gi') // case insensitive
		, replacement = '<'+ tag +'>$&</'+ tag +'>';
	
	  return this.html(function() {
		return $(this).text().replace(regex, replacement);
	  });
	};

	// BUTTONS
	
	// Rest Button
	 $('#reset').click(function() {
		$('.hideMe').fadeOut();
        $(':input','#search-form').val('');
		$( ".emptyMe" ).empty();
		$('.rolled').fadeOut();
		$('#desc .ellipsis, #desc .fill').contents().unwrap();
    });
	
	// CHARACTER COUNTER	
	$('#titleInput').keyup(function () {
	  var max = 50;
	  var len = $(this).val().length;
	  if (len >= max) {
		var char = len - 50;
		$('#titleNum').html('Wielkość tytułu przekroczono <span class="number">- ' + char + '</span>');
		$("#titleNum .number").css("background-color","red");
		
	  } else {
		var char = max - len;
		$('#titleNum').html('Wielkość tytułu pozostało <span class="number">' + char + '</span>');
		$("#titleNumc.number").css("background-color","green");
	  }
	});
	$('#descInput').keyup(function () {
	  var max = 155;
	  var len = $(this).val().length;
	  if (len >= max) {
		var char = len - 155;
		$('#descNum').html('Wielkość opisu przekroczono <span class="number">- ' + char + '</span>');
		$("#descNum .number").css("background-color","red");
	  } else {
		var char = max - len;
		$('#descNum').html('Wielkość opisu pozostało <span class="number">' + char + '</span>');
		$("#descNum .number").css("background-color","green");
	  }
	});
	
	
	
	// Stop form submit on enter
	 $(window).keydown(function(event){
		if(event.keyCode == 13) {
		  event.preventDefault();
		  return false;
		}
	  });
});

