jQuery(document).ready(function(){
  $('.deleteAccount').on('click', function(e) {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      var dataId = $(this).attr('data-id');
      $.ajax({
          url: '/account/delete/'+dataId,
          type: 'POST',
          data: {
            "id": dataId
          },
          success: function( msg ) {
            if ( msg.status === 'success' ) {
                setInterval(function() {
                    window.location.reload();
                }, 1000);
            }
          },
          error: function( data ) {
              
          }
      });

      return false;
  });
});