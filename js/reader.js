$(function() {
  $("#code_input").focus();
    $( "#searchForm" ).submit(function( event ) {

    event.preventDefault();

    var $form = $( this ),
    term = $form.find( "input[name='s']" ).val(),
    url = $form.attr( "action" );
    
    $(document).attr('title',term );
    
    var posting = $.post( url, { s: term } );
    
    posting.done(function( data ) {
      $( "#result" ).prepend( data );
      $("#code_input").focus();
      $form.find( "input[name='s']" ).val('');
    });
  });
})