// admin.js file

// $(document).ready(function(){
  if( getUrlParameter('id')){
      $('#notify').html('New admin account created successfully.');
  };

  if(getUrlParameter('login')){
      $('#notify').html('Bad email or password.');
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

// });