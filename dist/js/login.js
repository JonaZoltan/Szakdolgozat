(function () {
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
}());

// Logout
(function () {
    if ($("#logout-text")[0]) {
        setTimeout(function () {
            $.post("/users/users/logout", {}, function () {
                window.location.reload();
            });
        }, 1000);
    }
}());