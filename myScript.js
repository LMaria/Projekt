$(document).ready(function(){
  $(function() {
$("#algus").datepicker();
$("#format").change(function() {
$("#algus").datepicker("option", "dateFormat", $(this).val());

});
   });  
    $(function() {
$("#l6pp").datepicker();
$("#format").change(function() {
$("#l6pp").datepicker("option", "dateFormat", $(this).val());

});
   });  
  });
  
