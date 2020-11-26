jQuery(document).ready(function () {
  var input_field, input_field_id;
  var item_selector = "$('.ListItems__listItem')";

  var availableTags = <?php json_encode($locations); ?>;
  console.log(availableTags);
  let url = '/zeebavans/wp-admin/admin-ajax.php';
  let search_data = '';
  let data = {
          'action': 'showLocation',
  };
  jQuery.post(url, data, function(response) {
      availableTags = response.name;
      console.log(response);
  });

  $( "input[name=pick-up-location-search]" ).on('keyup',function(e){
      input_field = "input[name=pick-up-location-search]";
      input_field_id = 'input[name=pick-up-location-id]';
      s = $( "input[name=pick-up-location-search]" ).val();
      if(s.length > 0){
          $('#pickup-location-search-container .ListItems__stationListScrolling').empty();
          for (var i = availableTags.length - 1; i >= 0; i--) {
              if(availableTags[i].search(s)>=0){
                  $('#pickup-location-search-container .ListItems__stationListScrolling').append('<option value="'+availableTags[i]+'" class="ListItems__listItem groupedListItem">'+availableTags[i]+'</option>');
              }
          }
      }
      $('#pickup-location-search-container .NoSearchResults__noResult').hide();
      hidePopUp();
      // arrowSelect();
  });
 
  $( "input[name=drop-off-location-search]" ).on('keyup',function(){
      input_field = "input[name=drop-off-location-search]";
      input_field_id = 'input[name=drop-off-location-id]';
      s = $( "input[name=drop-off-location-search]" ).val();
      if(s.length > 0){
          $('.ListItems__stationListScrolling').empty();
          for (var i = availableTags.length - 1; i >= 0; i--) {
              if(availableTags[i].search(s)>=0){
                  $('.ListItems__stationListScrolling').append('<div class="ListItems__listItem groupedListItem">'+availableTags[i]+'</div>');
              }
          }
          $('.NoSearchResults__noResult').hide();
      }
      hidePopUp();
  });
  

  $('.Overlay__wrapper').on('click', function(){
      $('.SearchEnginePopup__container').parent().hide();
      $('.SearchEngine__searchEngine nav').removeClass('SearchEngine__searchEngineHasOpenPicker');
  });
  $( "input[name=pick-up-location-search], input[name=drop-off-location-search]" ).on('focus',function(){
      $('.NoSearchResults__noResult').hide();
      $('.SearchEnginePopup__container').parent().show();
      $('.SearchEngine__searchEngine nav').addClass('SearchEngine__searchEngineHasOpenPicker');
  });

  arrowSelect();
  hidePopUp();
  var next = 0, text='', text_id='';

  function arrowSelect(){
      $(window).on('keyup', function(e){
          var selected;
          if(e.which == 40){
              if(next == 0){
                  $('.ListItems__listItem').eq(0).addClass('ListItems__listItemSelected');
                  next = 1;
                  text = $('.ListItems__listItem').eq(0).text();
                  text_id = $('.ListItems__listItem').eq(0).attr('value');
              }
              else{
                  $('.ListItems__listItem').eq(next - 1).removeClass('ListItems__listItemSelected');
                  $('.ListItems__listItem').eq(next).addClass('ListItems__listItemSelected');
                  text = $('.ListItems__listItem').eq(next).text();
                  text_id = $('.ListItems__listItem').eq(next).attr('value');
                  next=next+1;
                  if($('.ListItems__listItem').length == next)
                      next = 0;
              }
          }
          else if(e.which == 38){
              if(next>0){
                  $('.ListItems__listItem').eq(next).removeClass('ListItems__listItemSelected');
                  $('.ListItems__listItem').eq(next-1).addClass('ListItems__listItemSelected');
                  text = $('.ListItems__listItem').eq(next-1).text();
                  next=next-1;
              }
          }
          else if(e.which == 13){
              // text = $(this).text();
              $(input_field).val(text);
              $(input_field_id).val(text_id);
              $('.SearchEnginePopup__container').parent().hide();
              $('.SearchEngine__searchEngine nav').removeClass('SearchEngine__searchEngineHasOpenPicker');
          }
      });
  }

  function hidePopUp(){
      $( ".ListItems__listItem" ).on('click',function(){
          text = $(this).text();
          console.log(input_field);
          $(input_field).val(text);
          $(input_field_id).val($(this).attr('value'));
          $('.SearchEnginePopup__container').parent().hide();
          $('.SearchEngine__searchEngine nav').removeClass('SearchEngine__searchEngineHasOpenPicker');
      });
      $('.hide-popup').on('focus', function(){
         $('.SearchEnginePopup__container').parent().hide();
         $('.SearchEngine__searchEngine nav').removeClass('SearchEngine__searchEngineHasOpenPicker'); 
      });
  }
});