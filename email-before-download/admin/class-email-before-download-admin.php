<?php


/**
 *
 * @since      5.0.0
 * @package    Email_Before_Download
 * @subpackage Email_Before_Download/admin
 * @author     M & S Consulting
 */
class Email_Before_Download_Admin
{

    private $plugin_name;
    private $version;
    private $option_name = 'email_before_download';
    private  $page_title = "Email Before Download";
    private $db;
    private $options  = array(
        array('name' => '_support', 'type' => 'section','label' => "Support Links"),
        array('name' => '_general', 'type' => 'section','label' => "General Settings"),
        array('name' => '_delivery_format', 'type' => 'setting', 'label' => "Default Delivery Format", 'section' => '_general', 'default' => "inline link"),
        array('name' => '_expire', 'type' => 'setting', 'label' => "Default Link Expiration Time", 'section' => '_general', 'default' => "0"),
        array('name' => '_forbidden', 'type' => 'setting', 'label' => "Forbidden Domains", 'section' => '_general'),
        array('name' => '_inline', 'type' => 'section','label' => "Inline Link Settings"),
        array('name' => '_link_format', 'type' => 'setting', 'label' => "Default Link Target", 'section' => '_inline', 'default' => "_self"),
        array('name' => '_link_css', 'type' => 'setting', 'label' => "Custom CSS", 'section' => '_inline'),
        array('name' => '_html_before', 'type' => 'setting', 'label' => "HTML Before Link", 'section' => '_inline'),
        array('name' => '_html_after', 'type' => 'setting', 'label' => "HTML After Link", 'section' => '_inline'),
        array('name' => '_email', 'type' => 'section','label' => "Email Settings"),
        array('name' => '_single_email', 'type' => 'setting', 'label' => "Single URL Template", 'section' => '_email'),
        array('name' => '_multi_email', 'type' => 'setting', 'label' => "Multiple URL Template", 'section' => '_email'),
        array('name' => '_attachment', 'type' => 'setting', 'label' => "Default Attachment", 'section' => '_email'),
        array('name' => '_subject', 'type' => 'setting', 'label' => "From Email Subject", 'section' => '_email', 'default' => "Your Requested File(s)"),
        array('name' => '_from_name', 'type' => 'setting', 'label' => "From Email Name", 'section' => '_email'),
        array('name' => '_email_from', 'type' => 'setting', 'label' => "From Email", 'section' => '_email'),
        array('name' => '_other', 'type' => 'section','label' => "Additional Settings"),
        array('name' => '_default_cf7', 'type' => 'setting', 'label' => "Default CF7 Form ID", 'section' => '_other'),
        array('name' =>  '_multi_check', 'type' => 'setting', 'label' => "Default Checkbox State", 'section' => '_other', 'default' => " "),
        array('name' => '_hidden_form', 'type' => 'setting', 'label' => "Default Hide Form", 'section' => '_other', 'default' => "no"),
        array('name' => '_radio', 'type' => 'setting', 'label' => "Default Radio Buttons", 'section' => '_other', 'default' => "no"),
        array('name' => '_file_thumbnail', 'type' => 'setting', 'label' => "Default Display File Thumbnail", 'section' => '_other', 'default' => "no")

    );

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->db = new Email_Before_Download_DB();
    }

    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/email-before-download-admin.css', array(),
            $this->version,
            'all');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/email-before-download-admin.js',
            array('jquery'),
            $this->version,
            true);
    }
    public function flush_api(){
        flush_rewrite_rules();
    }
    public function add_options_page()
    {
            $main_page = add_menu_page(
                __( 'Email Before Download', 'email-before-download' ),
                __( 'Email Before Download', 'email-before-download' ),
                'manage_options',
                'email-before-download',
                array(
                    $this,
                    'display_options_page'
                ),
                //'data:image/svg+xml;base64,'.base64_encode(file_get_contents(plugin_dir_url( dirname( __FILE__ ) ) . 'assets/group3.svg')),
                'data:image/svg+xml;base64,'.base64_encode(
                    '<svg width="70" height="71" viewBox="0 0 70 71" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M35 56.0002L28.4 51.7002L4 71.0002H65.6L41.4 51.8002L35 56.0002Z" fill="black"/>
<path d="M70 68.1002V33.2002L45.8 49.0002L70 68.1002Z" fill="black"/>
<path d="M0 22.7V27.3L35 50L70 27.3V22.7L35 0L0 22.7ZM37.5 31L43.2 25.3L46.7 28.8L35 40.5L23.2 28.8L23.3 28.7L26.8 25.2L32.5 30.9V10H37.5V31V31Z" fill="black"/>
<path d="M0 33.2002V67.8002L24 48.8002L0 33.2002Z" fill="black"/>
</svg>'),
                30
            );

            // add_action('admin_menu', 'wpcf7cf_admin_add_page');
            add_submenu_page( 'email-before-download',
                'Submenu Page Title',
                __( 'Options', 'email-before-download' ),
                'manage_options',
                'email-before-download',
                array(
                    $this,
                    'display_options_page'
                )   
            );
        add_submenu_page(
            'email-before-download',
            'Email Before Download Links',
            'Email Before Download Links',
            'administrator',
            $this->plugin_name.'-links',
            array($this,'display_links_page'));
        
        add_submenu_page(
            null,
            'Email Before Download Links',
            'Email Before Download Links',
            'administrator',
            $this->plugin_name.'-links',
            array($this,'display_links_page'));


        add_submenu_page(
            'email-before-download',
            'Email Before Download Logs',
            'Email Before Download Logs',
            'administrator',
            $this->plugin_name.'-logs',
            array($this,'display_logs_page'));
        
        add_submenu_page(
            null,
            'Email Before Download Logs',
            'Email Before Download Logs',
            'administrator',
            $this->plugin_name.'-logs',
            array($this,'display_logs_page'));
    }

    public function display_options_page()
    {

        include_once 'partials/email-before-download-admin-display.php';
    }
    public function display_links_page()
    {
        $wp_table = new Email_Before_download_Table();
        $wp_table->set_table('links');
        $wp_table->prepare_items();
        $title = $this->page_title. " Links";
            include_once 'partials/email-before-download-admin-table.php';
    }
    public function display_logs_page()
    {
        $wp_table = new Email_Before_download_Table();
        $wp_table->set_table('logs');
        $wp_table->prepare_items();
        $title = $this->page_title. " Logs";
        include_once 'partials/email-before-download-admin-table.php';
    }
    function print_csv()
    {
        if ( ! current_user_can( 'manage_options' ) )
            return;
        if(isset($_GET['table'])){
            $table = sanitize_key($_GET['table']);
            global $wpdb;
            $valid_tables = array($wpdb->prefix . "ebd_link" , $wpdb->prefix . "ebd_posted_data");
            if (!in_array($table, $valid_tables)) {
                wp_die(__('This table doesnot exists.' ));
            }            
            $csv = $this->db->export($table);
            $filename = $table.'-'.date('Y-m-d H:i:s',time()).'.csv';
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename='.$filename);
            header('Pragma: no-cache');
            echo $csv;
        }

        return;

    }
    public function purge_data()
    {
        if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET[ 'dopurge' ] ) ), 'dopurge_nonce' ) ) {
          die( __( 'Security check', 'textdomain' ) ); 
        }
        if ( ! current_user_can( 'manage_options' ) )
            return;
        if(isset($_GET['table'])) {
            $table = sanitize_key($_GET['table']);
            global $wpdb;
            $valid_tables = array($wpdb->prefix . "ebd_link" , $wpdb->prefix . "ebd_posted_data");
            if (!in_array($table, $valid_tables)) {
                wp_die(__('This table doesnot exists.' ));
            }            
            $this->db->clean_up($table);
        }
        return;

    }
    public function build_settings()
    {

        foreach ($this->options as $option){
            if($option['type'] == 'section'){
                add_settings_section(
                        $this->option_name . $option['name'],
                        $option['label'],
                        array($this, $this->option_name . $option['name'].'_cb'),
                        $this->plugin_name
                );
            }else{
                add_settings_field(
                    $this->option_name . $option['name'],
                    $option['label'],
                    array($this, $this->option_name . $option['name'].'_cb'),
                    $this->plugin_name,
                    $this->option_name . $option['section'],
                    array('label_for' => $this->option_name . $option['name'])
                );
            }
        }

    }

    public function register_settings()
    {
        foreach ($this->options as $option){
            if($option['type'] == 'setting')
                register_setting(
                        $this->plugin_name,
                        $this->option_name . $option['name'],
                        'string'
                );

        }
    }
    public function set_option_defaults(){

    }


    public function email_before_download_inline_cb()
    {
        echo '<div class="ebd_section_description">' . __('The following settings apply if you selected "Inline Link" or "Both" as the Delivery Format ', 'email-before-download') . '</div>';
    }

    public function email_before_download_email_cb()
    {
        echo '<div class="ebd_section_description">' . __('The following settings apply if you selected "Email" or "Both" as the Delivery Format ', 'email-before-download') . '</div>';
    }
    public function email_before_download_general_cb()
    {
        echo '<div class="ebd_section_description">' . __('General plugin settings ', 'email-before-download') . '</div>';
    }
    public function email_before_download_support_cb()
    {
        echo "<span class='ebd_subtitle'>". __('Email Before Download started as a plugin we developed for personal in-house needs. We realized this could be useful to other WordPress users. At that point, we made the decision to release the plugin for anyone to use. We apologize if we cannot provide support to you on an individual or timely basis. However, please feel free to post in the WordPress forums where we hope your questions can be answered by the WordPress community.', 'email-before-download')."</span>";
        echo "<span class='ebd_subtitle'><b><a href=\"https://wordpress.org/support/topic/email-before-download-pro-beta-testers-needed/\" target=\"_blank\">FREE Early Access to EBD Pro Version! </a></b></span>";

        echo "<ul>
            <li><a href=\"https://www.mandsconsulting.com/products/wp-email-before-download\" target=\"_blank\">".__('Plugin Homepage at M&amp;S Consulting with Live Demos and Test Download','email-before-download')."</a></li>
            <li><a href=\"https://wordpress.org/plugins/email-before-download/V\" target=\"_blank\">". __('Plugin Homepage at WordPress', 'email-before-download')."</a></li>
            <li><a href=\"https://wordpress.org/plugins/email-before-download/#changelog\" target=\"_blank\">".__('Plugin Changelog: Current and Past Releases','email-before-download')."</a></li>
            <li><a href=\"https://wordpress.org/support/topic-tag/email-before-download/?forum_id=10\" target=\"_blank\">".__('Plugin Support Forums','email-before-download')."</a></li>
        </ul>";

    }

    public function email_before_download_delivery_format_cb()
    {
        ?>
        <select class="ebd_input" name="<?php echo esc_attr($this->option_name) . '_delivery_format' ?>">
            <option value="inline link" <?php if (get_option($this->option_name . '_delivery_format') == 'inline link') echo 'selected="selected"'; ?> >
                <?php _e('Inline Link','email-before-download'); ?>
            </option>
            <option value="send email" <?php if (get_option($this->option_name . '_delivery_format') == 'send email') echo 'selected="selected"'; ?> >
                <?php _e('Send Email','email-before-download'); ?>
            </option>
            <option value="both" <?php if (get_option($this->option_name . '_delivery_format') == 'both') echo 'selected="selected"'; ?> >
                <?php _e('Both','email-before-download'); ?>
            </option>
        </select>
        <span class="ebd_subtitle"> <?php _e('Can be overridden in the shortcode with: delivered_as="inline link/send email/both" ','email-before-downlaod'); ?></span>

        <?php
    }



    public function email_before_download_expire_cb()
    {
        ?>
        <select class="ebd_input" id="<?php echo esc_attr($this->option_name) . '_expire' ?>"
                name="<?php echo esc_attr($this->option_name) . '_expire' ?>">
            <option value="0" <?php if (get_option($this->option_name . '_expire') == '0') echo 'selected="selected"'; ?> >
                Never
            </option>
            <option value="1 minute" <?php if (get_option($this->option_name . '_expire') == '1 minute') echo 'selected="selected"'; ?> >
                1 min
            </option>
            <option value="3 minute" <?php if (get_option($this->option_name . '_expire') == '3 minute') echo 'selected="selected"'; ?> >
                3 min
            </option>
            <option value="10 minute" <?php if (get_option($this->option_name . '_expire') == '10 minute') echo 'selected="selected"'; ?> >
                10 min
            </option>
            <option value="30 minute" <?php if (get_option($this->option_name . '_expire') == '30 minute') echo 'selected="selected"'; ?> >
                30 min
            </option>
            <option value="1 hour" <?php if (get_option($this->option_name . '_expire') == '1 hour') echo 'selected="selected"'; ?> >
                1 hr
            </option>
            <option value="12 hour" <?php if (get_option($this->option_name . '_expire') == '12 hour') echo 'selected="selected"'; ?> >
                12 hr
            </option>
            <option value="1 day" <?php if (get_option($this->option_name . '_expire') == '1 day') echo 'selected="selected"'; ?> >
                1 day
            </option>
            <option value="1 week" <?php if (get_option($this->option_name . '_expire') == '1 week') echo 'selected="selected"'; ?> >
                1 week
            </option>
        </select>
        <span class="ebd_subtitle"> <?php _e('This option expires the link after a set period of time. <br>It can be overridden in the shortcode with: expire_time="X minute/hour/day/week".','email-before-downlaod'); ?></span>
        <?php
    }

    public function email_before_download_forbidden_cb()
    {
        ?>
        <textarea class="ebd_input" cols="40" rows="10"
                  name="<?php echo esc_attr($this->option_name) . '_forbidden' ?>"><?php echo esc_textarea(get_option($this->option_name . '_forbidden')); ?></textarea>
        <span class="ebd_subtitle"><?php _e('A comma separated list of the forbidden domains','email-before-download'); ?></span>
        <?php
    }

    public function email_before_download_link_format_cb()
    {
        ?>
        <select class="ebd_input" name="<?php echo esc_attr($this->option_name) . '_link_format' ?>">
            <option value="_blank" <?php if (get_option($this->option_name . '_link_format') == '_blank') echo 'selected="selected"'; ?> >
                _blank
            </option>
            <option value="_self" <?php if (get_option($this->option_name . '_link_format') == '_self') echo 'selected="selected"'; ?> >
                _self
            </option>
        </select>
        <span class="ebd_subtitle"><i> <?php _e('This option can be overridden in the shortcode with: link_format="_self/_blank"','email-before-download'); ?></i></span>
        <?php
    }

    public function email_before_download_link_css_cb()
    {
        ?>
        <input type="text" size="40" name="<?php echo esc_attr($this->option_name) . '_link_css' ?>"
               value="<?php echo esc_attr(get_option($this->option_name . '_link_css')); ?>"/>
        <span class="ebd_subtitle"> <?php _e('CSS class used to render the div and the link','email-before-download'); ?></span>
        <?php
    }

    public function email_before_download_html_before_cb()
    {
        ?>
        <input type="text" size="40" name="<?php echo esc_attr($this->option_name) . '_html_before' ?>"
               value="<?php echo esc_attr(get_option($this->option_name . '_html_before')); ?>"/>
        <span class="ebd_subtitle"> <?php _e('HTML you want to be added before the link','email-before-dowjnload'); ?></span>
        <?php
    }

    public function email_before_download_html_after_cb()
    {
        ?>
        <input type="text" size="40" name="<?php echo esc_attr($this->option_name) . '_html_after' ?>"
               value="<?php echo esc_attr(get_option($this->option_name . '_html_after')); ?>"/>
        <span class="ebd_subtitle"> <?php _e('HTML you want to be added after the link','email-before-download'); ?> </span>
        <?php
    }

    public function email_before_download_single_email_cb()
    {
        ?>
        <textarea class="ebd_input" cols="40" rows="10"
                  name="<?php echo esc_attr($this->option_name) . '_single_email' ?>"><?php echo esc_textarea(get_option($this->option_name . '_single_email')); ?></textarea>
        <span class="ebd_subtitle"> <?php _e("[file_name] and [file_url] are specific placeholders for Email Before Download. Placeholders from Contact Form 7 can be used as long as your form has the same fields.  If a placeholder is used with no corresponding form field, it will be ignored.",'email-before-download'); ?><br>
          <br><?php _e('Note.  If you leave this field empty, The default template will be used.','email-before-download'); ?>
            <p><?php _e("The Default template is:<br><strong> Here is the download for",'email-before-download');  echo htmlentities('<a href="[file_url]">[file_name]</a>'); _e("that you requested.</strong>",'email-before-download'); ?>
            </p></span>
        <?php
    }

    public function email_before_download_multi_email_cb()
    {
        ?>
        <textarea class="ebd_input" cols="40" rows="10"
                  name="<?php echo esc_attr($this->option_name) . '_multi_email' ?>"><?php echo esc_textarea(get_option($this->option_name . '_multi_email')); ?></textarea>
        <span class="ebd_subtitle"><?php _e('The same rules apply to this template as the single URL template. <br>Use the following placeholder for multiple urls: [file_urls]</br> If this is left blank, a list of URLs will be sent.','email-before-download'); ?>  </span>
        <?php
    }

    public function email_before_download_attachment_cb()
    {
        ?>
        <input type="checkbox" size="40" id="<?php echo esc_attr($this->option_name) . '_attachment' ?>"
               name="<?php echo esc_attr($this->option_name) . '_attachment' ?>"
               value="1" <?php if (get_option($this->option_name . '_attachment')) echo 'checked="checked"'; ?> />
        <span class="ebd_subtitle"><?php _e('This can only be applied to the files uploaded using Download Monitor plugin.<br> This option can be overridden in the shorcode with: attachment="yes/no"','email-before-download');?></span>
        <?php
    }

    public function email_before_download_subject_cb()
    {
        ?>
        <input type="text" size="40" name="<?php echo esc_attr($this->option_name) . '_subject' ?>"
               value="<?php echo esc_attr(get_option($this->option_name . '_subject')); ?>"/>
        <span class="ebd_subtitle"><?php _e('If this field is left blank, the default subject is: "Your requested file(s)".','email-before-download');?></span>
        <?php
    }
    public function email_before_download_email_from_cb()
    {
        ?>
        <input type="text" size="40" name="<?php echo esc_attr($this->option_name) . '_email_from' ?>"
               value="<?php echo esc_attr(get_option($this->option_name . '_email_from')); ?>"/>
        <span class="ebd_subtitle"><?php _e('Email address used to deliver the download links. If this field is left blank, the default wordpress email will be used..','email-before-download');?></span>
        <?php
    }
    public function email_before_download_from_name_cb()
    {
        ?>
        <input type="text" size="40" name="<?php echo esc_attr($this->option_name) . '_from_name' ?>"
               value="<?php echo esc_attr(get_option($this->option_name . '_from_name')); ?>"/>
        <span class="ebd_subtitle"><?php _e('Name used to deliver the download links. If this field is left blank, the default wordpress email name will be used..','email-before-download');?></span>
        <?php
    }

    public function email_before_download_other_cb()
    {
        echo '<div class="ebd_section_description">' . __('The following settings only apply if you have multiple urls in you shortcode ', 'email-before-download') . '</div>';
    }
    public function email_before_download_default_cf7_cb()
    {
        ?>
        <input type="text" size="40" name="<?php echo esc_attr($this->option_name) . '_default_cf7' ?>"
               value="<?php echo esc_attr(get_option($this->option_name . '_default_cf7')); ?>"/>
        <span class="ebd_subtitle"> <?php _e('If you want to use the same form for all your downloads.</br> This can be overwritten in the shortcode with: contact-form-id="X"  ','email-before-download'); ?></span>
        <?php
    }

    public function email_before_download_multi_check_cb()
    {
        ?>
        <select class="ebd_input" name="<?php echo esc_attr($this->option_name) . '_multi_check' ?>">
            <option value="checked" <?php if (get_option($this->option_name . '_multi_check') == 'checked') echo 'selected="selected"'; ?> >
                Checked
            </option>
            <option value="" <?php if ((get_option($this->option_name . '_multi_check') == '') || (!get_option($this->option_name . '_multi_check'))) echo 'selected="selected"'; ?> >
                Unchecked
            </option>
        </select>
        <span class="ebd_subtitle"><?php _e('The default state of the Multiple Checkboxes.<br> This can be overridden in the shortcode with: checked="yes/no"','email-before-download'); ?></span>
        <?php
    }

    public function email_before_download_hidden_form_cb()
    {
        ?>
        <select class="ebd_input" name="<?php echo esc_attr($this->option_name) . '_hidden_form' ?>">
            <option value="yes" <?php if (get_option($this->option_name . '_hidden_form') == 'yes') echo 'selected="selected"'; ?> >
                <?php _e('Yes','email-before-download'); ?>
            </option>
            <option value="no" <?php if ((get_option($this->option_name . '_hidden_form')) == 'no'||(!get_option($this->option_name . '_hidden_form'))) echo 'selected="selected"'; ?> >
                <?php _e('No','email-before-download'); ?>
            </option>
        </select>
        <span class="ebd_subtitle"> <?php _e('Hide the form until user selects a download. <br> This can be overriden in the shortcode with hide_form="yes/no"','email-before-download'); ?></span>
        <?php
    }

    public function email_before_download_radio_cb()
    {
        ?>
        <select class="ebd_input" name="<?php echo esc_attr($this->option_name) . '_radio' ?>">
            <option value="yes" <?php if (get_option($this->option_name . '_radio') == 'yes') echo 'selected="selected"'; ?> >
                <?php _e('Yes','email-before-download'); ?>
            </option>
            <option value="no" <?php if ((get_option($this->option_name . '_radio') == 'no') || (!get_option($this->option_name . '_radio'))) echo 'selected="selected"'; ?> >
                <?php _e('No','email-before-download'); ?>
            </option>
        </select>
        <span class="ebd_subtitle"><?php _e('Radio buttons instead of checkboxes.<br> This can be overridden in the shortcode with: radio="yes/no"','email-before-download'); ?></span>
        <?php
    }
    
    public function email_before_download_file_thumbnail_cb()
    {
        ?>
        <select class="ebd_input" name="<?php echo esc_attr($this->option_name) . '_file_thumbnail' ?>">
            <option value="yes" <?php if (get_option($this->option_name . '_file_thumbnail') == 'yes') echo 'selected="selected"'; ?> >
                <?php _e('Yes','email-before-download'); ?>
            </option>
            <option value="no" <?php if ((get_option($this->option_name . '_file_thumbnail') == 'no') || (!get_option($this->option_name . '_file_thumbnail'))) echo 'selected="selected"'; ?> >
                <?php _e('No','email-before-download'); ?>
            </option>
        </select>
        <span class="ebd_subtitle"><?php _e('Display thumbnail (aka "Featured Image") of your Download Monitor Files along with the file\'s title. This can be overridden in the shortcode with: file_thumbnail="yes/no". If "yes", the thumbnail will display to the left file title at dimensions of 40px by 40px. If file thumbnail is not specified in Download Monitor, a default thumbnail is displayed.','email-before-download'); ?></span>
        <?php
    }

}