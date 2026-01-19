<?php
/**
 * ACF Utilities
 *
 * @package lunivers-theme
 */

namespace LUNIVERS_THEME\Inc\Classes;

use LUNIVERS_THEME\Inc\Traits\Singleton;

class ACF {
    use Singleton;
    
    private $preferred_save_path;

	protected function __construct() {
		// Vérifier si ACF est actif
		if ( ! function_exists( 'acf' ) ) {
			return;
		}

		// load class.
		$this->setup_hooks();
	}

	protected function setup_hooks() {
        // Ajoutez le paramètre "JSON Save Path" à tous les groupes de champs.
        // https://hwk.fr/blog/acf-ajouter-des-options-personnalisees-aux-groupes-de-champs
        add_action('acf/render_field_group_settings', [$this, 'add_json_save_path_setting']);

        // Appelez une action Early Bird (priorité 1) avant de sauvegarder le groupe de champs.
        add_action('acf/update_field_group', [$this, 'set_up_save_path'], 1, 1);

        add_filter('acf/settings/load_json', [$this, 'load_from_preferred_save_path']);

        add_action('acf/input/admin_footer', [ $this, 'flexible_content_layout_popup_class' ]);
        add_action('acf/input/admin_footer', [ $this, 'flexible_content_layout_no_popup']);

        // add_action('acf/input/admin_head', [$this, 'my_acf_admin_head']);


        add_filter('acf/settings/google_api_key', function () {
            return '';
        });

        add_filter('acf/prepare_field/type=flexible_content', [$this, 'disable_flexible_content_layouts']);

        add_filter('acf/load_field/name=social', [$this, 'acf_property_social_field']);

        // Enregistrer la page d'options de contact
        add_action('acf/init', [$this, 'register_contact_options_page']);

    }
    
    public function debug_log($log, $var_dump = false) {
       if (true === WP_DEBUG) {
           error_log('--------------------------------- /!\ NL Theme ACF /!\ --------------------------------- ');
           if (!$var_dump) {
               if ( is_array( $log ) || is_object( $log ) ) error_log( print_r( $log, true ) );
               else error_log( $log );
           } else {
               ob_start();
               var_dump($log);
               error_log(ob_get_contents());
               ob_end_clean();
           }
           error_log('--------------------------------- /!\ NL Theme ACF /!\ --------------------------------- ');
       }
    }

