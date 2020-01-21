$(document).ready(function() { 
  /* Get list of usernames */
  $('#username-search').autocomplete({
  	delay : 300,
  	minLength: 2,
  	source: function (request, response) {
         $.ajax({
             type: "GET",
             url: global_base_url + "home/get_usernames",
             data: {
             		query : request.term
             },
             dataType: 'JSON',
             success: function (msg) {
                 response(msg);
             }
         });
      }
  });
  $('#username-search2').autocomplete({
    delay : 300,
    minLength: 2,
    source: function (request, response) {
         $.ajax({
             type: "GET",
             url: global_base_url + "home/get_usernames",
             data: {
                query : request.term
             },
             dataType: 'JSON',
             success: function (msg) {
                 response(msg);
             }
         });
      }
  });  
});