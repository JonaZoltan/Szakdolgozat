// PerfectScrollbar
(function () {
    const demo = document.querySelector('.sidebar');
    if(demo) {
        new PerfectScrollbar(demo);
    }
}());
//PerfectScrollbar end

$("[data-send-password-reset]").click(function () {
    var $btn = $(this);
    var default_caption = $btn.html();
    if (confirm("Biztos szeretne jelszóbeállító e-mailt küldeni?")) {
        $btn.html('<i class="fas fa-spinner fa-spin"></i>').css("width", "100px");
        $.post("/users/users/send-password-reset-email", {
            user_id: $(this).data("user-id")
        }, function () {
            $($btn).html(default_caption).css("width", "auto");
            setTimeout(function () {
                alert("Elküldve!");
            }, 100);
        });
    }
});

$("[data-krajee-grid] .pull-right .summary").closest(".panel-heading").hide();
$("[data-krajee-grid] .kv-panel-after").hide();
$("[data-krajee-grid] .panel-footer").each(function () {
    if (!$(this).text().trim()) {
        $(this).hide();
    }
});
$("[data-krajee-grid] .panel-heading").each(function () {
    if (!$(this).text().trim()) {
        $(this).hide();
    }
});

// Toggle columns GridView
(function () {
    function create_checkboxes(columns) {
        var html = '';
        html += '<div class="btn-group"><div class="dropdown"><button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fas fa-cog"></i><span class="caret"></span></button>';
        html += '<ul class="dropdown-menu checkbox-menu allow-focus" aria-labelledby="dropdownMenu1" style="padding: 10px;">';
        columns.forEach(function (column) {
            html += '<li>';
            html += '<label>';
            html +=      '<input type="checkbox" checked> ' + column + '</label>';
            html +=  '</li>';
        });
        html += '</ul></div></div>';
        return html;
    }
    var saved_table;
    $(".kv-panel-before").each(function () {
        var $panel = $(this);
        var $table = $panel.closest(".grid-view").find(".kv-grid-container table");
        saved_table = $table.html();
        var columns = [];

        $panel.find('.btn-group').css('margin-left', '5px');

        $table.find("th").each(function () {
            columns.push($(this).text());
        });
        $panel.find(".kv-grid-toolbar").html(create_checkboxes(columns) + $panel.find(".kv-grid-toolbar").html());

    });

    $('.kv-panel-before').on('click', '.dropdown button[data-toggle="dropdown"]', function () {
        $(this).dropdown('toggle');
    });
    /*
    $(".kv-panel-before").on("change", ".checkbox-menu input[type='checkbox']", function() {
       $(this).closest("li").toggleClass("active", this.checked);
    });
    */
    $(document).on('click', '.allow-focus', function (e) {
        e.stopPropagation();
    });

    $(".kv-panel-before").on("change", ".checkbox-menu input[type='checkbox']", function() {
        var index = $(this).closest("li").index() + 1;
        var $table = $(this).closest(".grid-view").find(".kv-grid-container table");

        if ($(this).prop("checked")) {
            $table.find("td:nth-child(" + index + "), th:nth-child(" + index + ")").show().removeAttr("data-hide");
        } else {
            $table.find("td:nth-child(" + index + "), th:nth-child(" + index + ")").hide().attr("data-hide", true);
        }
    });
}());

(function () {
    /* user menu */
    $(".main-header .navbar-nav .user").click(function (e) {
        e.stopPropagation();
        $(this).toggleClass("opened");
    });
    $(".main-header .navbar-nav .user .user-menu").click(function (e) {
        e.stopPropagation();
    });
    $(document).click(function () {
        $(".main-header .navbar-nav .user").removeClass("opened");
    });
    /* user menu end */

    /* Preload */
    $('.btn-preload').on('click', function() {
        let $this = $(this);
        let loadingText = '<span class="preload-content"><i class="fas fa-sync fa-spin"></i> loading...</span>';

        if ($(this).html() !== loadingText) {
            $this.data('original-text', $(this).html());
            $this.html(loadingText);
        }

        setTimeout(function() {
            $this.html($this.data('original-text'));
        }, 5000);
    });
    /* Preload end */

    /* Slided */
    (function () {
        $(".sidebar-mini").click(function () {
            var classes = $("body").hasClass("sidebar-collapse");

            if (classes) {
                Cookies.set('slided', true);
            } else {
                Cookies.remove('slided');
            }
        });
    }());
    /* Slided end */

    (function () {
        // Lenyitja az aktuális menü kategóriát és kijelöli az aktuális menüt
        let path = window.location.pathname;
        const path_and_query = window.location.pathname + window.location.search;
        const path_parts = path.split("/");
        //if (path_parts.length >= 3 && path !== '/users/users/home') {
        if (path_parts.length >= 3 && path !== '/users/users/home') {
            if (path_parts[1] + "/" + path_parts[2] === "statistic/statistic") {
                path = path_and_query;
            } else {
                path = "/" + path_parts[1] + "/" + path_parts[2] + "/" + path_parts[3];
            }
        }
        //$(".admin-layout.sidebar .menu-item").each(function () {
        $('.main-sidebar .nav-link').each(function () {
            let $this = $(this);
            let exact_match = typeof $this.data("exact-match") === "string";

            if (exact_match ? $this.attr("href") === window.location.pathname : $this.attr("href").indexOf(path) >= 0) {
                /*if(!$this.hasClass('nemkell')) {
                    $(this).addClass("selected");
                }*/

                // Saját magának adunk egy "active";
                $this.addClass("active");
                // Fölötte a <li> adunk egy menu-open-t
                $this.closest(".menu").addClass("menu-open");
                // Menu alatti <a> tag kövérítés
                $this.closest(".menu").children('a').addClass("active");
                // Legfelső <li> adunk egy menu-open-t
                $this.closest(".menu-group").addClass("menu-open");

                /*$(this).closest(".menu-group").prev().addClass("selected opened");
                $(this).closest(".menu-group").show();
                $(this).closest(".menu-section-content").prev().addClass("opened")
                $(this).closest(".menu-section-content").show();*/
                /*setTimeout(function () {
                    $this.closest(".menu").scrollTop($this.closest(".menu-group").prev(".menu-category").position().top);
                    ps.update();
                }, 100);*/
            }
        });
    }()); /* menu-open end */

    (function () {
        $('[data-summernote]').summernote({
            lang: 'hu-HU',
            height: 150,
            toolbar: [
                ['font', ['bold', 'underline', 'italic', 'clear']],
                ['para', ['ul', 'ol']],
            ]
        });
    }());

}());



// Hexa color alapján megállapítja a betű színét.
function hexFontColor(hex) {
    // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
    var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(shorthandRegex, function(m, r, g, b) {
        return r + r + g + g + b + b;
    });

    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

    if(!result)
        return null;

    var brightness = Math.round(((parseInt(result[1], 16) * 299) + (parseInt(result[2], 16) * 587) + (parseInt(result[3], 16) * 114)) / 1000)

    return brightness > 125 ? 'black' : 'white';
}