<?php


add_action("admin_init", function () {
    add_action( 'show_user_profile', function ($user) {

        $u = new LFA_Author($user);

        if(!$u->isBlogger()) {
            return false;
        }

        my_show_extra_profile_fields($user);
    } );
    add_action( 'edit_user_profile', function ($user) {

        $u = new LFA_Author($user);

        if(!$u->isBlogger()) {
            return false;
        }

        my_show_extra_profile_fields($user);
    } );

    add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
    add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

    add_action("personal_options", "my_user_edit_form_tag");

    remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

    add_action( 'admin_head-user-edit.php', 'lfa_b_remove_website_field' );
    add_action( 'admin_head-profile.php',   'lfa_b_remove_website_field' );
});


function lfa_b_remove_website_field() {
    echo '<style>tr.user-url-wrap{ display: none; }</style>';
}

function my_user_edit_form_tag() {

    global $user_id;

    $author = new LFA_Author($user_id);

    if($author->isEnabled()) {
        return false;
    }

    ?>

    <h3 class="show" data-role="enable-blogger-ui">Account activation</h3>

    <tr class="enable-blogger-row" data-role="enable-blogger-ui">
        <th scope="row">Enable blogger</th>
        <td>
            <button data-id="<?php echo $user_id;?>" data-action='enable-user' class="button action">Enable</button>
        </td>
    </tr>
    <?php
}

/**
 * @param $user WP_User
 */
function my_show_extra_profile_fields( $user )  {

    ?>

    <h3>Additional profile information</h3>

    <table class="form-table">

        <tr>
            <th><label for="_user_position">Your role at LEAF Academy</label></th>

            <td>
                <input type="text" name="_user_position" id="_user_position" value="<?php echo esc_attr( get_the_author_meta( '_user_position', $user->ID ) ); ?>" class="regular-text" /><br />
            </td>
        </tr>

    </table>
<?php }

function my_save_extra_profile_fields( $user_id ) {

    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;

    /* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
    update_user_meta( $user_id, '_user_position', $_POST['_user_position'] );
}