<?php
/**
 * Settings Template
 * 
 */
?>
<div class="wrap">
    <h2>JWS Subscriber Settings</h2>
    <form method="post" action="options.php" novalidate="novalidate">
        <?php settings_fields( 'jws-option-group' ); ?>
        <?php do_settings_sections( 'jws-option-group' ); ?>
        
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">Activate</th>
                    <td>
                        <input 
                            type="checkbox" 
                            name="jws_active" 
                            id="jws_active" 
                            value="1"
                            <?php checked( 1, esc_attr( get_option( 'jws_active', false ) ), true ); ?> 
                        />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Subscription Status</th>
                    <td>
                        <select name="jws_subscription_status" id="jws_subscription_status">
                            <?php foreach( wcs_get_subscription_statuses() as $status => $name ): ?>
                            <option 
                                value="<?php echo str_replace( 'wc-', '', $status ); ?>" 
                                <?php selected( esc_attr( get_option( 'jws_subscription_status', 'active' ) ), str_replace( 'wc-', '', $status ), true ); ?> 
                            >
                                <?php echo $name; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Subscription Status</th>
                    <td>
                        <input 
                            type="text" 
                            name="jws_unauthorized_message" 
                            id="jws_unauthorized_message" 
                            value="<?php echo esc_attr( get_option( 'jws_unauthorized_message', 'You are not subscriber' ) ); ?>"
                        />
                    </td>
                </tr>
            </tbody>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div>