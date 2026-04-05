(function ($) {
    'use strict';

    /* =====================================================
     * MARQUEE
     * ===================================================== */
    function initCarousel($outer) {
        var $track    = $outer.find('.lcw-track');
        var direction = $outer.data('row-direction') || $outer.data('direction') || 'left';
        var speed     = parseFloat($outer.data('speed')) || 60;
        var pauseHov  = String($outer.data('pause')) === 'true';

        function applyAnimation() {
            var totalW = $track[0].scrollWidth;
            var setW   = totalW / 4;
            if (setW <= 0) return;
            var dur    = setW / speed; 
            if (dur <= 0) return;
            var anim   = direction === 'right' ? 'lcw-marquee-right' : 'lcw-marquee-left';

            $track.css({
                'animation'          : anim + ' ' + dur + 's linear infinite',
                '-webkit-animation'  : anim + ' ' + dur + 's linear infinite'
            });
        }

        applyAnimation();

        // FIXED RACE CONDITION: Check if images are already cached
        $track.find('img').each(function() {
            if (this.complete) {
                applyAnimation();
            } else {
                $(this).on('load', debounce(applyAnimation, 100));
            }
        });

        if (pauseHov) {
            $outer
                .on('mouseenter.lcw touchstart.lcw', function () { $outer.addClass('lcw-paused'); })
                .on('mouseleave.lcw touchend.lcw',   function () { $outer.removeClass('lcw-paused'); });
        }

        $(window).on('resize.lcw', debounce(applyAnimation, 200));
    }

    function initAll() {
        $('.lcw-track-outer').each(function () {
            var $el = $(this);
            if (!$el.data('lcw-inited')) {
                $el.data('lcw-inited', true);
                initCarousel($el);
            }
        });
    }

    /* =====================================================
     * YOUTUBE MODAL
     * ===================================================== */
    var $modal   = null;
    var $iframe  = null;

    function createModalIfNeeded() {
        if ($('#lcw-modal').length === 0) {
            $('body').append(
                '<div class="lcw-modal-overlay" id="lcw-modal" style="display:none;" tabindex="-1" role="dialog" aria-modal="true" aria-label="Video">' +
                    '<div class="lcw-modal-inner">' +
                        '<button class="lcw-modal-close" aria-label="Close">&times;</button>' +
                        '<div class="lcw-modal-video">' +
                            '<iframe id="lcw-modal-iframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            );
        }
        $modal  = $('#lcw-modal');
        $iframe = $('#lcw-modal-iframe');
    }

    function openModal(ytId) {
        createModalIfNeeded();

        var src = 'https://www.youtube.com/embed/' + ytId + '?autoplay=1&rel=0&modestbranding=1';

        $iframe.attr('src', src);
        $modal.fadeIn(200);
        $('body').addClass('lcw-modal-open');
        $modal.focus();
    }

    function closeModal() {
        if (!$modal) return;
        $iframe.attr('src', '');
        $modal.fadeOut(200);
        $('body').removeClass('lcw-modal-open');
    }

    function bindModal() {
        createModalIfNeeded();

        $(document)
            .on('click.lcw keydown.lcw', '.lcw-has-yt', function (e) {
                if (e.type === 'keydown' && e.key !== 'Enter' && e.key !== ' ') return;
                e.preventDefault();
                var ytId = $(this).data('yt-id');
                if (ytId) openModal(ytId);
            })
            .on('click.lcw', '#lcw-modal', function (e) {
                if ($(e.target).is('#lcw-modal')) closeModal();
            })
            .on('click.lcw', '.lcw-modal-close', function () { closeModal(); });

        $(document).on('keydown.lcw-esc', function (e) {
            if (e.key === 'Escape') closeModal();
        });

        if ($('#lcw-body-lock').length === 0) {
            $('<style id="lcw-body-lock">.lcw-modal-open{overflow:hidden!important}</style>').appendTo('head');
        }
    }

    /* =====================================================
     * HELPERS
     * ===================================================== */
    function debounce(fn, ms) {
        var t;
        return function () { clearTimeout(t); t = setTimeout(fn, ms); };
    }

    /* =====================================================
     * INIT
     * ===================================================== */
    $(document).ready(function () {
        initAll();
        bindModal();
    });

    $(window).on('elementor/frontend/init', function () {
        if (typeof elementorFrontend === 'undefined') return;
        elementorFrontend.hooks.addAction('frontend/element_ready/lcw_logo_carousel.default', function ($scope) {
            $scope.find('.lcw-track-outer').each(function () {
                var $el = $(this);
                $el.off('.lcw').removeData('lcw-inited');
                $el.find('.lcw-track').css('animation', '');
                initCarousel($el);
            });
            bindModal();
        });
    });

    if (typeof elementor !== 'undefined') {
        elementor.channels.editor.on('change', debounce(function () {
            $('.lcw-track-outer').each(function () {
                var $el = $(this);
                $el.off('.lcw').removeData('lcw-inited');
                $el.find('.lcw-track').css('animation', '');
                initCarousel($el);
            });
        }, 600));
    }

})(jQuery);
