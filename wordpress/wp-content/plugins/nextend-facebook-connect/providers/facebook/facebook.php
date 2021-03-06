<?php

class NextendSocialProviderFacebook extends NextendSocialProvider {

    protected $dbID = 'fb';

    /** @var NextendSocialProviderFacebookClient */
    protected $client;

    protected $color = '#4267b2';

    protected $svg = '<svg xmlns="http://www.w3.org/2000/svg"><path fill="#fff" d="M22.688 0H1.323C.589 0 0 .589 0 1.322v21.356C0 23.41.59 24 1.323 24h11.505v-9.289H9.693V11.09h3.124V8.422c0-3.1 1.89-4.789 4.658-4.789 1.322 0 2.467.1 2.8.145v3.244h-1.922c-1.5 0-1.801.711-1.801 1.767V11.1h3.59l-.466 3.622h-3.113V24h6.114c.734 0 1.323-.589 1.323-1.322V1.322A1.302 1.302 0 0 0 22.688 0z"/></svg>';

    protected $popupWidth = 475;

    protected $popupHeight = 175;

    public function __construct() {
        $this->id    = 'facebook';
        $this->label = 'Facebook';

        $this->path = dirname(__FILE__);

        $this->requiredFields = array(
            'appid'  => 'App ID',
            'secret' => 'App Secret'
        );

        add_filter('nsl_finalize_settings_' . $this->optionKey, array(
            $this,
            'finalizeSettings'
        ));

        parent::__construct(array(
            'appid'        => '',
            'secret'       => '',
            'login_label'  => 'Continue with <b>Facebook</b>',
            'link_label'   => 'Link account with <b>Facebook</b>',
            'unlink_label' => 'Unlink account from <b>Facebook</b>',
            'legacy'       => 0
        ));

        if ($this->settings->get('legacy') == 1) {
            $this->loadCompat();
        }
    }

    protected function forTranslation() {
        __('Continue with <b>Facebook</b>', 'nextend-facebook-connect');
        __('Link account with <b>Facebook</b>', 'nextend-facebook-connect');
        __('Unlink account from <b>Facebook</b>', 'nextend-facebook-connect');
    }

    public function finalizeSettings($settings) {

        if (defined('NEXTEND_FB_APP_ID')) {
            $settings['appid'] = NEXTEND_FB_APP_ID;
        }
        if (defined('NEXTEND_FB_APP_SECRET')) {
            $settings['secret'] = NEXTEND_FB_APP_SECRET;
        }

        return $settings;
    }

    public function getClient() {
        if ($this->client === null) {

            require_once dirname(__FILE__) . '/facebook-client.php';

            $this->client = new NextendSocialProviderFacebookClient($this->id, $this->isTest());

            $this->client->setClientId($this->settings->get('appid'));
            $this->client->setClientSecret($this->settings->get('secret'));
            $this->client->setRedirectUri($this->getLoginUrl());
        }

        return $this->client;
    }

    public function validateSettings($newData, $postedData) {
        $newData = parent::validateSettings($newData, $postedData);

        foreach ($postedData AS $key => $value) {

            switch ($key) {
                case 'legacy':
                    if ($postedData['legacy'] == 1) {
                        $newData['legacy'] = 1;
                    } else {
                        $newData['legacy'] = 0;
                    }
                    break;
                case 'tested':
                    if ($postedData[$key] == '1' && (!isset($newData['tested']) || $newData['tested'] != '0')) {
                        $newData['tested'] = 1;
                    } else {
                        $newData['tested'] = 0;
                    }
                    break;
                case 'appid':
                case 'secret':
                    $newData[$key] = trim(sanitize_text_field($value));
                    if ($this->settings->get($key) !== $newData[$key]) {
                        $newData['tested'] = 0;
                    }

                    if (empty($newData[$key])) {
                        NextendSocialLoginAdminNotices::addError(sprintf(__('The %1$s entered did not appear to be a valid. Please enter a valid %2$s.', 'nextend-facebook-connect'), $this->requiredFields[$key], $this->requiredFields[$key]));
                    }
                    break;
            }
        }

        return $newData;
    }

