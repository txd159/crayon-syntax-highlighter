<?php

require_once (CRAYON_ROOT_PATH . 'crayon_wp.class.php');

class Input {
    public $id;
    public $name;
    public $value;
    public $type;
    public static $cssPrefix = "crayon-theme-input-";

    public function __construct($id, $name, $value = '', $type = 'text') {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
    }

    public function __toString() {
        return '<input id="' . self::$cssPrefix . $this->id . '" class="' . self::$cssPrefix . $this->type . '" type="' . $this->type . '" />';
    }
}

class CrayonThemeEditorWP {

    public static $fields = NULL;
    public static $settings = NULL;
    public static $strings = NULL;

    public static function init() {
        self::admin_resources();
    }

    public static function initFields() {
        if (self::$fields === NULL) {
            self::$fields = array(
                // These are canonical and can't be translated, since they appear in the comments of the CSS
                'name' => 'Name',
                'description' => 'Description',
                'version' => 'Version',
                'author' => 'Author',
                'url' => 'URL',
                'original-author' => 'Original Author',
                'notes' => 'Notes',
                'maintainer' => 'Maintainer',
                'maintainer-url' => 'Maintainer URL'
                // These appear only in the UI and can be translated

            );
        }
    }

    public static function initSettings() {

        self::initFields();
        self::initStrings();
        if (self::$settings === NULL) {
            self::$settings = array(
                // Only things the theme editor needs
                'cssPrefix' => Input::$cssPrefix,
                'fields' => self::$fields,
                'fieldsInverse' => array_flip(self::$fields),
                'prefix' => 'crayon-theme-editor'
            );
        }
    }

    public static function initStrings() {
        if (self::$strings === NULL) {
            self::$strings = array(
                // Only things the theme editor needs
                'No' => crayon__("No"),
                'Yes' => crayon__("Yes"),
                'userTheme' => crayon__("User-Defined Theme"),
                'stockTheme' => crayon__("Stock Theme")
            );
        }
    }

    public static function admin_resources() {
        global $CRAYON_VERSION;
        self::initSettings();
        wp_enqueue_script('cssjson_js', plugins_url(CRAYON_CSSJSON_JS, dirname(dirname(__FILE__))), $CRAYON_VERSION);
        wp_enqueue_script('jquery_ui_js', plugins_url(CRAYON_JS_JQUERY_UI, dirname(dirname(__FILE__))), array('jquery'), $CRAYON_VERSION);
        wp_enqueue_script('crayon_theme_editor', plugins_url(CRAYON_THEME_EDITOR_JS, dirname(dirname(__FILE__))), array('jquery', 'jquery_ui_js', 'crayon_util_js', 'crayon_admin_js', 'cssjson_js'), $CRAYON_VERSION);
        wp_localize_script('crayon_theme_editor', 'CrayonThemeEditorSettings', self::$settings);
        wp_localize_script('crayon_theme_editor', 'CrayonThemeEditorStrings', self::$strings);

        wp_enqueue_style('jquery_ui', plugins_url(CRAYON_CSS_JQUERY_UI, dirname(dirname(__FILE__))), array(), $CRAYON_VERSION);
    }

    public static function form($inputs) {
        echo '<form class="', self::$settings['prefix'], '-form"><table>';
        foreach ($inputs as $input) {
            echo '<tr><td class="field">', $input->name, '</td><td class="value">', $input, '</td></tr>';
        }
        echo '</table></form>';
    }

