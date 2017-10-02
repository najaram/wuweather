<div class="wrap">
    <h1>Wunderground Weather</h1>
    <em>Provide your api key</em>
    <?php if ($_POST): ?>
        <div class="notice notice-success">
            <p>Settings Updated!</p>
        </div>
    <?php endif; ?>
    <form method="post">
        <table class="form-table">
            <tr valign="top">
                <th scope="row">API Key</th>
                <td><input type="text" name="wu_weather_api_key" value="<?php echo esc_attr(get_option('wu_weather_api_key')); ?>"></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>