    /**
     * @param $accessTokenData
     *
     * @return string
     * @throws Exception
     */
    protected function requestLongLivedToken($accessTokenData) {
        $client = $this->getClient();
        if (!$client->isAccessTokenLongLived()) {

            return $client->requestLongLivedAccessToken();
        }

        return $accessTokenData;
    }

    /**
     * @return array
     */
    protected function getCurrentUserInfo() {

        return $this->getClient()
                    ->get('/me?fields=id,name,email,first_name,last_name');
    }

    protected function getAuthUserData($key) {

        switch ($key) {
            case 'id':
                return $this->authUserData['id'];
            case 'email':
                return $this->authUserData['email'];
            case 'name':
                return $this->authUserData['name'];
            case 'first_name':
                return $this->authUserData['first_name'];
            case 'last_name':
                return $this->authUserData['last_name'];
        }

        return parent::getAuthUserData($key);
    }

    public function syncProfile($user_id, $provider, $access_token) {
        $this->saveUserData($user_id, 'profile_picture', 'https://graph.facebook.com/' . $this->getAuthUserData('id') . '/picture?type=large');
        $this->saveUserData($user_id, 'access_token', $access_token);
    }

    protected function saveUserData($user_id, $key, $data) {
        switch ($key) {
            case 'profile_picture':
                update_user_meta($user_id, 'fb_profile_picture', $data);
                break;
            case 'access_token':
                update_user_meta($user_id, 'fb_user_access_token', $data);
                break;
            default:
                parent::saveUserData($user_id, $key, $data);
                break;
        }
    }

    protected function getUserData($user_id, $key) {
        switch ($key) {
            case 'profile_picture':
                return get_user_meta($user_id, 'fb_profile_picture', true);
                break;
            case 'access_token':
                return get_user_meta($user_id, 'fb_user_access_token', true);
                break;
        }

        return parent::getUserData($user_id, $key);
    }

    public function getState() {
        if ($this->settings->get('legacy') == 1) {
            return 'legacy';
        }

        return parent::getState();
    }

    public function loadCompat() {

        if (!is_admin()) {
            require_once(dirname(__FILE__) . '/compat/nextend-facebook-connect.php');
        } else {
            if (basename($_SERVER['PHP_SELF']) !== 'plugins.php') {
                require_once(dirname(__FILE__) . '/compat/nextend-facebook-connect.php');
            } else {

                add_action('admin_menu', array(
                    $this,
                    'loadCompatMenu'
                ), 1);
            }
        }
    }

    public function loadCompatMenu() {
        add_options_page('Nextend FB Connect', 'Nextend FB Connect', 'manage_options', 'nextend-facebook-connect', array(
            'NextendFBSettings',
            'NextendFB_Options_Page'
        ));
    }

    public function import() {
        $oldSettings = maybe_unserialize(get_option('nextend_fb_connect'));
        if ($oldSettings === false) {
            $newSettings['legacy'] = 0;
            $this->settings->update($newSettings);
        } else if (!empty($oldSettings['fb_appid']) && !empty($oldSettings['fb_secret'])) {
            $newSettings = array(
                'appid'  => $oldSettings['fb_appid'],
                'secret' => $oldSettings['fb_secret']
            );

            if (!empty($oldSettings['fb_user_prefix'])) {
                $newSettings['user_prefix'] = $oldSettings['fb_user_prefix'];
            }

            $newSettings['legacy'] = 0;
            $this->settings->update($newSettings);

            delete_option('nextend_fb_connect');
        }

        return true;
    }

    public function adminDisplaySubView($subview) {
        if ($subview == 'import' && $this->settings->get('legacy') == 1) {
            $this->renderAdmin('import', false);
        } else {
            parent::adminDisplaySubView($subview);
        }
    }
}

NextendSocialLogin::addProvider(new NextendSocialProviderFacebook);