<?php
include_once('PaintingHtml_LifeCycle.php');
include_once('PaintingHtml_OwlCarousel.php');

class PaintingHtml_Plugin extends PaintingHtml_LifeCycle {
    public $carousel;
    function __construct() 
    {
        $this->carousel = new PaintingHtml_OwlCarousel();
    }
    /**
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        return array(
            //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
            //'ATextInput' => array(__('Enter in some text', 'my-awesome-plugin')),
            //'AmAwesome' => array(__('I like this awesome plugin', 'my-awesome-plugin'), 'false', 'true'),
            //'CanDoSomething' => array(__('Which user role can do something', 'my-awesome-plugin'),
            //                            'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber', 'Anyone')
        );
    }

//    protected function getOptionValueI18nString($optionValue) {
//        $i18nValue = parent::getOptionValueI18nString($optionValue);
//        return $i18nValue;
//    }

    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }

    public function getPluginDisplayName() {
        return 'Painting HTML';
    }

    protected function getMainPluginFileName() {
        return 'painting-html.php';
    }

    /**
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
        //            `id` INTEGER NOT NULL");
    }

    /**
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }

    /**
     * Perform actions when upgrading from version X to version Y
     * @return void
     */
    public function upgrade() {
    }

    public function addActionsAndFilters() {

        // Add options administration page
        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));

        // Example adding a script & style just for the options administration page
        //        if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
        //            wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));
        //            wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        }


        // Add Actions & Filters
        add_action('init', array(&$this, 'painting_html_init'), 99); 
        add_action('save_post', array(&$this, 'save_gallery_mb'), 99, 3);


        // Adding scripts & styles to all pages
        // Examples:
        //        wp_enqueue_script('jquery');
        //        wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));

        add_action( 'admin_enqueue_scripts', function($hook_suffix ) {
            if(in_array($hook_suffix, ['post.php', 'post-new.php']))
            {
                wp_enqueue_script('jquery');
                wp_enqueue_media();
                wp_enqueue_style('p_gallery', site_url('/wp-content/plugins/painting-html/assets/css/painting_gallery_admin.css'), [], false, 'all');
                wp_enqueue_script('p_gallery', site_url('/wp-content/plugins/painting-html/assets/js/painting_gallery_admin.js') , ['jquery'], false, true);
            }
        });

        add_action( 'wp_enqueue_scripts', function(){
            //wp_enqueue_style('html', site_url('/wp-content/plugins/painting-html/assets/bootstrap-5.0.2-dist/css/bootstrap.min.css'), [], false, 'all');
            wp_enqueue_style('font-awesome', site_url('/wp-content/plugins/painting-html/assets/fontawesome-free-5.15.4-web/css/all.min.css'), [], false, 'all');
            //wp_enqueue_style('vue-agile', site_url('/wp-content/plugins/painting-html/assets/css/VueAgile.css'), [], false, 'all');
            wp_enqueue_style('owl_carousel', site_url('/wp-content/plugins/painting-html/assets/css/owl.carousel.min.css'), [], false, 'all');
            wp_enqueue_style('owl_carousel_theme', site_url('/wp-content/plugins/painting-html/assets/css/owl.theme.default.min.css'), [], false, 'all');
            wp_enqueue_style('slick', site_url('/wp-content/plugins/painting-html/assets/accessible-slick-1.0.1/slick/slick.min.css'), [], false, 'all');
            wp_enqueue_style('slick_theme', site_url('/wp-content/plugins/painting-html/assets/accessible-slick-1.0.1/slick/accessible-slick-theme.min.css'), [], false, 'all');

            //wp_enqueue_script('slim', site_url('/wp-content/plugins/painting-html/assets/jquery.slim.min.js_3.5.1/cdnjs/jquery.slim.min.js') , ['jquery'], false, true);
            wp_enqueue_script('owl_carousel', site_url('/wp-content/plugins/painting-html/assets/js/owl.carousel.min.js') , ['jquery'], false, true);
            //wp_enqueue_script('vue', site_url('/wp-content/plugins/painting-html/assets/js/vue.min.js') , [], false, true);
            //wp_enqueue_script('vue-agile', site_url('/wp-content/plugins/painting-html/assets/js/VueAgile.umd.min.js') , [], false, true);
            wp_enqueue_script('slick', site_url('/wp-content/plugins/painting-html/assets/accessible-slick-1.0.1/slick/slick.min.js') , [], false, true);

            wp_enqueue_style('html', site_url('/wp-content/plugins/painting-html/assets/css/style.css'), [], false, 'all');

            wp_enqueue_script('p_gallery', site_url('/wp-content/plugins/painting-html/assets/js/painting_gallery.js') , ['jquery'], false, true);
            
            if(is_front_page()) {
                //wp_enqueue_style('p_home', site_url('/wp-content/plugins/painting-html/assets/css/painting_home.css'), [], false, 'all');
            }
            
            //wp_enqueue_style('p_html', site_url('/wp-content/plugins/painting-html/assets/css/painting_html.css'), [], false, 'all');
        });

        // Register short codes
        add_shortcode('pg', function($atts){
            return $this->carousel->painting_gallery($atts);
        });
        add_shortcode('pgs', function($atts){
            return $this->carousel->painting_gallery_single($atts);
        });

        // Register AJAX hooks

    }

    public function painting_html_init()
    {
        return $this->carousel->register_post_types();
    }
    public function save_gallery_mb($post_id, $post, $update)
    {
        return $this->carousel->save_gallery_mb($post_id, $post, $update);
    }
}