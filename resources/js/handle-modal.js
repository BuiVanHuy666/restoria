$(document).ready(function() {
    $(document).on('click', '[data-toggle="restoria-modal"]', function(e) {
        e.preventDefault();
        const targetId = $(this).data('target');
        const $modal = $(targetId);

        if ($modal.length) {
            $modal.addClass('show');
            $('body').css('overflow', 'hidden');
        }
    });

    $(document).on('click', function(e) {
        const $target = $(e.target);

        if (
            $target.hasClass('restoria-modal') ||
            $target.closest('.restoria-modal-close').length ||
            $target.attr('data-dismiss') === 'modal'
        ) {
            const $modal = $target.closest('.restoria-modal').length
                ? $target.closest('.restoria-modal')
                : $('.restoria-modal.show');

            $modal.removeClass('show');

            if ($('.restoria-modal.show').length === 0) {
                $('body').css('overflow', '');
            }
        }
    });
});
