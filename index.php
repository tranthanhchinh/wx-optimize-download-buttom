<?php
/**
 * Plugin Name: WX Optimize Download Buttom
 * Version: 1.0.0
 * Description: Optimize buttom click load page second redirect
 * Author: Thanh Chinh
 * Author URI: http://webxanh.vn
 * Plugin URI: http://webxanh.vn
 * Text Domain: wx-odb
 */
if(!class_exists('WX_Optimize_Download_Buttom')){
    class WX_Optimize_Download_Buttom{
        public $version = '1.0.0';
        public $wx_odb_data;
        public function __construct()
        {
            if(maybe_unserialize(get_option( 'wx-redirect-second' ))){
                 $this->wx_odb_data = maybe_unserialize(get_option( 'wx-redirect-second' ));

            }
            add_action('admin_menu', array($this, 'wx_odb_admin_menu'));
            register_activation_hook(__FILE__, array($this, 'wx_odb_creat_page'));
            add_filter( 'page_template', array($this, 'wx_odb_custom_page') );
            add_action( 'admin_enqueue_scripts', array( $this, 'wx_odb_enqueue_scripts' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'wx_odb_enqueue_scripts' ) );
            add_shortcode('wx-url', array($this, 'wx_odb_shortcode'));
            add_action('init', array($this, 'wx_odb_custom_rewrite_rule'));
            add_action( 'admin_init', array($this,'wx_odb_setting_field') );

        }
        public function wx_odb_admin_menu(){
            add_options_page('WX Optimize Download Buttom', 'WX Download Buttom', 'manage_options', 'wx-optimize-download', array($this, 'wx_odb_admin_option'));
        }
        public function wx_odb_setting_field(){
            register_setting( 'wx-odb-settings-group', 'wx-redirect-second' );
        }
        public function wx_odb_admin_option(){



            ?>

            <form action="options.php" method="POST">
                <?php settings_fields( 'wx-odb-settings-group' ); ?>
                <?php do_settings_sections( 'wx-odb-settings-group' ); ?>
            <h2><?php _e('WX Redirect Second Settings','wx-odb');?></h2>
                <h4><?php _e('1. Display buttom fontend use shortcode ', 'wx-odb');?></h4>
                <p class="wx-shortcode-view">[wx-redirect url="https://example.com"]</p>
            <h4><?php _e('2.Second Number', 'wx-odb')?></h4>
            <input type="text" name="wx-redirect-second[second]" value="<?php  if(isset($this->wx_odb_data['second'])) echo $this->wx_odb_data['second'];?>" placeholder="0"><br/>
            <h4><?php _e('3.Insert code Ads', 'wx-odb')?></h4>
            <p><?php _e('Ads 1', 'wx-odb')?></p>
            <textarea rows="10" cols="100" name="wx-redirect-second[ads1]"><?php  if(isset($this->wx_odb_data['ads1'])) echo $this->wx_odb_data['ads1'];?></textarea>
            <p><?php _e('Ads 2', 'wx-odb')?></p>
            <textarea rows="10" cols="100" name="wx-redirect-second[ads2]"><?php  if(isset($this->wx_odb_data['ads2'])) echo $this->wx_odb_data['ads2'];?></textarea>
            <?php submit_button(); ?>
            </form>
        <?php }

        public function wx_odb_creat_page(){
            wp_insert_post( array(
                'post_title'    => wp_strip_all_tags( 'WX Optimize Download' ),
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page',
            ));
        }
        public function wx_odb_custom_page(){
            if(is_page('wx-optimize-download')){
                $page_template = dirname( __FILE__ ) . '/tp-optimize.php';
            }
             return $page_template;
        }

        public function wx_odb_enqueue_scripts(){
            wp_enqueue_style('wx-optimize-download', plugins_url('/style.css', __FILE__), array(), $this->version, 'all');
        }
        private function encode($string,$key) {
            $key = sha1($key);
            $strLen = strlen($string);
            $keyLen = strlen($key);
            for ($i = 0; $i < $strLen; $i++) {
                $ordStr = ord(substr($string,$i,1));
                if ($j == $keyLen) { $j = 0; }
                $ordKey = ord(substr($key,$j,1));
                $j++;
                $hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
            }
            return $hash;
        }

        public static function decode($string,$key) {
            $key = sha1($key);
            $strLen = strlen($string);
            $keyLen = strlen($key);
            for ($i = 0; $i < $strLen; $i+=2) {
                $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
                if ($j == $keyLen) { $j = 0; }
                $ordKey = ord(substr($key,$j,1));
                $j++;
                $hash .= chr($ordStr - $ordKey);
            }
            return $hash;
        }

        public function wx_odb_shortcode($atts, $content){
            $ref = '';
            $attr = shortcode_atts( array(
                'ref' => '#',
            ), $atts );
            return '<div class="wx-view-download"><a href="'.get_home_url().'/wx-optimize-download/'. $this->encode($attr['ref'], 'wx-odp-url').'">Download <span class="dashicons dashicons-download"></span></a></div>';
        }
        function wx_odb_custom_rewrite_rule() {

            add_rewrite_rule('^wx-optimize-download/(.+)', 'index.php?pagename=wx-optimize-download&url=$matches[1]', 'top');
        }
        



    }
    new WX_Optimize_Download_Buttom();
}