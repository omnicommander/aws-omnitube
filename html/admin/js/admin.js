// admin.js file

$(document).ready(function(){
    $('#notify').hide();

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




}); // document.ready