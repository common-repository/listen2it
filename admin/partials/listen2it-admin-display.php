<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.vikasroy.com
 * @since      1.0.0
 *
 * @package    Listen2it
 * @subpackage Listen2it/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

    <h2>Listen2It</h2>
	<?php settings_errors(); ?>
    <form method="POST" action="options.php">
		<?php
            settings_fields( 'l2it_general_settings' );
            do_settings_sections( 'l2it_general_settings' );
		?>
		<?php submit_button(); ?>
    </form>

    <?php

        if( get_option('l2it_org_id') && get_option('l2it_integration_id') && get_option('l2it_api_key') ):

            $response = wp_remote_get( 'https://api.getlisten2it.com/organisation/' . get_option('l2it_org_id') . '/integration/' . get_option('l2it_integration_id') . '?origin=wordpress',
                array(
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'X-API-Key' => get_option('l2it_api_key')
                    )
                )
            );

            if( 200 == wp_remote_retrieve_response_code( $response )):

                $body = json_decode(wp_remote_retrieve_body( $response ));

    ?>

    <div class="notice notice-success is-dismissible">
        <p>The plugin has been successfully connected to your Listen2It account.</p>
    </div>

    <div style="display:flex; align-items: center;">
        <h1 style="display: inline-block;padding: 0; margin-right:5px;">Settings</h1>
        <span class="dashicons dashicons-update" id="l2it-btn-refresh-settings" style="margin-bottom: -4px; cursor:pointer;"></span>
    </div>

    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row">Property Name</th>
                <td>
                    <input type="text" value="<?php echo esc_attr($body->data->integration_name); ?>" size="40" disabled/>
                    <p><a href="https://dashboard.getlisten2it.com/organisation/<?php echo esc_attr(get_option('l2it_org_id')) ?>/integration/<?php echo esc_attr(get_option('l2it_integration_id'))?>/general" target="_blank">Click here</a> to change general settings</p>
                </td>
            </tr>
            <tr>
                <th scope="row">Voice</th>
                <td>
                    <input type="text" value="<?php echo esc_attr($body->data->audio_profile->voice) ?>" size="40" disabled/>
                    <p><a href="https://dashboard.getlisten2it.com/organisation/<?php echo esc_attr(get_option('l2it_org_id')) ?>/integration/<?php echo esc_attr(get_option('l2it_integration_id'))?>/voice" target="_blank">Click here</a> to change voice settings</p>
                </td>
            </tr>
            <tr>
                <th scope="row">Player Type</th>
                <td>
                    <input type="text" value="<?php echo esc_attr(ucwords($body->data->audio_player_settings->player_type)) ?>" size="40" disabled/>
                    <p><a href="https://dashboard.getlisten2it.com/organisation/<?php echo esc_attr(get_option('l2it_org_id')) ?>/integration/<?php echo esc_attr(get_option('l2it_integration_id'))?>/player" target="_blank">Click here</a> to change player settings</p>
                </td>
            </tr>
        </tbody>
    </table>

    <?php

            else:

                echo '<div class="notice error is-dismissible"><p>Error connecting to Listen2It. Check your settings below.</p></div>';

            endif;

        endif;
    ?>

</div>