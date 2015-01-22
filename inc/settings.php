<?php


class LCS_settingPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;   

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Slider"
        add_submenu_page(
            'edit.php?post_type=slider-image', 
            'settings', 
            'Settings', 
            'manage_options', 
            'slider_settings', array( $this, 'create_slider_settings_page' ));
    }

    /**
     * Options page callback
     */
    public function create_slider_settings_page()
    {
        // Set class property
        $this->options = get_option( 'my_option_name' );
        ?>
        <div class="wrap">
            <?php echo screen_icon(); ?>
            <h2>LC Slider Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );   
                do_settings_sections( 'slider_settings' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'my_option_group', // Option group
            'my_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            '', // Title
            array( $this, 'print_section_info' ), // Callback
            'slider_settings' // Page
        );  

        add_settings_field(
            'mode', // ID
            'Mode', // Title 
            array( $this, 'mode_callback' ), // Callback
            'slider_settings', // Page
            'setting_section_id' // Section           
        );  
        
        add_settings_field(
            'speed', // ID
            'Speed', // Title 
            array( $this, 'speed_callback' ), // Callback
            'slider_settings', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'controls', 
            'Controls', 
            array( $this, 'controls_callback' ), 
            'slider_settings', 
            'setting_section_id'
        );    
        add_settings_field(
            'pager', 
            'Pager', 
            array( $this, 'pager_callback' ), 
            'slider_settings', 
            'setting_section_id'
        );  
        add_settings_field(
            'hoverPause', 
            'Hover Pause', 
            array( $this, 'hoverPause_callback' ), 
            'slider_settings', 
            'setting_section_id'
        );    
        
        add_settings_field(
            'startslide', // ID
            'Start Slide', // Title 
            array( $this, 'startslide_callback' ), // Callback
            'slider_settings', // Page
            'setting_section_id' // Section           
        );    
        
        
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['mode'] ) )
            $new_input['mode'] = sanitize_text_field( $input['mode'] );

        if( isset( $input['speed'] ) )
            $new_input['speed'] = sanitize_text_field( $input['speed'] );
            
        if( isset( $input['controls'] ) )
            $new_input['controls'] = sanitize_text_field( $input['controls'] );
        else
            $new_input['controls'] = 'false';   
        
        if( isset( $input['pager'] ) )
            $new_input['pager'] = sanitize_text_field( $input['pager'] );
        else
            $new_input['pager'] = 'false';  
            
        if( isset( $input['hoverPause'] ) )
            $new_input['hoverPause'] = sanitize_text_field( $input['hoverPause'] );
        else
            $new_input['hoverPause'] = 'false';
            
       if( isset( $input['startslide'] ) )
            $new_input['startslide'] = sanitize_text_field( $input['startslide'] );       

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function mode_callback()
    {
     
        $html = '<select id="mode" name="my_option_name[mode]">';
            $html .= '<option value="slide"' . selected( $this->options['mode'], 'slide', false) . '>Slide</option>';
            $html .= '<option value="fade"' . selected( $this->options['mode'], 'fade', false) . '>Fade</option>';           
        $html .= '</select>';
        $html .= '<p class="description">Type of transition between slides</p>';
         
        echo $html;
    }
    
    /** 
     * Get the settings option array and print one of its values
     */
    public function speed_callback()
    {
        
        $speed = (isset($this->options['speed']) and (!empty($this->options['speed']))) ? esc_attr( $this->options['speed']) : "500";
        $html = '<input type="text" id="speed" name="my_option_name[speed]" value="'.$speed.'"  />';
        $html .= '<p class="description">Slide transition duration (in ms) (default:500)</p>';
         
        echo $html;
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function controls_callback()
    {
        $html = '<input type="checkbox" id="controls" name="my_option_name[controls]" value="true" '.checked( $this->options['controls'], 'true', false).'   />';
        $html .= '<p class="description">If true, "Next" / "Prev" controls will be added</p>';
        
        echo $html;
    }
    
     /** 
     * Get the settings option array and print one of its values
     */
    public function pager_callback()
    {
        $html = '<input type="checkbox" id="pager" name="my_option_name[pager]" value="true" '.checked( $this->options['pager'], 'true', false).'   />';
        $html .= '<p class="description">If true, a pager will be added</p>';
        
        echo $html;
    }
    
     public function hoverPause_callback()
    {
        $html = '<input type="checkbox" id="hoverPause" name="my_option_name[hoverPause]" value="true" '.checked( $this->options['hoverPause'], 'true', false).'   />';
        $html .= '<p class="description">Start Slider on a Random Slide</p>';
        
        echo $html;
    }
     public function auto_callback($arg)
    {
        $auto = isset($this->options['auto']) ? $this->options['auto'] : "true";
        $html = '<input type="checkbox" id="auto" name="my_option_name[auto]" value="true" '.checked($auto , 'true', false).'   />';
        $html .= '<p class="description">Slides will automatically transition</p>';
        
        echo $html;
    }

    public function startslide_callback()
    {
        
        $startslide = (isset($this->options['startslide']) and (!empty($this->options['startslide']))) ? esc_attr( $this->options['startslide']) : "1";
        $html = '<input type="text" id="startslide" name="my_option_name[startslide]" value="'.$startslide.'"  />';
        $html .= '<p class="description">Slide transition duration</p>';
         
        echo $html;
    }

   

}

if( is_admin() )
    $my_settings_page = new LCS_settingPage();