$(document).ready(function() {
    $('.datepicker').datepicker({
        language: 'fr',
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
        });    
    $('#genres').select2();
    $('#series').select2();


});