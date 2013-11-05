$(function() {
  function log( message ) {
    $( "<div>" ).text( message ).prependTo( "#log" );
    $( "#log" ).scrollTop( 0 );
  }

  $( "#birds" ).autocomplete({
	source: "search.php",
	minLength: 2,
	select: function( event, ui ) {
	log( ui.item ?
	"Selected: " + ui.item.value + " aka " + ui.item.id :
	"Nothing selected, input was " + this.value );
	}
  });

  /*
  $( "#search" ).autocomplete({
	source: "search.php",
        minLength: 1,
	select: function( event, ui ) {
	  log( ui.item ?
	  "Selected: " + ui.item.value + " aka " + ui.item.id :
	  "Nothing selected, input was " + this.value );
	}
  });
  */

  $( "#gdr_id" ).autocomplete({
	source: "search_gdr.php",
	minLength: 2,
	select: function( event, ui ) {
	  console.log( ui.item ?
	  "Selected: " + ui.item.value + " aka " + ui.item.id :
	  "Nothing selected, input was " + this.value );
	}
  });

  $( "#state_id" ).autocomplete({
        source: "search_state.php",
        minLength: 2,
        select: function( event, ui ) {
          console.log( ui.item ?
          "Selected: " + ui.item.value + " aka " + ui.item.id :
          "Nothing selected, input was " + this.value );
        }
  });

  $( "#lga_id" ).autocomplete({
        source: "search_lga.php",
        minLength: 2,
        select: function( event, ui ) {
          console.log( ui.item ?
          "Selected: " + ui.item.value + " aka " + ui.item.id :
          "Nothing selected, input was " + this.value );
        }
  });

});
