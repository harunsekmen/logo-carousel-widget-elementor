<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;

class LCW_Logo_Carousel_Widget extends Widget_Base {

    public function get_name()       { return 'lcw_logo_carousel'; }
    public function get_title()      { return __( 'Logo Carousel', 'logo-carousel-widget' ); }
    public function get_icon()       { return 'eicon-carousel'; }
    public function get_categories() { return [ 'general' ]; }
    public function get_keywords()   { return [ 'logo', 'carousel', 'slider', 'brand', 'marquee', 'youtube', 'video', 'popup' ]; }

    public function get_style_depends() {
        return [ 'lcw-styles' ];
    }

    public function get_script_depends() {
        return [ 'lcw-scripts' ];
    }

    protected function register_controls() {

        /* ── Logos ─────────────────────────────── */
        $this->start_controls_section( 'section_logos', [
            'label' => __( 'Logos', 'logo-carousel-widget' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $repeater = new Repeater();

        $repeater->add_control( 'logo_image', [
            'label'   => __( 'Logo Image', 'logo-carousel-widget' ),
            'type'    => Controls_Manager::MEDIA,
            'default' => [ 'url' => Utils::get_placeholder_image_src() ],
        ] );

        $repeater->add_control( 'logo_title', [
            'label'       => __( 'Brand Name (Alt text)', 'logo-carousel-widget' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => __( 'Brand Name', 'logo-carousel-widget' ),
            'label_block' => true,
        ] );

        $repeater->add_control( 'youtube_url', [
            'label'       => __( '▶ YouTube Video URL', 'logo-carousel-widget' ),
            'description' => __( 'If filled, a popup video opens when clicked. Normal link is ignored.', 'logo-carousel-widget' ),
            'type'        => Controls_Manager::TEXT,
            'placeholder' => 'https://www.youtube.com/watch?v=XXXXXXXXXXX',
            'label_block' => true,
        ] );

        $repeater->add_control( 'logo_link', [
            'label'         => __( 'Website Link (If no YouTube)', 'logo-carousel-widget' ),
            'type'          => Controls_Manager::URL,
            'placeholder'   => 'https://example.com',
            'show_external' => true,
            'default'       => [ 'url' => '' ],
        ] );

        $this->add_control( 'logos', [
            'label'       => __( 'Logo List', 'logo-carousel-widget' ),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => [
                [ 'logo_title' => 'Brand 1' ],
                [ 'logo_title' => 'Brand 2' ],
                [ 'logo_title' => 'Brand 3' ],
                [ 'logo_title' => 'Brand 4' ],
                [ 'logo_title' => 'Brand 5' ],
                [ 'logo_title' => 'Brand 6' ],
            ],
            'title_field' => '{{{ logo_title }}}',
        ] );

        $this->end_controls_section();

        /* ── Carousel Settings ─────────────────── */
        $this->start_controls_section( 'section_settings', [
            'label' => __( 'Carousel Settings', 'logo-carousel-widget' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'direction', [
            'label'   => __( 'Scroll Direction', 'logo-carousel-widget' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'left',
            'options' => [
                'left'  => __( 'Left → Right', 'logo-carousel-widget' ),
                'right' => __( 'Right → Left', 'logo-carousel-widget' ),
            ],
        ] );

        $this->add_control( 'speed', [
            'label'   => __( 'Speed (pixels/second)', 'logo-carousel-widget' ),
            'type'    => Controls_Manager::SLIDER,
            'default' => [ 'size' => 60 ],
            'range'   => [ 'px' => [ 'min' => 10, 'max' => 300, 'step' => 5 ] ],
        ] );

        $this->add_control( 'pause_on_hover', [
            'label'        => __( 'Pause on Hover', 'logo-carousel-widget' ),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $this->add_control( 'rows_count', [
            'label'       => __( 'Number of Rows', 'logo-carousel-widget' ),
            'description' => __( 'Maximum 4 rows. Consecutive rows move in opposite directions.', 'logo-carousel-widget' ),
            'type'        => Controls_Manager::NUMBER,
            'min'         => 1,
            'max'         => 4,
            'default'     => 1,
        ] );

        $this->end_controls_section();

        /* ── Heading ────────────────────────────── */
        $this->start_controls_section( 'section_heading', [
            'label' => __( 'Heading (Optional)', 'logo-carousel-widget' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        $this->add_control( 'heading_text', [
            'label'       => __( 'Heading Text', 'logo-carousel-widget' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => '',
            'placeholder' => __( 'e.g. Trusted Brands', 'logo-carousel-widget' ),
            'label_block' => true,
        ] );

        $this->add_control( 'heading_tag', [
            'label'   => __( 'HTML Tag', 'logo-carousel-widget' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'h3',
            'options' => [ 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'p' => 'P' ],
        ] );

        $this->end_controls_section();

        /* ── Style: General ──────────────────────── */
        $this->start_controls_section( 'section_style_wrapper', [
            'label' => __( 'General Style', 'logo-carousel-widget' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'bg_color', [
            'label'     => __( 'Background Color', 'logo-carousel-widget' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'selectors' => [ '{{WRAPPER}} .lcw-wrapper' => 'background-color: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'wrapper_padding', [
            'label'      => __( 'Padding', 'logo-carousel-widget' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em', '%' ],
            'default'    => [ 'top' => '30', 'right' => '0', 'bottom' => '30', 'left' => '0', 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .lcw-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_control( 'show_fade_edges', [
            'label'        => __( 'Edge Fade Effect', 'logo-carousel-widget' ),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $this->add_control( 'fade_color', [
            'label'     => __( 'Fade Color', 'logo-carousel-widget' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => '#ffffff',
            'condition' => [ 'show_fade_edges' => 'yes' ],
            'selectors' => [
                '{{WRAPPER}} .lcw-track-outer::before' => '--lcw-fade-color: {{VALUE}};',
                '{{WRAPPER}} .lcw-track-outer::after'  => '--lcw-fade-color: {{VALUE}};',
            ],
        ] );

        $this->end_controls_section();

        /* ── Style: Logo Card ─────────────────── */
        $this->start_controls_section( 'section_style_logo', [
            'label' => __( 'Logo Card', 'logo-carousel-widget' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_responsive_control( 'logo_height', [
            'label'      => __( 'Logo Height', 'logo-carousel-widget' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 20, 'max' => 200 ] ],
            'default'    => [ 'size' => 60, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .lcw-logo img' => 'height: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'logo_gap', [
            'label'      => __( 'Gap Between Logos', 'logo-carousel-widget' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
            'default'    => [ 'size' => 40, 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .lcw-logo' => 'margin-right: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_responsive_control( 'logo_padding', [
            'label'      => __( 'Card Padding', 'logo-carousel-widget' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'em' ],
            'default'    => [ 'top' => '10', 'right' => '20', 'bottom' => '10', 'left' => '20', 'unit' => 'px' ],
            'selectors'  => [ '{{WRAPPER}} .lcw-logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_control( 'logo_bg', [
            'label'     => __( 'Card Background', 'logo-carousel-widget' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'transparent',
            'selectors' => [ '{{WRAPPER}} .lcw-logo' => 'background-color: {{VALUE}};' ],
        ] );

        $this->add_group_control( Group_Control_Border::get_type(), [
            'name'     => 'logo_border',
            'selector' => '{{WRAPPER}} .lcw-logo',
        ] );

        $this->add_control( 'logo_border_radius', [
            'label'      => __( 'Border Radius', 'logo-carousel-widget' ),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%' ],
            'selectors'  => [ '{{WRAPPER}} .lcw-logo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
        ] );

        $this->add_group_control( Group_Control_Box_Shadow::get_type(), [
            'name'     => 'logo_box_shadow',
            'selector' => '{{WRAPPER}} .lcw-logo',
        ] );

        $this->add_control( 'logo_opacity', [
            'label'     => __( 'Opacity (Normal)', 'logo-carousel-widget' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0.1, 'max' => 1, 'step' => 0.05 ] ],
            'default'   => [ 'size' => 0.65 ],
            'selectors' => [ '{{WRAPPER}} .lcw-logo img' => 'opacity: {{SIZE}};' ],
        ] );

        $this->add_control( 'logo_opacity_hover', [
            'label'     => __( 'Opacity (Hover)', 'logo-carousel-widget' ),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [ 'px' => [ 'min' => 0.1, 'max' => 1, 'step' => 0.05 ] ],
            'default'   => [ 'size' => 1 ],
            'selectors' => [ '{{WRAPPER}} .lcw-logo:hover img' => 'opacity: {{SIZE}};' ],
        ] );

        $this->add_control( 'grayscale', [
            'label'        => __( 'Grayscale Filter', 'logo-carousel-widget' ),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => '',
            'selectors'    => [
                '{{WRAPPER}} .lcw-logo img'       => 'filter: grayscale(100%);',
                '{{WRAPPER}} .lcw-logo:hover img' => 'filter: grayscale(0%);',
            ],
            'condition' => [ 'grayscale' => 'yes' ],
        ] );

        $this->end_controls_section();

        /* ── Style: YouTube Popup ──────────────── */
        $this->start_controls_section( 'section_style_popup', [
            'label' => __( 'YouTube Popup Style', 'logo-carousel-widget' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );

        $this->add_control( 'popup_overlay_color', [
            'label'     => __( 'Overlay Color', 'logo-carousel-widget' ),
            'type'      => Controls_Manager::COLOR,
            'default'   => 'rgba(0,0,0,0.85)',
            'selectors' => [ '#lcw-modal.lcw-modal-overlay' => 'background: {{VALUE}};' ],
        ] );

        $this->add_control( 'popup_max_width', [
            'label'      => __( 'Popup Max Width', 'logo-carousel-widget' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [ 'px' => [ 'min' => 300, 'max' => 1400 ] ],
            'default'    => [ 'size' => 900, 'unit' => 'px' ],
            'selectors'  => [ '#lcw-modal .lcw-modal-inner' => 'max-width: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->add_control( 'popup_play_icon', [
            'label'        => __( 'Show Play Icon', 'logo-carousel-widget' ),
            'description'  => __( 'Play icon is shown over the row.', 'logo-carousel-widget' ),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ] );

        $this->add_control( 'custom_play_icon', [
            'label'     => __( 'Custom Play Icon', 'logo-carousel-widget' ),
            'type'      => Controls_Manager::ICONS,
            'condition' => [ 'popup_play_icon' => 'yes' ],
        ] );

        $this->add_control( 'play_icon_color', [
            'label'     => __( 'Icon Color', 'logo-carousel-widget' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .lcw-play-badge i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .lcw-play-badge svg' => 'fill: {{VALUE}};',
            ],
            'condition' => [ 'popup_play_icon' => 'yes' ],
        ] );

        $this->add_responsive_control( 'play_icon_size', [
            'label'      => __( 'Icon Size', 'logo-carousel-widget' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', 'em' ],
            'selectors'  => [
                '{{WRAPPER}} .lcw-play-badge' => 'width: auto; height: auto;',
                '{{WRAPPER}} .lcw-play-badge i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .lcw-play-badge svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
            'condition' => [ 'popup_play_icon' => 'yes' ],
        ] );

        $this->end_controls_section();

        /* ── Style: Heading ─────────────────────── */
        $this->start_controls_section( 'section_style_heading', [
            'label'     => __( 'Heading Style', 'logo-carousel-widget' ),
            'tab'       => Controls_Manager::TAB_STYLE,
            'condition' => [ 'heading_text!' => '' ],
        ] );

        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'heading_typography',
            'selector' => '{{WRAPPER}} .lcw-heading',
        ] );

        $this->add_control( 'heading_color', [
            'label'     => __( 'Color', 'logo-carousel-widget' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [ '{{WRAPPER}} .lcw-heading' => 'color: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'heading_align', [
            'label'     => __( 'Alignment', 'logo-carousel-widget' ),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'left'   => [ 'title' => 'Left',  'icon' => 'eicon-text-align-left' ],
                'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                'right'  => [ 'title' => 'Right',  'icon' => 'eicon-text-align-right' ],
            ],
            'default'   => 'center',
            'selectors' => [ '{{WRAPPER}} .lcw-heading' => 'text-align: {{VALUE}};' ],
        ] );

        $this->add_responsive_control( 'heading_margin', [
            'label'      => __( 'Bottom Margin', 'logo-carousel-widget' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'default'    => [ 'size' => 24 ],
            'selectors'  => [ '{{WRAPPER}} .lcw-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};' ],
        ] );

        $this->end_controls_section();
    }

    /* ── YouTube ID helper ───────────────────── */
    private function extract_youtube_id( $url ) {
        if ( empty( $url ) ) return '';
        if ( preg_match( '/youtu\.be\/([^?&\s]+)/', $url, $m ) )             return sanitize_text_field( $m[1] );
        if ( preg_match( '/[?&]v=([^&\s]+)/', $url, $m ) )                   return sanitize_text_field( $m[1] );
        if ( preg_match( '/youtube\.com\/embed\/([^?&\s]+)/', $url, $m ) )   return sanitize_text_field( $m[1] );
        return '';
    }

    /* ── Render ──────────────────────────────── */
    protected function render() {
        $settings  = $this->get_settings_for_display();
        $logos     = $settings['logos'] ?? [];
        if ( empty( $logos ) ) return;

        $direction   = $settings['direction'] ?? 'left';
        $speed       = $settings['speed']['size'] ?? 60;
        $pause_hover = $settings['pause_on_hover'] === 'yes' ? 'true' : 'false';
        $rows_count  = isset( $settings['rows_count'] ) ? (int) $settings['rows_count'] : 1;
        $fade_edges  = $settings['show_fade_edges'] === 'yes';
        $show_play   = ( $settings['popup_play_icon'] ?? '' ) === 'yes';
        $widget_id   = 'lcw-' . $this->get_id();
        $fade_class  = $fade_edges ? ' lcw-has-fade' : '';

        $play_svg = '<svg viewBox="0 0 68 48" xmlns="http://www.w3.org/2000/svg">'
            . '<path d="M66.52 7.74c-.78-2.93-2.49-5.41-5.42-6.19C55.79.13 34 0 34 0S12.21.13 6.9 1.55C3.97 2.33 2.27 4.81 1.48 7.74-.06 13.05 0 24 0 24s-.06 10.95 1.48 16.26c.78 2.93 2.49 5.41 5.42 6.19C12.21 47.87 34 48 34 48s21.79-.13 27.1-1.55c2.93-.78 4.64-3.26 5.42-6.19C68 34.95 68 24 68 24s-.06-10.95-1.48-16.26z" fill="#f00"/>'
            . '<path d="M45 24 27 14v20" fill="#fff"/>'
            . '</svg>';

        ob_start();
        if ( ! empty( $settings['custom_play_icon']['value'] ) ) {
            \Elementor\Icons_Manager::render_icon( $settings['custom_play_icon'], [ 'aria-hidden' => 'true' ] );
        }
        $rendered_icon = ob_get_clean();
        if ( empty( $rendered_icon ) ) {
            $rendered_icon = $play_svg;
        }

        $data_attrs = 'data-direction="' . esc_attr( $direction ) . '" '
                    . 'data-speed="' . esc_attr( $speed ) . '" '
                    . 'data-pause="' . esc_attr( $pause_hover ) . '"';

        echo '<div class="lcw-wrapper" id="' . esc_attr( $widget_id ) . '">';

        if ( ! empty( $settings['heading_text'] ) ) {
            $tag = esc_html( $settings['heading_tag'] ?? 'h3' );
            echo "<{$tag} class=\"lcw-heading\">" . esc_html( $settings['heading_text'] ) . "</{$tag}>";
        }

        $rows = min( 4, max( 1, $rows_count ) );
        for ( $row = 0; $row < $rows; $row++ ) {
            $row_dir = ( $row % 2 === 1 )
                ? ( $direction === 'left' ? 'right' : 'left' )
                : $direction;

            echo '<div class="lcw-track-outer' . esc_attr( $fade_class ) . '" '
                . $data_attrs . ' data-row-direction="' . esc_attr( $row_dir ) . '">';
            echo '<div class="lcw-track">';

            for ( $copy = 0; $copy < 4; $copy++ ) {
                foreach ( $logos as $index => $logo ) {
                    $img_url  = $logo['logo_image']['url'] ?? '';
                    $alt      = esc_attr( $logo['logo_title'] ?? '' );
                    $yt_id    = $this->extract_youtube_id( $logo['youtube_url'] ?? '' );
                    $link     = $logo['logo_link']['url'] ?? '';
                    
                    if ( $yt_id ) {
                        echo '<div class="lcw-logo lcw-has-yt" data-yt-id="' . esc_attr( $yt_id ) . '" role="button" tabindex="0" aria-label="' . $alt . ' watch video">';
                        if ( $img_url ) echo '<img src="' . esc_url( $img_url ) . '" alt="' . $alt . '" loading="lazy">';
                        else echo '<span class="lcw-logo-placeholder">' . esc_html( $logo['logo_title'] ?? '' ) . '</span>';
                        if ( $show_play ) echo '<span class="lcw-play-badge" aria-hidden="true">' . $rendered_icon . '</span>';
                        echo '</div>';
                    } elseif ( $link ) {
                        // Elementor Link attributes logic
                        $link_key = 'logo_link_' . $copy . '_' . $logo['_id'];
                        $this->add_link_attributes( $link_key, $logo['logo_link'] );
                        
                        echo '<div class="lcw-logo">';
                        echo '<a ' . $this->get_render_attribute_string( $link_key ) . ' aria-label="' . $alt . '">';
                        if ( $img_url ) echo '<img src="' . esc_url( $img_url ) . '" alt="' . $alt . '" loading="lazy">';
                        else echo '<span class="lcw-logo-placeholder">' . esc_html( $logo['logo_title'] ?? '' ) . '</span>';
                        echo '</a></div>';
                    } else {
                        echo '<div class="lcw-logo">';
                        if ( $img_url ) echo '<img src="' . esc_url( $img_url ) . '" alt="' . $alt . '" loading="lazy">';
                        else echo '<span class="lcw-logo-placeholder">' . esc_html( $logo['logo_title'] ?? '' ) . '</span>';
                        echo '</div>';
                    }
                }
            }

            echo '</div></div>';
        }

        echo '</div>';
    }

    protected function content_template() { ?>
        <#
        var logos     = settings.logos;
        var direction = settings.direction || 'left';
        var heading   = settings.heading_text;
        var tag       = settings.heading_tag || 'h3';
        var rowsCount = settings.rows_count || 1;
        var fadeEdges = settings.show_fade_edges === 'yes';
        var fadeClass = fadeEdges ? ' lcw-has-fade' : '';
        var speed     = (settings.speed && settings.speed.size) ? settings.speed.size : 60;
        var pause     = settings.pause_on_hover === 'yes' ? 'true' : 'false';
        var showPlay  = settings.popup_play_icon === 'yes';

        var dataAttrs = 'data-direction="'+direction+'" data-speed="'+speed+'" data-pause="'+pause+'"';

        function extractYtId(url) {
            if (!url) return '';
            var m;
            if ((m = url.match(/youtu\.be\/([^?&\s]+)/))) return m[1];
            if ((m = url.match(/[?&]v=([^&\s]+)/))) return m[1];
            if ((m = url.match(/youtube\.com\/embed\/([^?&\s]+)/))) return m[1];
            return '';
        }

        var playSvg = '<svg viewBox="0 0 68 48" xmlns="http://www.w3.org/2000/svg"><path d="M66.52 7.74c-.78-2.93-2.49-5.41-5.42-6.19C55.79.13 34 0 34 0S12.21.13 6.9 1.55C3.97 2.33 2.27 4.81 1.48 7.74-.06 13.05 0 24 0 24s-.06 10.95 1.48 16.26c.78 2.93 2.49 5.41 5.42 6.19C12.21 47.87 34 48 34 48s21.79-.13 27.1-1.55c2.93-.78 4.64-3.26 5.42-6.19C68 34.95 68 24 68 24s-.06-10.95-1.48-16.26z" fill="#f00"/><path d="M45 24 27 14v20" fill="#fff"/></svg>';
        var renderedIcon = playSvg;
        if ( settings.custom_play_icon && settings.custom_play_icon.value ) {
            renderedIcon = elementor.helpers.renderIcon( view, settings.custom_play_icon, { 'aria-hidden': true }, 'i' , 'object' ).value;
        }
        #>
        <div class="lcw-wrapper">
            <# if (heading) { #><{{{ tag }}} class="lcw-heading">{{{ heading }}}</{{{ tag }}}><# } #>
            <# var rows = Math.min(4, Math.max(1, rowsCount));
               for (var r = 0; r < rows; r++) {
                   var rowDir = (r % 2 === 1) ? (direction==='left' ? 'right' : 'left') : direction; #>
            <div class="lcw-track-outer{{{ fadeClass }}}" {{{ dataAttrs }}} data-row-direction="{{{ rowDir }}}">
                <div class="lcw-track">
                    <# for (var cp=0; cp<4; cp++) { _.each(logos, function(logo, index) {
                        var imgUrl = logo.logo_image ? logo.logo_image.url : '';
                        var alt    = logo.logo_title || '';
                        var ytId   = extractYtId(logo.youtube_url || '');
                        var link   = logo.logo_link ? logo.logo_link.url : '';
                        var linkKey = 'logo_link_' + cp + '_' + logo._id;
                        
                        if (link) {
                            view.addRenderAttribute(linkKey, 'href', link);
                            if (logo.logo_link.is_external) view.addRenderAttribute(linkKey, 'target', '_blank');
                            if (logo.logo_link.nofollow) view.addRenderAttribute(linkKey, 'rel', 'nofollow');
                        }
                    #>
                    <# if (ytId) { #>
                    <div class="lcw-logo lcw-has-yt" data-yt-id="{{{ ytId }}}" role="button" tabindex="0">
                        <# if (imgUrl) { #><img src="{{{ imgUrl }}}" alt="{{{ alt }}}"><# } else { #><span class="lcw-logo-placeholder">{{{ alt }}}</span><# } #>
                        <# if (showPlay) { #><span class="lcw-play-badge" aria-hidden="true">{{{ renderedIcon }}}</span><# } #>
                    </div>
                    <# } else if (link) { #>
                    <div class="lcw-logo"><a {{{ view.getRenderAttributeString(linkKey) }}} aria-label="{{{ alt }}}">
                        <# if (imgUrl) { #><img src="{{{ imgUrl }}}" alt="{{{ alt }}}"><# } else { #><span class="lcw-logo-placeholder">{{{ alt }}}</span><# } #>
                    </a></div>
                    <# } else { #>
                    <div class="lcw-logo">
                        <# if (imgUrl) { #><img src="{{{ imgUrl }}}" alt="{{{ alt }}}"><# } else { #><span class="lcw-logo-placeholder">{{{ alt }}}</span><# } #>
                    </div>
                    <# } #>
                    <# }); } #>
                </div>
            </div>
            <# } #>
        </div>
        <?php
    }
}
