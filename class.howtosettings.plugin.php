<?php defined('APPLICATION') or die;

$PluginInfo['HowToSettings'] = array(
    'Name' => 'HowTo: Settings',
    'Description' => 'Shows how to create a settings screen for your plugins.',
    'Version' => '0.1',
    'RequiredApplications' => array('Vanilla' => '>= 2.1'),
    'RequiredPlugins' => array('HtmLawed' => '>= 1.0.1'),
    'RequiredTheme' => false,
    'SettingsPermission' => array('Garden.Settings.Manage', 'HowToSettings.Settings.Manage'),
    'SettingsUrl' => '/dashboard/settings/howtosettings',
    'RegisterPermissions' => array('HowToSettings.Settings.Manage'),
    'MobileFriendly' => true,
    'HasLocale' => false,
    'Author' => 'Robin Jurinka',
    'AuthorUrl' => 'http://vanillaforums.org/profile/44046/R_J',
    'License' => 'MIT'
);



/**
 * Plugin that shows a quick way to build a settings screen.
 *
 * See here for some more and detailed information:
 * http://vanillaforums.org/discussion/comment/224540/#Comment_224540
 *
 * @package HowToSettings
 * @author Robin Jurinka
 * @license MIT
 */
class HowToSettingsPlugin extends Gdn_Plugin {
    /**
     * Adds menu entry to dashboard.
     *
     * I would recommend to not add a link to the dashboard panel for your
     * plugin. It will clutter the navigation if every plugin would do it.
     * That's why I've commented the "addLink" part. I wanted to show you how
     * it could be done, but disencourage you to do so ;-)
     *
     * @param  object $sender Garden Controller.
     * @return void.
     * @package HowToSettings
     * @since 0.1
     */
    public function base_getAppSettingsMenuItems_handler ($sender) {
        // get a reference to the dashboards panel menu.
        $menu = &$sender->EventArguments['SideMenu'];

        // you can add your link to every section you like: Dashboard,
        // AppearanceUsers, Moderation, Forum, Add-ons, Site Settings, Import
        // If you use a non existing code, you will get your own group in the
        // dashboard navigation.
        // Link is only shown for the permissions specified.
        /*
        // Only uncomment and use it if you really think your settings deserve
        // a prominent place.
        $menu->addLink(
            'Add-ons',
            t('HowToSettings Settings'),
            '/dashboard/settings/howtosettings',
            array(
                'Garden.Settings.Manage',
                'HowToSettings.Settings.Manage'
            )
        );
        */
    }


    /**
     * All that is needed for a nice settings screen.
     *
     * When you have a "'SettingsUrl' => someLink" in $PluginInfo, you will see
     * a button in the plugins list as soon as you have enabled your plugin.
     * Pressing that button will send you to the link.
     *
     * @param  object $sender SettingsController.
     * @return void.
     * @package HowToSettings
     * @since 0.1
     */
    public function settingsController_howToSettings_create ($sender) {
        // don't forget to check the permissions, otherwise anybody can change
        // the settings! But you don't have to create a custom settings
        // permission. If you only specify one permission, you could do it like
        // that: $sender->permission('Garden.Settings.Manage');
        $sender->permission(array(
            'Garden.Settings.Manage',
            'HowToSettings.Settings.Manage'
        ));

        // set navigation marker before the panel entry "Plugins".
        // Use this if you haven't uncommented out the addLink in
        // base_getAppSettingsMenuItems_handler
        $sender->addSideMenu('dashboard/settings/plugins');
        // Use this line instead if you have decided to have a navigation link
        // in the panel:
        // $sender->addSideMenu('/dashboard/settings/howtosettings');

        // set a nice title to our setting screen
        $sender->setData('Title', t('HowToSettings Settings'));

        // this will do all the magic for us. So if you want to really
        // understand what is going on, look at
        // /library/core/class.configurationmodel.php
        $configurationModule = new ConfigurationModule($sender);

        // fill in all the info that is needed to build the settings view
        $configurationModule->initialize(array(
            // if you provide only the name of the config value, the
            // ConfigurationModul creates a textbox for that entry
            // with a label it guesses from the name
            'HowToSettings.Minimal',

            // you can add an info array for having more control about what is
            // rendered.
            'HowToSettings.SomeText' => array(
                'Default' => 'Hello World!',
                'Options' => array('class' => 'InputBox BigInput')
            ),

            // you can pass options to the html element, change the label and/or
            // give a description. "TextBox" is the default control, but your
            // code is way more readable if you provide it!
            'HowToSettings.MultiLineText' => array(
                'Control' => 'TextBox',
                'LabelCode' => 'Here is a multi line textarea!',
                'Description' => 'Enter some more text here...',
                'Options' => array(
                    'MultiLine' => true
                )
            ),

            // of course there are other form controls. DropDown, for example.
            'HowToSettings.OneOfAList' => array(
                'Control' => 'DropDown',
                'Items' => array(
                    'i1' => 'One Item',
                    'i2' => 'Another one',
                    'i3' => 'The Third'
                ),
                'LabelCode' => 'Choose one!',
                'Options' => array(
                    'IncludeNull' => true
                )
            ),

            // but it doesn't need so much options. Just look at that
            'HowToSettings.PrimeNumber' => array(
                'Control' => 'DropDown',
                'Items' => array(1,2,3,5,7,11)
            ),

            // there is a special dropdown to make your life easier
            'HowToSettings.Category' => array(
                'Control' => 'CategoryDropDown',
                'LabelCode' => "What's your favorite category?",
                'Description' => 'Besides its content, this is a normal DropDown',
                'Options' => array(
                    'IncludeNull' => true
                )
            ),

            // need a checkbox?
            'HowToSettings.OnOff' => array(
                'Control' => 'CheckBox',
                'Description' => 'Choose wise...',
                'LabelCode' => 'This is awesome!',
                'Default' => true
            ),

            // there is one missing: the radio list. You can decide by yourself
            // how much info you like to provide. Just the same as for the
            // checkbox
            'HowToSettings.KilledByVideo' => array(
                'Control' => 'RadioList',
                'LabelCode' => 'Phew...',
                'Items' => array(
                    'w' => 'Wow',
                    'y' => 'Yeesh'
                ),
                'Default' => 'w'
            ),

            // even image upload is supported, but "LabelCode" is the only thing
            // you can influence here.
            'HowToSettings.Picture' => array(
                'Control' => 'ImageUpload',
                'LabelCode' => 'Upload a test picture if you like'
            )
        ));
        $configurationModule->renderAll();
    }

    /**
     * This is called whenever a plugin is disabled.
     *
     * We delete the settings we made in this plugins from config.php, beacuse
     * after all this is all rubbish ;-)
     *
     * @return void.
     * @package HowToSettings
     * @since 0.1
     */
    public function onDisable () {
        removeFromConfig('HowToSettings');
    }

}
