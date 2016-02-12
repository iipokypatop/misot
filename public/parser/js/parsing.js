$(document).ready(function () {
    console.log("ready!");


    $("#parse").click(function () {

        console.log("click!");
        var text = $("#text").val();

        $.ajax({
            url: "../parsing/parse.php",
            data: {
                text: text
            },
            type: "POST",
            success: function (data) {

                $("#res").html(data);
                console.log(data);
                //$( "<h1>" ).text( json.title ).appendTo( "body" );
                //$( "<div class=\"content\">").html( json.html ).appendTo( "body" );
            },
            error: function (xhr, status, errorThrown) {
                alert("Sorry, there was a problem!");
                console.log("Error: " + errorThrown);
                console.log("Status: " + status);
                console.dir(xhr);
            },
            complete: function (xhr, status) {
                console.log("The request is complete!");
            }
        });
    });
});