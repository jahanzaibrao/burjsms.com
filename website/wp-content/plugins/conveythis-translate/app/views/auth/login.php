<form id="login-form" action="" method="post">

    <input type="hidden" name="set_api_key"  value="1"/>

    <div class="key-block mt-5">
        <div>
            <a href="https://www.conveythis.com/" target="_blank">
                <img src="<?php echo esc_url(CONVEY_PLUGIN_PATH);?>app/widget/images/conveythis-logo-vertical-blue.png" alt="ConveyThis">
            </a>
        </div>
        
        <div class="text">Take a few steps to set up plugin</div>
        <div class="row gap-20-sm align-items-center">

            <div class="col-md-12">
                <div class="step text-center">
                    <p>Complete a quick registration to get your API key</p>
                    <a href="https://app.conveythis.com/account/register-wordpress/" class="btn btn-primary api-key-setting" target="_blank">Get api key</a>
                </div>
            </div>

        </div>

        <div>
            <a href="#" class="api-key-setting">I already have API key!</a>
        </div>
    </div>

</form>

