
$(function () {

    // img.youtube.com/vi/[VideoID]/maxresdefault.jpg -- thumbnail for youtube_id
    // -------------------------------------------------------------------------

    var my_duration = 100;

    // edit video Dialog Object and form update
    // ----------------------------------------
    $( "#vEdit").dialog({
        autoOpen: false,
        modal: true,
        open: function() {
            $('.ui-widget-overlay').addClass('custom-overlay');
        }, 
        width: 800,
        height: 300,
        hide: { effect: "clip", duration: my_duration },
        title: "edit Video",
        buttons: {
            "Save" : function() {
                // capture and update values
                video_title         = $('input#video_title').val();
                youtube_id          = $('input#youtube_id').val();
                youtube_formatted   = matchYoutubeUrl(youtube_id);
                youtube_id          = youtube_formatted == false ? youtube_id : youtube_formatted;
                video_id            = $('#dataContainer').data('video-id');            
                customerId          = $('#dataContainer').data('customer-id');
                campaignId          = $('#dataContainer').data('campaign-id');

                $('#dataContainer').html('Saving...');

                $.post( "jarvis.php", { 
                        action: "updateVideo",                       
                        video_id: video_id,
                        video_title: video_title,                 
                        youtube_id: youtube_id
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
        
    // video edit listener and modal data form populator
    // -------------------------------------------------

    $('.vEdit').click(function(){
        vTitle     = $(this).closest('.flex-table').find('#video_title').text().trim();
        youtubeId  = $(this).closest('.flex-table').find('#youtube_id').text().trim();
        customerId = $(this).closest('.flex-table').data('customer-id');
        campaignId = $(this).closest('.flex-table').data('campaign-id');
        videoId    = $(this).closest('.flex-table').data('video-id');

        // assign values to inputs and create datacontainer data set.
        $('#vEdit #video_title').val( vTitle );
        $('#vEdit #youtube_id').val( youtubeId );
        $('#vEdit #dataContainer').attr('data-video-id', videoId);
        $('#vEdit #dataContainer').attr('data-customer-id', customerId);
        $('#vEdit #dataContainer').attr('data-campaign-id', campaignId);
        $('#vEdit').dialog('open');
    });

    // new video Dialog and form insert
    // --------------------------------
    $( "#newVideo").dialog({
        autoOpen: false,
        modal: true,
        open: function() {
            $('.ui-widget-overlay').addClass('custom-overlay');
        }, 
        width: 800,
        height: 300,
        hide: { effect: "clip", duration: my_duration },
        title: "Add A New Youtube Video",
        buttons: {

            "Save" : function() {

                // capture and update values of form
                video_title  = $('#newVideo input#video_title').val().trim().replace(/'/g, "`"); // escape the apostrphe
                youtube_id   = $('#newVideo input#youtube_id').val().trim();
                youtube_formatted = matchYoutubeUrl(youtube_id);
                youtube_id = youtube_formatted == false ? youtube_id : youtube_formatted;
                campaign_id  = $('#newVideo #dataContainer').data('campaign-id');            
                
                $('#dataContainer').html('Saving...');

                $.post( "jarvis.php", { 
                        action: "insertVideo",                       
                        video_title: video_title,                 
                        youtube_id: youtube_id,
                        campaign_id: campaign_id

                    }).done(function( data ) {
                        console.log(' insert: ' +  data );
                    });
                    
                    // close dialog and reload
                    $(this).dialog('close');   
                    
                    // wait for it..
                    setTimeout( function(){ location.reload(); }, 100);
                    
            },
            Cancel: function(){
                $(this).dialog('close');
            }
        }
    });

    // Add a video to existing Campaign
    // --------------------------------
    $('.newVideo').click(function(){
        campaign_id = $(this).data('campaign-id'); // get campaign_id of element clicked      
        $('#newVideo #dataContainer').attr('data-campaign-id', campaign_id); // set campaign_id in dialog      
        $('#newVideo').dialog('open');
    });



    // =======================================================
    // delete a video
    // ----------------------
    $( "#vDelete").dialog({
        autoOpen: false,
        modal: true,
        open: function() {
            $('.ui-widget-overlay').addClass('custom-overlay');
        }, 
        width: 500,
        height: 250,
        hide: { effect: "clip", duration: my_duration },
        title: "Delete A Video",
        buttons: {

            "DELETE" : function() {
              
                // delete a video from a campaign
                video_id  = $('#vDelete #dataContainer').data('video-id');            
                $('#dataContainer').html('Deleting...');

                $.post( "jarvis.php", { 
                        action: "deleteVideo",                       
                        video_id: video_id                                        
                    }).done(function( data ) {
                        console.log('Records deleted: ' +  data );
                    });
                    
                    // close dialog and reload
                    $(this).dialog('close');   
                    
                    // brief timeout for slow connections to webserver.
                    setTimeout( function(){ location.reload(); }, 100);
                    
            },
            Cancel: function(){
                $(this).dialog('close');
            }
        }
    });

    // Delete video click listener
    // ---------------------------
    $('.vDelete').click(function(){
      
        video_id   = $(this).closest('.flex-table').data('video-id');
        vTitle     = $(this).closest('.flex-table').find('#video_title').text();
        youtube_id  = $(this).closest('.flex-table').find('#youtube_id').text();

        $('#vDelete #dataContainer').attr('data-video-id', video_id); // set video_id in dialog  
        $('#vDelete #dataContainer .vTitle').text('Video Title: ' + vTitle); // set video Title in dialog  
        $('#vDelete #dataContainer .youtube_id').text('Youtube ID: ' + youtube_id);
        
        $('#vDelete').dialog('open');
    });

// vLink viewer dialog
// --------------------

$( "#vLink").dialog({
    autoOpen: false,
    modal: true,
    open: function() {
        $('.ui-widget-overlay').addClass('custom-overlay');
    }, 
    width: 750,
    height: 650,
    hide: { effect: "clip", duration: my_duration },
    // title: "Watch",
    buttons: {

        Close: function(){          
            // reset the ifram src to stop playback
            $("#dataContainer iframe").attr("src", $("#dataContainer iframe").attr("src"));
            $(this).dialog('close');
        }
    }
});

    // videoLink click listener preview modal
    // --------------------------------------

    $('.vLink').click(function(){
        youtube_id  = $(this).closest('.flex-table').find('#youtube_id').text();
        vTitle     = $(this).closest('.flex-table').find('#video_title').text();

        // set src of the video
        $('#vLink iframe').attr("src", 'https://www.youtube.com/embed/' + youtube_id);

        // set the title of the dialog window
        $('#vLink').dialog('option','title', vTitle).dialog('open');
    });




// =========== CUSTOMERS DASHBOARD LISTENERS AND FUNCTIONS ===================
      
     
    // customer details Edit Dialog 
    $( "#cEdit" ).dialog({
        autoOpen: false,
        modal: true,
        open: function() {
            $('.ui-widget-overlay').addClass('custom-overlay');
        }, 
        width: 600,
        height: 500,
        hide: { effect: "clip", duration: my_duration },
        buttons: {
            "Save" : function() {
                // capture and update values
                customer_id = $('#cEdit').data('customer_id');
                customer_contact_name = $('#customer_contact_name').val();
                customer_contact_email = $('#customer_contact_email').val();
                customer_contact_phone = $('#customer_contact_phone').val();
                customer_website_url = $('#customer_website_url').val();
                
                $('.status').text('Saving...' + customer_contact_name );
                
                $.post( "jarvis.php", { 
                            action: "updateCustomer",                       
                            customer_id: customer_id,
                            customer_website_url: customer_website_url,
                            customer_contact_name: customer_contact_name,
                            customer_contact_email: customer_contact_email,
                            customer_contact_phone: customer_contact_phone
                    
                        }).done(function( data ) {                         
                            console.log( 'affected rows: ' +  data );
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

            // Customer Edit Click Listener
            // ================================

            $('.cEdit').click(function(){

               var customerId = $(this).data('customer-id');
                // grab the variables from the existing displayed data
                customer_name        = $(this).closest('.row').find('.customer_id').data('customer-name');
                customer_contact_name = $(this).closest('.row').find('.customer_contact_name').text();
                customer_contact_email = $(this).closest('.row').find('.customer_contact_email').text();
                customer_contact_phone = $(this).closest('.row').find('.customer_contact_phone').text();
                customer_website_url = $(this).closest('.row').find('.customer_website_url').text();

                // populate form fields in HTML dialog with existing data
                $('#cEdit #customer_contact_name').val( customer_contact_name );
                $('#cEdit #customer_contact_email').val( customer_contact_email );
                $('#cEdit #customer_contact_phone').val( customer_contact_phone );
                $('#cEdit #customer_website_url').val( customer_website_url );

                // open the dialog, passing our values as .data
                $('#cEdit')
                .data('customer_id', customerId)
                .data('customer_name', customer_name)
                .dialog('option','title','Edit ' + customer_name) .dialog('open');
            });


            // Add New Customer Dialog 
            // ================================

            $( "#addCustomer").dialog({
                autoOpen: false,
                modal: true,
                open: function() {
                    $('.ui-widget-overlay').addClass('custom-overlay');
                }, 
                width: 550,
                height: 550,
                title: "Add A New Customer",
                hide: { effect: "clip", duration: my_duration },
                buttons: {
            
                    "Save": function(){          

                        adminId = $('#addCustomer').data('adminId'); // get out set data
                        customer_name = $('#addCustomer #customer_name').val();
                        customer_contact_name = $('#addCustomer #customer_contact_name').val();
                        customer_contact_email = $('#addCustomer #customer_contact_email').val();
                        customer_contact_phone = $('#addCustomer #customer_contact_phone').val();
                        customer_website_url = $('#addCustomer #customer_website_url').val();

                        $.post( "jarvis.php", { 
                            action: "insertCustomer",                       
                            customer_name: customer_name,
                            customer_contact_name: customer_contact_name,
                            customer_contact_email: customer_contact_email,
                            customer_contact_phone: customer_contact_phone,
                            customer_website_url: customer_website_url,
                            admin: adminId,
                            status: 1
                    
                        }).done(function( data ) {                         
                            console.log( 'Inserted : ' +  data );
                        });
                        
                         // close dialog and reload
                         $(this).dialog('close');   
                         setTimeout( function(){ location.reload();  }, 100);

                    },
                    Cancel:function(){
                        $(this).dialog('close');
                    }
                },
                
            });

            // Customer Add Click Listener
            $('.addCustomer').click(function(){
                adminId = $(this).data('admin-id');
                $('#addCustomer').data('admin-id', adminId).dialog('open');
            });

    // New Campaign Dialog Form HTML 
    // ============================
    $( "#newCampaign").dialog({
        autoOpen: false,
        modal: true,
        width: 550,
        height: 550,
        title: "Add A New Pi Client",
        overlay: {
            opacity: 0.2,
            background: "black"
        },
        buttons: {
    
            "Save": function(){          

                // Variable passed through .data
                $('#newCampaign #customer_id').val( $(this).data('customer_id') );
                $('#newCampaign #admin_id').val( $(this).data('admin_id') );

                $.post( "jarvis.php", { 
                    action: "insertCampaign",                       
                    customer_id: $(this).data('customer_id'),
                    admin_id: $(this).data('admin_id'),
                    campaign_name: $('#newCampaign #campaign_name').val(),
                    client_id : $('#newCampaign #client_id').val(),
                    status: 1
        
                }).done(function( data ) {                         
                    console.log( 'Inserted : ' +  data );
                });
                
                    // close dialog and reload
                    $(this).dialog('close');   
                    setTimeout( function(){ location.reload();  }, 100);

            },
            Cancel:function(){
                $(this).dialog('close');
            }
        },
        
    });



    // New Campaign Link Listener
    // ================================
    $('.newCampaign').click(function(){
        customer_id = $(this).data('customer_id');
        admin_id= $(this).data('admin_id');

        $('#newCampaign')
        .data('customer_id', customer_id)
        .data('admin_id', admin_id)
        .dialog('open');
    })


    // PI_UID Link listener
    // ==============================
    $('.PI_UID').click(function(){
       
       var client_id = $(this).text();
        console.log('clicked on ' + client_id);
        $('#pistat')
        .data('client_id', client_id)
        .dialog('option','title', 'Stats for ' + client_id)
        .dialog('open');

    });

    // PI_UID dialog Report HTML -- fortified with geo location.
    // ========================================================

    $( "#pistat").dialog({
        autoOpen: false,
        modal: true,
        width: 700,
        height: 550,
        hide: { effect: "clip", duration: my_duration },
        open: function(){
                      
            $('.info').html('<h4>Request Log</h4>');

            // query jarvis for data on client_id
            $.post( "jarvis.php", { 
                action: "fetchClientMonitor",                       
                client_id: $('#pistat').data('client_id')
    
            }).done(function( data ) {                         
            
                // mysqli query:
                // "SELECT * from Monitor WHERE `client_id` IN ('$client_id')";

                data = $.parseJSON(data);            
                
                if(data.length === 0) $('.info').html('<div>Thats a 404 on requests from '+ $('#pistat').data('client_id') + '</div>');
                
            $.each(data, function(i, item) {

                // dateformat request_date 
                formatDate = new Date(item.request_date);
                d          = formatDate.getDate();
                m          = formatDate.getMonth();
                m          += 1;
                y          = formatDate.getFullYear();
                y          = String(y).substr(2,4); // 2-digit year
                hour       = formatDate.getHours();
                min        = formatDate.getMinutes();

                   $('.info').append(
                    '<div>'+ m +'/'+d+'/'+y+' '+hour+':'+min+ ':' + item.request + ' loc: ' + item.city + ','+ item.state + '</div>');
                });
            });
        },

        buttons: {
            Close:function(){
                    $(this).dialog('close');
                }
            }
        });




// Youtube url parse
function matchYoutubeUrl(url){
    var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
     return (url.match(p)) ? RegExp.$1 : false ;
    }


});// document            