    /**
     * Recherche une clé dans une array
     */
    public function search_by_key($array, $key) {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key])) {
                $results[] = $array[$key];
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, $this->search_by_key($subarray, $key));
            }
        }

        return $results;
    }

    /**
     * Recherche d'une valeur d'un champ ACF via son nom (et non sa clé)
     */
    public function get_acf_post_value($slug = false, $array = false) {
        if (!$array) $array = $_POST['acf'];

        $key = acf_get_field($slug)['key'];
        $results = $this->search_by_key($array, $key);
        
        if (count($results) == 1) return reset($results);
        
        return $results;
    }

    public function get_acf_field_option($field){
        //https://support.advancedcustomfields.com/forums/topic/wpml-acf-5-0-9-and-options-page/
        if (class_exists('SitePress')){
            add_filter('acf/settings/current_language',function() {
                global $sitepress;
                return $sitepress->get_default_language();
            });
        }
    
        return get_field($field,'option', true);

        if (class_exists('SitePress')){
            // reset to original language
            add_filter('acf/settings/current_language',function() {
                return ICL_LANGUAGE_CODE;
            });
        }
    }

    public function flexible_content_layout_popup_class() {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($){

            try{
                // ACF Flexible Content: Ajouter une class à la modal de sélection de Layouts
                var flexible_content_position = acf.fields.flexible_content.position_popup;
                acf.fields.flexible_content.position_popup = function($popup, $bouton){
                    // Si le nom du champ flexible = 'mon_flexible'
                    if(this.$field.attr('data-name') == 'flexible_content'){
                        $popup.addClass('acf-fc-popup-modal');
                    }
                    // Continuer l'exécution normalement
                    return flexible_content_position.apply(this, arguments);
                }
            }catch(e){}

            $('a[data-name=add-layout]').click(function(){
                waitForEl('.acf-tooltip li', function() {

                    document.querySelectorAll('.acf-tooltip li a').forEach((item) => {
                        image = item.querySelector("img");
                        imageTP = item.getAttribute("data-layout");

                        if(!image){
                            $(item).append('<div class="imagePreview"><img src="<?php echo esc_url( LUNIVERS_THEME_URI . '/assets/images/flexible-content/' ); ?>' + imageTP + '.svg"></div>');
                        }

                    });
                    
                });
            })
            var waitForEl = function(selector, callback) {
                if (jQuery(selector).length) {
                    callback();
                } else {
                    setTimeout(function() {
                        waitForEl(selector, callback);
                    }, 100);
                }
            };
        });
        </script>
        
        <style>
        .imagePreview{
            position: relative;
            overflow: hidden;
            margin: 15px -15px -15px -15px;
            background:white;
        }
        .imagePreview:after {
            content: "";
            display: block;
            padding-bottom: 50%;
        }
        .imagePreview img {
            position: absolute !important;
            width: 100% !important;
            height: 100% !important;
            left: 0;
            object-fit: cover;
        }
        /* .acf-fc-popup .imagePreview { position:absolute; right:100%; top:0px; z-index:999999; border:1px solid #f2f2f2; box-shadow:0px 0px 3px #b6b6b6; background-color:#fff; padding:20px;}
        .acf-fc-popup .imagePreview img { width:300px; height:auto; display:block; }
        .acf-tooltip li:hover { background-color:#0074a9; } */
        .acf-fc-popup{
            width:80%;
            max-width:1200px;
            /*height:80%;*/
            max-height:90dvh;
            top:50% !important;
            left:50% !important;
            -webkit-transform:translate(-50%, -50%);
                -ms-transform:translate(-50%, -50%);
                    transform:translate(-50%, -50%);
            border-radius:0;
            position: fixed !important;
            background: none !important;
            padding:0;
            -webkit-box-sizing:border-box;
            box-sizing:border-box;
            display: flex;
            flex-direction: column;
        }
        .acf-fc-popup *{
            -webkit-box-sizing:border-box;
            box-sizing:border-box;
        }
        .acf-fc-popup::before,
        .acf-fc-popup::after{
            border-color:transparent !important;
        }
        .acf-fc-popup:before{
            top: 50% !important;
            right: 0 !important;
            bottom: 0 !important;
            left: 50% !important;
            position: fixed;
            background: rgba(0,0,0,0.8);
            height: 100dvh;
            width: 110vw;
            margin: 0 !important;
            padding: 0;
            content: "";
            transform:translate(-50%, -50%);
        }
        .acf-fc-popup ul{
            position:relative;
            height:100%;
            width:100%;
            overflow-y:auto;
            
            background:#f4f4f4;
            border:#ccc;
            padding:15px;
            -webkit-box-shadow:0 0 8px rgba(0, 0, 0, 0.5);
                    box-shadow:0 0 8px rgba(0, 0, 0, 0.5);
            padding-top: 93px !important;
            flex-direction:row;
            flex-wrap:wrap;
        }
        .acf-fc-popup ul:before{
            position: absolute;
            top: 31px;
            content: "Ajouter une section";
            font-size: 23px;
            font-weight: 400;
            color: #333;
            left: 30px;
        }
        .acf-fc-popup ul:after{
            position: absolute;
            top: 62px;
            content: "Selectionnez une section à ajouter sur la page.";
            font-size: 13px;
            color: #666;
            left: 30px;
            font-style: italic;
        }
        .acf-fc-popup ul li{
            width: 33.33333%;
            padding: 1%;
            float: left;
            height:auto;
        }
        @media only screen and (max-width:960px){
            .acf-fc-popup ul li{
                width: 50%;
            }
        }
        @media only screen and (max-width:782px){
            .acf-fc-popup{
                width:95%;
                height:88%;
            }
            .acf-fc-popup ul li{
                width: 100%;
            }
        }
        .acf-fc-popup ul li a{
            border:1px solid #ddd;
            background:#fff;
            padding:15px 15px 15px 15px;
            color:#333;
        }
        .acf-fc-popup ul li a:hover{
            background: #092938;
            border:1px solid #092938;
        }
        /*
        .acf-fc-popup ul li a i{
            font-size: 22px;
            vertical-align: text-top;
            margin-right: 10px;
        }
        
        .acf-fc-popup ul li a[data-layout]:before{
            position:absolute;
            display: inline-block;
            font: normal normal normal 20px/1 FontAwesome;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            vertical-align: text-top;
            margin-left:-30px;
            margin-top:-1px;
            content: "\f105";
        }
        */
        </style>
        <?php
    }

    public function flexible_content_layout_no_popup() {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($){
            try{
                // ACF Flexible Content: Supprimer la popup si il n'y a qu'un seul Layout
                var flexible_content_open = acf.fields.flexible_content._open;
                acf.fields.flexible_content._open = function(e){
                    var $popup = $(this.$el.children('.tmpl-popup').html());
                    // On compte le nombre de layouts
                    if($popup.find('a').length == 1){
                        acf.fields.flexible_content.add($popup.find('a').attr('data-layout'));
                        return false;
                    }
                    // Si plus d'un layout, continuer l'exécution normalement
                    return flexible_content_open.apply(this, arguments);
                }
            }catch(e){}
        });
        </script>
        <?php
    }

    public function add_json_save_path_setting($field_group) {
        $theme = wp_get_theme();
        $acf_render_field_wrap_choices = [
            'parent'  => $theme->template
        ];
        if($theme->template !== $theme->stylesheet){
            $acf_render_field_wrap_choices["child"] = $theme->stylesheet;
        }

        // Créez notre champ de paramétrage personnalisé avec les options spécifiées.
        acf_render_field_wrap([
            'label'        => 'JSON Save Path',
            'instructions' => 'Déterminer si on sauvegarde ce groupe dans le parent (global) ou uniquement au theme courant (pas global)',
            'type'         => 'button_group',
            'name'         => 'json_save_path',
            'prefix'       => 'acf_field_group',
            'prepend'      => '/',
            'placeholder'  => $theme->template,
            'value'        => (isset($field_group['json_save_path'])) ? $field_group['json_save_path'] : '',
            'choices'           => $acf_render_field_wrap_choices,
            'allow_null'        => false, // true | false
            'layout'            => 'horizontal' // vertical | horizontal
        ]);
    }

    public function set_up_save_path($group) {
        // Obtenez le chemin de sauvegarde préféré, s'il est défini.
        $preferred_save_path = $group['json_save_path'] ?? null;

        // S'il n'est pas défini (ou défini sur une chaîne vide), ne faites rien.
        if (!$preferred_save_path) {
            return $group;
        }

        // Theme parent (global)
        if($preferred_save_path == "parent"){
            $save_path = get_template_directory();
        }
        // Theme actuel (enfant)
        if($preferred_save_path == "child"){
            $save_path = get_theme_file_path();
        }

        // Mettez de côté le chemin préféré et ajoutez une action de remplacement.
        $this->preferred_save_path = $save_path . "/acf-fields";

        add_action('acf/settings/save_json', [$this, 'save_to_preferred_save_path'], 9999);

        // Renvoyez le groupe pour la mise à jour comme d'habitude.
        return $group;
    }

    public function save_to_preferred_save_path($path) {
        // Assurez-vous que ce groupe de champs est enregistré dans le chemin d'enregistrement préféré.

        $path = $this->preferred_save_path;
        
        if ( ! file_exists( $path ) ) {
            wp_mkdir_p( $path );
        }

        return $path;
    }

    public function load_from_preferred_save_path($path) {
        $paths = $path;
        // supprimer le chemin d'origine (facultatif)
        unset($paths[0]);
        // append path

        /**
         * Priorité au thème enfant en cas d'écrasement du json
         */
        $paths[] = get_template_directory() . '/acf-fields'; // Parent Theme
        $paths[] = get_theme_file_path() . '/acf-fields'; // Child Theme

        // return
        return $paths;
    }

    function acf_property_id_name_field($field) {
        // Note: Cette fonction nécessite la fonction getJsonDatas() qui doit être définie ailleurs
        // ou peut être adaptée selon vos besoins spécifiques
        $hited_fields = array(
            "property_activity",
            "property_service",
        );
        if(in_array($field['name'], $hited_fields)){
            
            // Si la fonction getJsonDatas existe, l'utiliser
            if (function_exists('getJsonDatas')) {
                $arrays = getJsonDatas($field['name']);
                
                if($arrays === false)
                    return $field;
        
                $categories = [];
        
                if($arrays){
                    foreach($arrays as $array){
                        $categories[$array['id']] = $array["name"];
                    }
                }
        
                $field['choices'] = $categories;
            }
        }
        return $field;
    }

    
    public function disable_flexible_content_layouts($field){
        
        // Bail early if no layouts
        if(!isset($field['layouts']) || empty($field['layouts']))
            return $field;
        
        foreach($field['layouts'] as $layout_key => $layout){
            
            // Disable Réalisations
            if(
                $layout['name'] === 'works_categories' && 
                get_theme_mod('cpt_work_activation', false) == false
            ){
                
                unset($field['layouts'][$layout_key]);
                
            }
            
        }
        
        // return
        return $field;
        
    }

    public function acf_property_social_field( $field ){
 
        // reset choices
        $field['choices'] = array();
         
        $socials = array(
			''   => esc_html__( '', 'lunivers-theme' ),
			'facebook'   => esc_html__( 'Facebook', 'lunivers-theme' ),
			'twitter'    => esc_html__( 'Twitter', 'lunivers-theme' ),
			'instagram'  => esc_html__( 'Instagram', 'lunivers-theme' ),
			// 'googleplus' => esc_html__( 'Google+', 'lunivers-theme' ),
			// 'pinterest'  => esc_html__( 'Pinterest', 'lunivers-theme' ),
			'linkedin'   => esc_html__( 'LinkedIn', 'lunivers-theme' ),
			// 'rss'        => esc_html__( 'RSS', 'lunivers-theme' ),
			'envelope'   => esc_html__( 'Email', 'lunivers-theme' ),
			// 'tumblr'     => esc_html__( 'Tumblr', 'lunivers-theme' ),
			'youtube'    => esc_html__( 'Youtube', 'lunivers-theme' ),
			'vimeo'      => esc_html__( 'Vimeo', 'lunivers-theme' ),
			'behance'    => esc_html__( 'Behance', 'lunivers-theme' ),
			// 'dribbble'   => esc_html__( 'Dribbble', 'lunivers-theme' ),
			// 'flickr'     => esc_html__( 'Flickr', 'lunivers-theme' ),
			// 'github'     => esc_html__( 'GitHub', 'lunivers-theme' ),
			// 'skype'      => esc_html__( 'Skype', 'lunivers-theme' ),
			'whatsapp'   => esc_html__( 'WhatsApp', 'lunivers-theme' ),
			'telegram'   => esc_html__( 'Telegram', 'lunivers-theme' ),
			// 'snapchat'   => esc_html__( 'Snapchat', 'lunivers-theme' ),
			// 'wechat'     => esc_html__( 'WeChat', 'lunivers-theme' ),
			// 'weibo'      => esc_html__( 'Weibo', 'lunivers-theme' ),
			// 'foursquare' => esc_html__( 'Foursquare', 'lunivers-theme' ),
			// 'soundcloud' => esc_html__( 'Soundcloud', 'lunivers-theme' ),
			// 'vk'         => esc_html__( 'VK', 'lunivers-theme' ),
			// 'tiktok'     => esc_html__( 'TikTok', 'lunivers-theme' ),
			'phone'     => esc_html__( 'Phone', 'lunivers-theme' ),
		);
         
        foreach ($socials as $key => $role) :
            $field['choices'][ $key ] = $role;
        endforeach;
 
        return $field;
    }

    /**
     * Enregistrer la page d'options de contact
     */
    public function register_contact_options_page() {
        if ( function_exists( 'acf_add_options_page' ) ) {
            acf_add_options_page([
                'page_title'  => __( 'Options de contact', 'lunivers-theme' ),
                'menu_title'  => __( 'Contact', 'lunivers-theme' ),
                'menu_slug'   => 'contact-options',
                'capability'  => 'edit_posts',
                'icon_url'    => 'dashicons-email-alt',
                'position'    => 30,
            ]);
        }
    }

    /* Hover Module Effect */
    public function my_acf_admin_head() {
        $siteURL = get_site_url();
        ?>
        <style>
            .acf-fc-popup .imagePreview { position:absolute; right:100%; top:0px; z-index:999999; border:1px solid #f2f2f2; box-shadow:0px 0px 3px #b6b6b6; background-color:#fff; padding:20px;}
            .acf-fc-popup .imagePreview img { width:300px; height:auto; display:block; }
            /* .acf-tooltip li:hover { background-color:#0074a9; } */
        </style>
        <script>
        jQuery(document).ready(function($) {
            $('a[data-name=add-layout]').click(function(){
                waitForEl('.acf-tooltip li', function() {
                    $('.acf-tooltip li a').hover(function(){
                        imageTP = $(this).attr('data-layout');
                        $('.acf-tooltip').append('<div class="imagePreview"><img src="<?php echo esc_url( LUNIVERS_THEME_URI . '/assets/images/flexible-content/' ); ?>' + imageTP + '.jpg"></div>');
                    }, function(){
                        $('.imagePreview').remove();
                    });
                    });
                })
                var waitForEl = function(selector, callback) {
                    if (jQuery(selector).length) {
                        callback();
                    } else {
                        setTimeout(function() {
                        waitForEl(selector, callback);
                    }, 100);
                }
            };
        })
        </script>
        <?php
    }

}