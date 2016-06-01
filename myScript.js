$(document).ready(function() {
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



    var p = document.getElementById("valmis");
    var vorm = document.getElementById("valik");
    vorm.onsubmit = validateForm;

    p.onclick = function() {

        if ($("#algus").datepicker("getDate") != null && $("#l6pp").datepicker("getDate") != null) {
            alert("Broneeritud! Võtame teiega esimesel võimalusel ühendust!");


        } else {
            alert("Palun vali kuupäev");

        }
    }

    function validateForm() {
        if ($("#algus").datepicker("getDate") != null && $("#l6pp").datepicker("getDate") != null) {
            return true;
        } else {
            return false;
        }
    }

});


