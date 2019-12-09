// admin.js file

$(document).ready(function(){
    var my_duration = 100;

    // $('#notify').hide();

  if( getUrlParameter('id')){
      $('#notify').addClass('success').html('New admin account created successfully.').show();
  };

  if(getUrlParameter('login')){
      $('#notify').addClass('error').html('Invalid email address OR password').show();
  }
  

 function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};


// Admin panel listener
$('.panel .aEdit').click( function(){
    
    // clear options from duplicating
    $('#aEdit #role').children().remove().end();
    
    selectValues = {"1":"Admin", "2":"Manager"}; // TODO:make this a db call.
    admin_id    = $(this).data('admin-id');
    admin_name  = $(this).closest('.row').find('.adminName').text();
    admin_email = $(this).closest('.row').find('.email').text();
    admin_role  = $(this).closest('.row').find('.role').text();
    status      = $(this).closest('.row').find('.status').data('status');
   
    console.log('status onload = ' + status);
       
    // Populate fields in dialog
    $('#aEdit #admin_id').val(admin_id);
    $('#aEdit #adminName').val( admin_name );
    $('#aEdit #email').val( admin_email );

    if(status == 1){
        $('#aEdit #status').prop('checked', true); // set check if checked 
    }
   
   
    // iterate selectValues, and match on admin_role, select option matched
    $.each(selectValues, function(key, value){
        selectedOption = value === admin_role ? true : false;
        $('#aEdit #role').append($("<option></option") 
                        .attr('selected', selectedOption)
                        .attr("value", key)                    
                        .text(value)
        );
    });
    // Fire the weapon, Mr. Sulu..
    $('#aEdit')
                .data('admin_id', admin_id)
                .data('adminName', admin_name)
                .data('email', admin_email)
                .data('role', admin_role)
                .data('status', status)
                .dialog('option','title','Edit ' + admin_name) .dialog('open');
    });

    // customer Edit Dialog 
    $( "#aEdit" ).dialog({
        autoOpen: false,
        modal: true,
        open: function() {
            $('.ui-widget-overlay').addClass('custom-overlay');
            // place check box if status=1
            if(status == 1){
                $('#aEdit #status').prop('checked', true); // set checkbox if status=1
            }
        },   
        width: 500,
        title: 'Edit ' + $("#aEdit").data('adminName'),
        height: 400,
        hide: { effect: "clip", duration: my_duration },
        buttons: {
            "Save" : function() {
                // // capture and update values
                admin_id        = $('#aEdit').data('admin_id');
                adminName       = $('#aEdit #adminName').val();
                email           = $('#aEdit #email').val();
                role            = $('#aEdit #role').val();
                status          = 0; // default

               // get status checkbox property on close
               if( $('#aEdit #status').is(':checked')){ 
                   status =1;
               }

                               
                
                

                // post it to jarvis... 
                $.post( "jarvis.php", { 
                            action: "updateAdmin",                       
                            id: admin_id,
                            adminName: adminName,
                            email: email,
                            role: role,
                            status: status
                                            
                        }).done(function( data ) {                         
                            console.log(data);
                        });
                        
                        // close dialog and reload
                        $(this).dialog('close');   
                        setTimeout( function(){ location.reload();  }, 100);
                        
                    },
                    Cancel: function(){
                        $(this).dialog('close');
                    }
                }
            });







}); // document.ready