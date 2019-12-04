
$(function () {
    // edit video Dialog Object and form update
    // ----------------------------------------
    $( "#vEdit").dialog({
        autoOpen: false,
        modal: true,
        width: 800,
        height: 300,
        title: "edit Video",
        buttons: {
            "Save" : function() {
                // capture and update values
                video_title = $('input#video_title').val();
                youtube_id   = $('input#youtube_id').val();
                video_id     = $('#dataContainer').data('video-id');
                customerId  = $('#dataContainer').data('customer-id');
                campaignId  = $('#dataContainer').data('campaign-id');

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
        vTitle     = $(this).closest('.flex-table').find('#video_title').text();
        youtubeId  = $(this).closest('.flex-table').find('#youtube_id').text();
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
        width: 800,
        height: 300,
        title: "Add A New Youtube Video",
        buttons: {

            "Save" : function() {

                // capture and update values of form
                video_title  = $('#newVideo input#video_title').val();
                youtube_id   = $('#newVideo input#youtube_id').val();
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


    // delete a video
    // ----------------------
    $( "#vDelete").dialog({
        autoOpen: false,
        modal: true,
        width: 500,
        height: 250,
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
    width: 750,
    height: 650,
    // title: "Watch",
    buttons: {

        Close: function(){          
            // reset the ifram src to stop playback
            $("#dataContainer iframe").attr("src", $("#dataContainer iframe").attr("src"));
            $(this).dialog('close');
        }
    }
});

    // videoLink click listener
    // ------------------------------

    $('.vLink').click(function(){
        youtube_id  = $(this).closest('.flex-table').find('#youtube_id').text();
        vTitle     = $(this).closest('.flex-table').find('#video_title').text();

        // set src of the video
        $('#vLink iframe').attr("src", 'https://www.youtube.com/embed/' + youtube_id);

        // set the title of the dialog window
        $('#vLink').dialog('option','title', vTitle).dialog('open');
    });

});


  