    public static function content() {
        self::initSettings();
        $theme = CrayonResources::themes()->get_default();
        $editing = false;

        if (isset($_GET['curr_theme'])) {
            $currTheme = CrayonResources::themes()->get($_GET['curr_theme']);
            if ($currTheme) {
                $theme = $currTheme;
            }
        }

        if (isset($_GET['editing'])) {
            $editing = CrayonUtil::str_to_bool($_GET['editing'], FALSE);
        }

        ?>

    <div
            id="icon-options-general" class="icon32"></div>
    <h2>
        Crayon Syntax Highlighter
        <?php crayon_e('Theme Editor'); ?>
    </h2>

    <h3 id="<?php echo self::$settings['prefix'] ?>-name">
        <?php
//			if ($editing) {
//				echo sprintf(crayon__('Editing "%s" Theme'), $theme->name());
//			} else {
//				echo sprintf(crayon__('Creating Theme From "%s"'), $theme->name());
//			}
        ?>
    </h3>
    <div id="<?php echo self::$settings['prefix'] ?>-info"></div>

    <p>
        <a id="crayon-editor-back" class="button-primary"><?php crayon_e('Back To Settings'); ?></a>
        <a id="crayon-editor-save" class="button-primary"><?php crayon_e('Save'); ?></a>
        <span id="crayon-editor-status"></span>
    </p>

    <?php //crayon_e('Use the Sidebar on the right to change the Theme of the Preview window.') ?>

    <div
            id="crayon-editor-top-controls"></div>

    <table id="crayon-editor-table" style="width: 100%;" cellspacing="5"
           cellpadding="0">
        <tr>
            <td id="crayon-editor-preview-wrapper">
                <div id="crayon-editor-preview"></div>
            </td>
            <td id="crayon-editor-control-wrapper">
                <div id="crayon-editor-controls">
                    <ul>
                        <li title="General Info"><a href="#tabs-1"></a></li>
                        <li title="Highlighting"><a href="#tabs-2"></a></li>
                        <li title="Lines"><a href="#tabs-3"></a></li>
                        <li title="Numbers"><a href="#tabs-3"></a></li>
                        <li title="Toolbars"><a href="#tabs-3"></a></li>
                    </ul>
                    <div id="tabs-1">
                        <?php
//                        $inputs = array();
//                        foreach (self::$fields as $id => $name) {
//                            $inputs[] = new Input($id, $name);
//                        }
//                        self::form($inputs);
                        ?>
                    </div>
                    <div id="tabs-2">
                        <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus
                            gravida ante, ut pharetra massa metus id nunc. Duis scelerisque
                            molestie turpis. Sed fringilla, massa eget luctus malesuada, metus
                            eros molestie lectus, ut tempus eros massa ut dolor. Aenean
                            aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit
                            aliquam. Praesent in eros vestibulum mi adipiscing adipiscing.
                            Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel
                            metus. Ut posuere viverra nulla. Aliquam erat volutpat.
                            Pellentesque convallis. Maecenas feugiat, tellus pellentesque
                            pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel
                            felis. Mauris consectetur tortor et purus.</p>
                    </div>
                    <div id="tabs-3">
                        <p>Mauris eleifend est et turpis. Duis id erat. Suspendisse
                            potenti. Aliquam vulputate, pede vel vehicula accumsan, mi neque
                            rutrum erat, eu congue orci lorem eget lorem. Vestibulum non ante.
                            Class aptent taciti sociosqu ad litora torquent per conubia
                            nostra, per inceptos himenaeos. Fusce sodales. Quisque eu urna vel
                            enim commodo pellentesque. Praesent eu risus hendrerit ligula
                            tempus pretium. Curabitur lorem enim, pretium nec, feugiat nec,
                            luctus a, lacus.</p>

                        <p>Duis cursus. Maecenas ligula eros, blandit nec, pharetra at,
                            semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent
                            viverra justo vitae neque. Praesent blandit adipiscing velit.
                            Suspendisse potenti. Donec mattis, pede vel pharetra blandit,
                            magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam
                            scelerisque. Donec non libero sed nulla mattis commodo. Ut
                            sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor
                            vitae, pede. Aenean vehicula velit eu tellus interdum rutrum.
                            Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a
                            libero vitae lectus hendrerit hendrerit.</p>
                    </div>
                </div>
            </td>
        </tr>

    </table>

    <?php
        exit();
    }

