<?php
/**
* ID: gfonts
* Name: Google Fonts in local
* Description: Scegli e carica in locale i font di Google
* Icon: data:image/svg+xml,%3Csvg _ngcontent-ng-c1476994768='' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 192 192'%3E%3Cpath _ngcontent-ng-c1476994768='' fill='none' d='M0 0h192v192H0z'%3E%3C/path%3E%3Cpath _ngcontent-ng-c1476994768='' fill='%23FBBC04' d='M95.33 36L92 32 8 160h58l26.07-39.73 3.26-7.06z'%3E%3C/path%3E%3Cpath _ngcontent-ng-c1476994768='' fill='%231A73E8' d='M92 32h52v128H92z'%3E%3C/path%3E%3Ccircle _ngcontent-ng-c1476994768='' fill='%23EA4335' cx='36' cy='56' r='24'%3E%3C/circle%3E%3Cpath _ngcontent-ng-c1476994768='' fill='%230D652D' d='M148 124l-4 36c-19.88 0-36-16.12-36-36s16.12-36 36-36l4 36z'%3E%3C/path%3E%3Cpath _ngcontent-ng-c1476994768='' fill='%23174EA6' d='M116 60c0-15.46 12.54-28 28-28l5 28-5 28c-15.46 0-28-12.54-28-28z'%3E%3C/path%3E%3Cpath _ngcontent-ng-c1476994768='' fill='%231A73E8' d='M144 32c15.46 0 28 12.54 28 28s-12.54 28-28 28'%3E%3C/path%3E%3Cpath _ngcontent-ng-c1476994768='' fill='%2334A853' d='M144 88c19.88 0 36 16.12 36 36s-16.12 36-36 36'%3E%3C/path%3E%3C/svg%3E
* Version: 1.0
* 
*/
class gfonts {
    private $bc_gfonts_options;
	public function __construct() {	
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
        global $pagenow;
        if($pagenow=='admin.php' && isset($_GET['page']) && $_GET['page']=='gfonts'){
            add_action( 'admin_enqueue_scripts', array( $this, 'load_enqueue') );
        }
        add_action( 'wp_enqueue_scripts', array( $this, 'load_font') );

    }
    public function add_plugin_page(){
        add_submenu_page(
            'bweb-component',
			'Google Fonts', // page_title
			'Google Fonts', // menu_title
			'manage_options', // capability
			'gfonts', // menu_slug
			array( $this, 'create_admin_page' ) // function
		);
    }
    public function create_admin_page(){
        $this->bc_gfonts_options = get_option( 'bc_gfonts' ); 
        
        ?>

		<div class="wrap">
			<h2 class="wp-heading-inline">Google Fonts</h2>
			<p></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
                <div>
                    <input type="text" id="search_font" class="regular-text" placeholder="Cerca font">
                    <button id="btn_search_font" class="button-secondary">Cancella</button>
                    <select id="categfont">
                        <option value="all" selected>Tutto</option>
                        <option value="serif">Serif</option>
                        <option value="sans-serif">Sans Serif</option>
                        <option value="display">Display</option>
                        <option value="handwriting">Handwriting</option>
                        <option value="monospace">Monospace</option>
                    </select>
                    Testo anteprima font: <input type="text" id="text_preview_font" class="regular-text" value="Whereas recognition of the INHERENT DIGNITY">
                    <a href="#" class="btn_chiaroscuro"><span class="dashicons dashicons-editor-textcolor"></span></a>
                </div>
                <div id="main_fonts">
                    <div id="cont_font">
                        <div id="response_font"></div>
                    </div>
                    <div id="cont_action">
                        <div id="response_action">
                        <?php 
                        $txtclassfont = "";
                        //print_r($this->bc_gfonts_options);
                        if(isset($this->bc_gfonts_options) && is_array($this->bc_gfonts_options)):
                            foreach($this->bc_gfonts_options as $name => $v ){
                                //$v['bc_gfonts_weight']
                                ?>
                                <div class="list-group-item <?php echo str_replace(" ","_",$name); ?>" data-font="<?php echo $name; ?>" data-weight="<?php echo $v['bc_gfonts_weight']; ?>">
                                    <div>
                                    <strong><?php echo $name; ?></strong>
                                        <div class="preview_font" style="font-family: <?php echo $name; ?>"></div>
                                    <br>
                                        <div>
                                            <input type="checkbox" checked name="bc_gfonts[<?php echo $name; ?>]" class="chk_font" value="<?php echo $name; ?>">
                                            <input type="hidden" name="bc_gfonts[<?php echo $name; ?>][bc_gfonts_weight]" value="<?php echo $v['bc_gfonts_weight']; ?>">
                                            
                                            <?php foreach(explode(',',$v['bc_gfonts_weight']) as $key) { ?>
                                                <label class="lbl_weight"><input <?php if (is_array($v['chk_weight']) && in_array($key, $v['chk_weight'])) echo 'checked'; ?> type="checkbox" name="bc_gfonts[<?php echo $name; ?>][chk_weight][]" data-font="<?php echo $name; ?>" data-qryfont="<?php echo str_replace(" ","+",$name); ?>" class="chk_weight" value="<?php echo $key; ?>"><span><?php echo str_replace("regular","400",$key); ?></span></label>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="#" class="remove_font button-secondary delete"><span class="dashicons dashicons-trash" style="vertical-align: text-top;"></span> Rimuovi</a>
                                    </div>
                                </div>


                                <?php
                                $txtclassfont .= ".font-" . str_replace(" ","-",$name) . "{<br>";
                                $txtclassfont .= "&nbsp&nbsp&nbsp&nbsp&nbsp&nbspfont-family: '".$name."';<br>";
                                $txtclassfont .= "}<br>";
                            }
                        endif;
                        ?>
                        </div>
                        <?php if($txtclassfont != ""): ?>
                            <br><br><br>
                        <strong>CSS</strong>
                        <div id="cont_fontclass">
                            <?php
                                echo $txtclassfont;
                            ?>
                        </div>
                        <?php endif ?>
                    </div>
                </div>
				<?php
					settings_fields( 'option_group' );
					submit_button();
				?>
                
			</form>
		</div>

	<?php
    }

    public function page_init(){
        register_setting(
			'option_group', // option_group
			'bc_gfonts', // option_name
            array( $this, '_sanitize' ) // sanitize_callback
		);
    }
    
    public function _sanitize($input){
        $folder_gfont = WP_CONTENT_DIR  . '/themes/' . get_option( 'stylesheet' ) . '/gfonts/';
        if (!is_dir($folder_gfont)) {
            if (!mkdir($folder_gfont)) { 
                exit("Failed to create $folder_gfont");
            };
          //echo "$to created\r\n";
        }
        $family = "";
        foreach($input as $name => $v ){
            $family .= "&family=".str_replace(" ","+",$name);
        }
        if($family != ""){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/webfonts/v1/webfonts?capability=WOFF2&key='.get_option( 'bc_key_gfont' ).$family);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json"
            ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    
            $fonts_list = json_decode(curl_exec($ch), true);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $txtfont = "";
            $txtclassfont = "";
            foreach($fonts_list['items'] as $values) {
                foreach($input[$values['family']]['chk_weight'] as $weight) {
                    if (!str_contains($weight, 'italic')) {
                        if (!file_put_contents($folder_gfont . $values['family'] . "_" . $weight . '.woff2', file_get_contents($values['files'][$weight]))){
                            exit("Error File downloaded");
                        }else{
                                $txtfont .= "@font-face {" . PHP_EOL;
                                $txtfont .= "   font-display: swap;" . PHP_EOL;
                                $txtfont .= "   font-family: '".$values['family']."';" . PHP_EOL;
                                $txtfont .= "   font-style: normal;" . PHP_EOL;
                                $txtfont .= "   font-weight: ".str_replace("regular","400",$weight).";" . PHP_EOL;
                                $txtfont .= "   src: url('".$values['family'] . "_" . $weight . ".woff2') format('woff2');" . PHP_EOL;
                                $txtfont .= "}" . PHP_EOL;
                        }
                    }
                }
                $txtclassfont .= ".font-" . str_replace(" ","-",$values['family']) . "{" . PHP_EOL;
                $txtclassfont .= "  font-family: '".$values['family']."';" . PHP_EOL;
                $txtclassfont .= "}" . PHP_EOL;
            }

            $filecss = fopen($folder_gfont."fonts.css", "w") or die("Unable to open file!");
            fwrite($filecss, $txtfont.$txtclassfont);
            fclose($filecss);
        }

		return $input;
    }
    public function load_enqueue(){
        wp_enqueue_script( 'settings_bc_gfont_js', plugin_dir_url( DIR_COMPONENT .  '/bweb_component_functions/' ).'gfonts/assets/script.js', ['jquery', 'jquery-ui-autocomplete'], null, true );
		wp_localize_script('settings_bc_gfont_js', 'variable', array('key_gfont'=>get_option( 'bc_key_gfont' )));
        wp_enqueue_style( 'settings_bc_gfont-css', plugin_dir_url( DIR_COMPONENT .  '/bweb_component_functions/' ).'gfonts/assets/style.css');
    }
    
    public function load_font(){
        $file_gfont = WP_CONTENT_DIR  . '/themes/' . get_option( 'stylesheet' ) . '/gfonts/fonts.css';
        if(file_exists($file_gfont)){
            wp_enqueue_style( 'bc_gfont-css', get_stylesheet_directory_uri().'/gfonts/fonts.css');
        }
    }

}

new gfonts();