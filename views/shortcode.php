<div class="wu-weather-shortcode-container">
    <div class="wu-weather-summary">
        <h3><?php echo $currentConditions['current_observation']['display_location']['full']; ?></h3>
        <div class="wu-weather-weather">
            <h2><?php echo intval($currentConditions['current_observation']['temp_f']); ?></h2>
            <span class="wu-weather-deg">
                F
            </span>
            <p>Feels like <span><?php echo $currentConditions['current_observation']['temp_f']; ?></span></p>
        </div>
        <div class="wu-weather-weather-icon">
            <img src="<?php echo $currentConditions['current_observation']['icon_url']; ?>">
            <p><?php echo $currentConditions['current_observation']['weather']; ?></p>
        </div>
        <div style="clear:both;"></div>
    </div>
    <div class="wunderground">

            <a href="https://www.wunderground.com/" target="_blank">
                <img src="https://icons.wxug.com/logos/JPG/wundergroundLogo_4c_horz.jpg" width="90" height="90">
            </a>

    </div>
</div>