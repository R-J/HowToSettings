<?php

/**
 * Plugin that shows a quick way to build a settings page.
 *
 * @package HowToSettings
 * @author Robin Jurinka
 * @license MIT
 */
class HowToSettingsPlugin extends Gdn_Plugin {
    /**
     * All that is needed for a nice settings page.
     *
     * When you have a "'SettingsUrl' => someLink" in $PluginInfo, you will see
     * a button in the plugins list as soon as you have enabled your plugin.
     * Pressing that button will send you to the link.
     *
     * @param SettingsController $sender Instance of the calling class.
     *
     * @return void.
     * @package HowToSettings
     * @since 0.1
     */
    public function settingsController_howToSettings_create($sender) {
        // Never forget checking permisssions!
        $sender->permission('Garden.Settings.Manage');

        // Highlight the "Plugins" menu item in the panel.
        $sender->setHighlightRoute('dashboard/settings/plugins');

        // Give your setting page an informative title.
        $sender->setData('Title', t('HowToSettings Settings'));

        // This will do all the magic for us. So if you want to really
        // understand what is going on, look at
        // /library/core/class.configurationmodel.php
        $configurationModule = new ConfigurationModule($sender);

        // Fill in all the info that is needed to build the settings view.
        $configurationModule->initialize([
            // If you provide only the name of the config value, the
            // ConfigurationModul creates a textbox for that entry
            // with a label it guesses from the name.
            'HowToSettings.Minimal',

            // You can add an info array for having more control about what is
            // rendered.
            'HowToSettings.SomeText' => [
                'Default' => 'Hello World!',
                'Options' => ['class' => 'InputBox BigInput']
            ],

            // You can pass options to the html element, change the label and/or
            // give a description. "TextBox" is the default control, but your
            // code is way more readable if you provide it!
            'HowToSettings.MultiLineText' => [
                'Control' => 'TextBox',
                'LabelCode' => 'Here is a multi line textarea!',
                'Description' => 'Enter some more text here...',
                'Options' => ['MultiLine' => true]
            ],

            // Of course there are other form controls. DropDown, for example.
            'HowToSettings.OneOfAList' => [
                'Control' => 'DropDown',
                'Items' => [
                    'i1' => 'One Item',
                    'i2' => 'Another one',
                    'i3' => 'The Third'
                ],
                'LabelCode' => 'Choose one!',
                'Options' => ['IncludeNull' => true]
            ],

            // But it doesn't need so much options. Just look at that:
            'HowToSettings.PrimeNumber' => [
                'Control' => 'DropDown',
                'Items' => [1,2,3,5,7,11]
            ],

            // There is a special dropdown for categories to make your life
            // easier.
            'HowToSettings.Category' => [
                'Control' => 'CategoryDropDown',
                'LabelCode' => "What's your favorite category?",
                'Description' => 'Besides its content, this is a normal DropDown',
                'Options' => ['IncludeNull' => true]
            ],

            // Need a checkbox?
            'HowToSettings.OnOff' => [
                'Control' => 'CheckBox',
                'Description' => 'Choose wise...',
                'LabelCode' => 'This is awesome!',
                'Default' => true
            ],

            // There is one missing: the radio list. You can decide by yourself
            // how much info you like to provide. Just the same as for the
            // checkbox.
            'HowToSettings.KilledByVideo' => [
                'Control' => 'RadioList',
                'LabelCode' => 'Phew...',
                'Items' => [
                    'w' => 'Wow',
                    'y' => 'Yeesh'
                ],
                'Default' => 'w'
            ],

            // Even image upload is supported, but "LabelCode" is the only thing
            // you can influence here.
            'HowToSettings.Picture' => [
                'Control' => 'ImageUpload',
                'LabelCode' => 'Upload a test picture if you like'
            ]
        ]);

        // The configuration takes our instructions and renders a nice form
        // from it. It also handles saving our values to the config.
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
    public function onDisable() {
        removeFromConfig('HowToSettings');
    }
}
