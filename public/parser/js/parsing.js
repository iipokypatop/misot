$(document).ready(function () {
    $("#parse").click(function () {
        var text = $("#text").val();
        $("#parse").prop("disabled", true);
        $("#loading").show();

        $.ajax({
            url: "../parsing/parse.php",
            data: {
                text: text
            },
            type: "POST"
        }).success(function (data) {
            $("#res").html(data);
        }).error(function (xhr, status, errorThrown) {
            alert("Sorry, there was a problem!");
            console.log("Error: " + errorThrown);
            console.log("Status: " + status);
            console.dir(xhr);
        }).complete(function (xhr, status) {
            console.log("The request is complete!");
            $("#parse").prop("disabled", false);
            $("#loading").hide();
        })
    });
});