$(function() {

  // events

  $( "html" ).keypress(function(event) {
    if ( event.which == 103 ) thumbMode();
  });
  $( "#searchForm" ).submit(function( event ) {
    event.preventDefault();
    get_media($( this ));
  });
  
  // functions
  
  function scanmode(){
    // s field ready
    $("#code_input").focus();
    $("#code_input").val('');
  }
  function thumbMode(){
    // fs images to tumbnails
    $( "body" ).toggleClass( "mini" );
  }
  function get_media($form){
    
    var term = $form.find( "input[name='s']" ).val(),
    url = $form.attr( "action" );
    
    console.log('scanned_id :: '+term);
    $(document).attr('title',term ); 
    
    var posting = $.post( url, { s: term } );
    
    posting.done(function( data ) {
      $( "#result" ).prepend( data );
      scanmode();
    });
  }

  scanmode();
  
})