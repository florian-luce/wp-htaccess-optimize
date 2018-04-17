<?php
/* ------ -
 * display htaccess menu options
 * --- -
 *
 */
function wpho_display_admin_ui_content_view() {
    ?>
        <div class="wrap">
            <h1><?php _e( 'WP htaccess optimize', 'wp-htaccess-optimize' ); ?></h1>
            <p><?php _e( 'Custom your htaccess', 'wp-htaccess-optimize' ); ?></p>

            <form method="post" action="options.php" novalidate="novalidate" enctype="multipart/form-data">
                <?php settings_fields( 'wpho-htaccess-options' ); ?>
                <?php do_settings_sections( 'wpho-htaccess-options' ); ?>

                <table class="form-table">
                    <?php
                        echo '<input type="text" name="wpho_rewrite_htaccess" value="true" id="wp-htaccess-optimize" hidden>';
                        echo wpho_list_to_display_htaccess_inputs_options();
                    ?>
                </table>
                <?php submit_button( __( 'Save and rewrite htaccess', 'wp-htaccess-optimize' ) ); ?>
            </form>
        </div>
    <?php
}

/* ------ -
 * List menu name for construct htaccess options
 * --- -
 *
 */
function wpho_list_to_display_htaccess_inputs_options() {

    $return_content = '';
    $htaccess_options = [
        'Time zone selection',
        'Prevents indexing of folders without index.php',
        'Disable the server signature',
        'Block Sensitive Files',
        'Protect WP-includes',
        'protect author link',
        'block author scans',
        'Enabling the tracking of symbolic links',
        'comment spam',
        'Protection against file injections',
        'Various protections ( XSS, clickjacking and MIME-Type sniffing )',
        'Disable the hotlinking of your pictures',
        'Redirect without WWW',
        'Redirect to HTTPS',
        'Caching files in the browser',
        'Disabled headers ETags',
        'Compress static files',
    ];

    foreach ( $htaccess_options as $option ) {

        $sanitized_option = sanitize_html_class( $option );
        $return_content .= wpho_construct_htaccess_input_option( $option, 'checkbox', $sanitized_option, 'true', $sanitized_option );
    };

    return $return_content;
}

/* ------ -
 * building htaccess options sections for the menu
 * --- -
 *
 */
function wpho_construct_htaccess_input_option( $display_title_text = '',
                                               $input_type,
                                               $input_name_and_option_name,
                                               $input_default_value = false,
                                               $input_id_and_label_for,
                                               $display_label_text = false
                                              ) {

    $option_name_value = esc_attr( get_option( $input_name_and_option_name ) );

    if( $option_name_value === $input_default_value ) {

        $display_checked = 'checked';
    } else {

        $display_checked = '';
    }

    if( false === $input_default_value || '' === $input_default_value ) {

        $input_default_value = $option_name_value;
    }

    $out = vsprintf(
        '
            <tr valign="top">
                <th scope="row">%1$s</th>
                <td>
                    <input type="%2$s" name="%3$s" value="%4$s" id="%5$s" %6$s>
       ',
        array(
            esc_attr__( $display_title_text, 'wp-htaccess-optimize' ),
            $input_type,
            $input_name_and_option_name,
            $input_default_value,
            $input_id_and_label_for,
            $display_checked,
        )
    );

    if( false === empty( $display_label_text ) ) {

        $out .= vsprintf(
            '
                        <label for="%1$s">%2$s</label><br />
           ',
            array(
                $input_id_and_label_for,
                esc_attr__( $display_label_text, 'wp-htaccess-optimize' ),
            )
        );
    }

    $out.= add_input_options( $display_title_text );

    $out .= '</td></td>';
    return $out;
}

/* ------ -
 * building and adding specific options
 * --- -
 *
 */
function add_input_options( $display_title_text ) {

    if( 'Time zone selection' === $display_title_text ) {

//        http://php.net/manual/en/timezones. -> Liste complète des Times zones supportée

        $option_values_names_liste = [
            'Europe/Paris',
            'Europe/London',
            'Europe/Dublin',
            'Europe/Belgrade',
        ];

        $display_option_names_list = [

            'France',
            'Royaume-uni',
            'Irlande',
            'Serbie',
        ];

        $out .= wpho_display_option_select( 'Time zone selection', 'wpho_country_selected_htaccess', $option_values_names_liste, $display_option_names_list );
    } elseif( 'Redirect visitors coming site to another' === $display_title_text ) {

        $out .= whop_display_dual_repeat_input( false, 'text', 'wpho_redirect_visitors_coming', 'input-class-name', 'br-option-name', 'patate', 'Rediriger votre site vers' );
    }
    return $out;
}

/* ------ -
 * To build a radio option list
 * --- -
 *
 */
function wpho_display_option_select( $display_title, $option_value_name, $options_values_names_list, $display_options_names_list ) {

    $current_options = get_option( $option_value_name );
    $option_out = '';

    if( false !== $display_title ) {

        $option_out .= '<label for="' . $option_value_name . '" >' . __( $display_title, 'wp-htaccess-optimize' ) . '</label ><br />';
    }
    $option_out .= '<select name = "' . $option_value_name . '" id = "' . $option_value_name . '" >';
    $num_loop = 0;
    foreach ( $options_values_names_list as $options_values_name ) {

        if ( $current_options === $options_values_name) {

            $display_selected = 'selected';
        } else {

            $display_selected = '';
        }

        $option_out .= '<option value = "' . $options_values_name . '" ' . $display_selected . '>' . __( $display_options_names_list[ $num_loop ], 'wp-htaccess-optimize' ) . '</option >';
        $num_loop++;
    };

    $option_out .= '</select >';
    return $option_out;
}

/* ------ -
 * Declaration des custom options
 * --- -
 *
 */
add_action( 'admin_init', function() {

    $htaccess_options_name_register = [
        'Timezoneselection',
        'Preventsindexingoffolderswithoutindexphp',
        'Disabletheserversignature',
        'BlockSensitiveFiles',
        'ProtectWP-includes',
        'protectauthorlink',
        'blockauthorscans',
        'Enablingthetrackingofsymboliclinks',
        'commentspam',
        'Protectionagainstfileinjections',
        'VariousprotectionsXSSclickjackingandMIME-Typesniffing',
        'Disablethehotlinkingofyourpictures',
        'RedirectwithoutWWW',
        'RedirecttoHTTPS',
        'Cachingfilesinthebrowser',
        'DisabledheadersETags',
        'Compressstaticfiles',
    ];

    foreach ( $htaccess_options_name_register as $option_name ) {

        register_setting( 'wpho-htaccess-options', $option_name );
    }

    register_setting( 'wpho-htaccess-options', 'wpho_redirect_visitors_coming', 'wpho_sanitize_urls_field' );
    register_setting( 'wpho-htaccess-options', 'wpho_country_selected_htaccess' );
    register_setting( 'wpho-htaccess-options', 'wpho_rewrite_htaccess','wpho_rewrite_htaccess_file' );
} );

/* ------ -
 * personalized error message
 * --- -
 *
 */
add_action( 'admin_notices', function() {

    settings_errors( 'wp-htaccess-optimize' );
} );

/* ------ -
 * HTACCESS rewrite functions
 * --- -
 *
 */
function wpho_rewrite_htaccess_file( $value ) {

    if( 'true' === $value ) {

        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }
    add_settings_error( 'wpho_rewrite_htaccess', 'wpho_rewrite_htaccess', __( 'updated htaccess file', 'wp-htaccess-optimize' ),'updated' );
    return 'false';
}