    /**
     * Saves the given theme id and css, making any necessary path and id changes to ensure the new theme is valid.
     * Echos 0 on failure, 1 on success and 2 on success and if paths have changed.
     */
    public static function save($allow_edit_stock_theme = CRAYON_DEBUG) {
        CrayonSettingsWP::load_settings();
        $oldID = $_POST['id'];
        $name = $_POST['name'];
        $css = stripslashes($_POST['css']);
        $change_settings = CrayonUtil::set_default($_POST['change_settings'], TRUE);
        $delete = CrayonUtil::set_default($_POST['delete'], TRUE);
        $oldTheme = CrayonResources::themes()->get($oldID);

        if (!empty($oldID) && !empty($css) && !empty($name)) {
            // By default, expect a user theme to be saved - prevents editing stock themes
            // If in DEBUG mode, then allow editing stock themes.
            $user = $oldTheme !== NULL && $allow_edit_stock_theme ? $oldTheme->user() : TRUE;
            $oldPath = CrayonResources::themes()->path($oldID);
            $oldDir = CrayonResources::themes()->dirpath($oldID);
            $newID = CrayonResource::clean_id($name);
            $newPath = CrayonResources::themes()->path($newID, $user);
            $newDir = CrayonResources::themes()->dirpath($newID, $user);
            // Create the new path if needed
            if (!is_file($newPath)) {
                if (!is_dir($newDir)) {
                    mkdir($newDir, 0777, TRUE);
                    try {
                        // Copy image folder
                        CrayonUtil::copyDir($oldDir . 'images', $newDir . 'images');
                    } catch (Exception $e) {
                        CrayonLog::syslog($e->getMessage(), "THEME SAVE");
                    }
                }
            }
            $refresh = FALSE;
            $replaceID = $oldID;
            // Replace ids in the CSS
            if (!is_file($oldPath) || strpos($css, CrayonThemes::CSS_PREFIX . $oldID) === FALSE) {
                // The old path/id is no longer valid - something has gone wrong - we should refresh afterwards
                $refresh = TRUE;
                // Forces the ids to be updated
                $replaceID = '[\w-]+';
            }
            // XXX This is case sensitive to avoid modifying text, but it means that CSS must be in lowercase
            $css = preg_replace('#(?<=' . CrayonThemes::CSS_PREFIX . ')' . $replaceID . '\b#ms', $newID, $css);

            $result = @file_put_contents($newPath, $css);
            $success = $result !== FALSE;
            if ($success && $oldPath !== $newPath) {
                if ($oldID !== CrayonThemes::DEFAULT_THEME && $delete) {
                    // Only delete the old path if it isn't the default theme
                    try {
                        // Delete the old path
                        CrayonUtil::deleteDir($oldDir);
                    } catch (Exception $e) {
                        CrayonLog::syslog($e->getMessage(), "THEME SAVE");
                    }
                }
                // Refresh
                echo 2;
            } else {
                echo $refresh ? 2 : intval($success);
            }
            // Set the new theme in settings
            if ($change_settings) {
                CrayonGlobalSettings::set(CrayonSettings::THEME, $newID);
                CrayonSettingsWP::save_settings();
            }
        } else {
            CrayonLog::syslog("$oldID=$oldID\n\n$name=$name", "THEME SAVE");
            echo 0;
        }
        exit();
    }

    public static function duplicate() {
        CrayonSettingsWP::load_settings();
        $oldID = $_POST['id'];
        $oldPath = CrayonResources::themes()->path($oldID);
        $_POST['css'] = file_get_contents($oldPath);
        $_POST['delete'] = FALSE;
        self::save(FALSE);
    }

    public static function delete() {
        CrayonSettingsWP::load_settings();
        $id = $_POST['id'];
        $dir = CrayonResources::themes()->dirpath($id);
        if (is_dir($dir) && CrayonResources::themes()->exists($id)) {
            try {
                CrayonUtil::deleteDir($dir);
                CrayonGlobalSettings::set(CrayonSettings::THEME, CrayonThemes::DEFAULT_THEME);
                CrayonSettingsWP::save_settings();
                echo 1;
            } catch (Exception $e) {
                CrayonLog::syslog($e->getMessage(), "THEME SAVE");
                echo 0;
            }
        } else {
            echo 0;
        }
        exit();
    }

}

if (defined('ABSPATH') && is_admin()) {
    add_action('init', 'CrayonThemeEditorWP::init');
}

?>
