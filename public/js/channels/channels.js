var animate = "dashboard";

$(document).ready(function() {
    $('#dashboard').click(function() {
        if(animate !== "dashboard") {
            event.preventDefault();
            $('.settings, .userSettings').find('.arrow-down').css('top', "-13px");

            $('section').transition({
                height: $(window).height() - 60 + "px",
                overflowY: "hidden"
            }, 400, function() {
                $('section').css("overflow-y", "auto");
            });
            $('.settings, .userSettings').transition({
                height: 0
            }, 400);

            animate = "dashboard";
        }
    });

    $('#settings').click(function(event) {
        event.preventDefault();

        $('.settings, .userSettings').find('.arrow-down').css('top', "-13px");

        $('.settings').transition({
            height: animate == "settings" ? 0 : $(window).height() - 60 + "px"
        }, 400, function() {
            animate == "settings" ? $(this).find('.arrow-down').css('top', "-1px") : $(this).find('.arrow-down').css('top', "-13px");
        });
        $('.userSettings').transition({
            height: 0
        }, 400);
        $('section').transition({
            height: animate == "settings" ? $(window).height() - 60 + "px" : 0,
            overflowY: "hidden"
        }, 400, function() {
            $('section').css("overflow-y", animate == "settings" ? "hidden" : "auto");
        });

        animate != "settings" ? animate = "settings" : animate = "dashboard";
    });

    $('#userSettings').click(function(event) {
        event.preventDefault();
         
        $('.settings, .userSettings').find('.arrow-down').css('top', "-13px");

        $('.userSettings').transition({
            height: animate == "userSettings" ? 0 : $(window).height() - 60 + "px"
        }, 400, function() {
            animate == "userSettings" ? $(this).find('.arrow-down').css('top', "-1px") : $(this).find('.arrow-down').css('top', "-13px");
        });
        $('.settings').transition({
            height: 0
        }, 400);
        $('section').transition({
            height: animate == "userSettings" ? $(window).height() - 60 + "px" : 0,
            overflowY: "hidden"
        }, 400, function() {
            $('section').css("overflow-y", animate == "userSettings" ? "hidden" : "auto");
        });

        animate != "userSettings" ? animate = "userSettings" : animate = "dashboard";
    });
});

$(window).on('resize', function(){
    var height = $(window).height() - 60;

    if(animate == "dashboard") {
        $('section').height(height);
    } else if(animate == "settings") {
        $('.settings').height(height);
    } else if(animate == "userSettings") {
        $('.userSettings').height(height);
    }
});
