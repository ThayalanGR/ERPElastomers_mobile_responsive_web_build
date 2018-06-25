<?php
// myDBR defaults
// DO NOT CHANGE THIS FILE
// If you want to change the default values, override them in /user/defaults.php as this file will be overridden in updates
//

$mydbr_defaults = array (
  'chart' => array (
    'sizeX' => 600,
    'sizeY' => 350,
    'size_mobile' => array( 'x' => 430, 'y' => 270),
    'title_color' => 0x00000000, // ImageChart only, ARGB Color 
    'title_font' => 'arialbd.ttf', // ImageChart
    'font' => null, // Set this to override chart font
    'font_size' => null, // Set this to override chart font sizes
    'title_font_size' => 11, // ImageChart 
    'base_font_size' => 10, // Flash Chart 
    'x_axis_font_color' => 0x00000000, // ImageChart only, ARGB Color 
    'x2_axis_font_color' => 0x00000000, // ImageChart only, ARGB Color 
    'y_axis_font_color' => 0x00000000, // ImageChart only, ARGB Color 
    'y2_axis_font_color' => 0x00000000, // ImageChart only, ARGB Color 
    'z_axis_font_color' => 0x00000000, // ImageChart only, ARGB Color 
    'x_axis_color' => 0xB2C5D8, // ImageChart only, ARGB Color 
    'x2_axis_color' => 0xB2C5D8, // ImageChart only, ARGB Color 
    'y_axis_color' => 0xFFFF0000, // ImageChart only, ARGB Color 
    'z_axis_color' => 0xB2C5D8, // ImageChart only, ARGB Color 
    'axis_font' => 'arial.ttf', // ImageChart 
    'axis_font_size' => 9, // ImageChart 
    'axis_label_font' => 'arialbd.ttf', // ImageChart 
    'axis_label_font_size' => 9, // ImageChart 
    'axis_label_font_color' => 0x00000000, // ImageChart 
    'label_color' => 0x00000000, // ImageChart only, ARGB Color 
    'label_color.data' => 0x00000000, // ImageChart only, ARGB Color 
    'label_font' => 'arial.ttf', // ImageChart 
    'label_font_size' => 9, // ImageChart 
    'legend_default_position' => 'right',
    'legend_default_style' => array(
      'background_color' => 0xFFFFFFFF,
      'edge_color' => NULL,
      'rounded_border' => array(
        'TL' => 5,
        'TR' => 5,
        'BL' => 5,
        'BR' => 5
      )
    ),
    'chart_extra_width_with_legend_on_side' => 100,
    'legend_color' => 0x00000000, // ImageChart only, ARGB Color 
    'legend_font' => 'arial.ttf', // ImageChart 
    'legend_font_size' => 9, // ImageChart 
    'legend_font_color' => 9, // ImageChart 
    'background_color' => 0xFFFFFFFF, // 0x00FFFFFF,
    'edge_color' => 0xFF000000,
    'plot_edge_color' => 0xFFFF0000,
    'raised_effect' => 0,
    'padding_top' => 0,
    'grid_color_horizontal' => 0xD7D7D7,
    'grid_color_vertical' => 0xFF000000,
    'colors' => array(
      0x3F90F4,
      0x5AB231,
      0xF7D921,
      0xF43011,
      0xA05AE5,
      0xF984A1,
      0xDDE10A,
      0xAEAEAE,
      0xAFD8F8,
      0x006F00,
      0x0099FF,
      0xFF66CC,
      0x669966,
      0x7C7CB4,
      0xFF9933,
      0x9900FF,
      0x99FFCC,
      0xCCCCFF,
      0x669900,
      0x1941A5,
    ),
    'bargap' => array('col' => 0.2, 'mscol' => 0.5 ),
    'subbargap' => -1.69e-100, /* TouchBar */
    'alternate_color' => array('0xFFFFFF', '0xFFFFFF', '0xc0c0c0', '0xc0c0c0'),
    'polar_radar'=> array( 'xSize' => 500, 'grid_color' => array(0x80000000, 1, 0xc0000000, 1) ),
    'line' => array(
      'x_axis_tick_middle' => true,
      'x_axis_margin' => 20,
    ),
    'meter' => array(
      'size' => 240,
      'inner' => 0xF4F4F4,
      'outer' => 0x736F6E,
      'outer2' => 0xD5D5D5,
      'green' => 0x6666ff66,
      'yellow' => 0x66ffff33,
      'red' => 0x66ff6666,
      'title' => array( 'font' => 'arialbd.ttf'),
      'text' => array( 'font' => 'ariali.ttf', 'size'=>8, 'color' => 0x000000),
      'label' => array( 'font' => 'ariali.ttf', 'size'=>8, 'color' => 0x000000),
      'pointer' => array( 'color'=> 0xB82313),
      'color' => array(
        'type' => 'gradient',
        'gradient' => array(
          array(0, 0x6666ff),
          array(25, 0x00bbbb),
          array(50, 0x00ff00),
          array(75, 0xffff00),
          array(100, 0xff0000),
        ),
        'step' => array(
          array(0, null),
          array(33, 0x6666ff66),
          array(66, 0x66ffff33),
          array(100, 0x66ff6666),
        ),
      ),
      'zones' => array(
        array(0, 33, 0x4000FF00),
        array(33, 66, 0x40FFFF00),
        array(66, 100, 0x40FF0000),
      ),
      'theme' => array(
        'black' => array(
          'background' => 0x000000,
          'label-color' => 0xffffff,
          'border' => 0x888888,
          'ring1' => 0x707070, // Meter chart ring second to outer
          'ring2' => 0xA0A0A0, // Meter chart outer ring
          'ring3' => 0x222222, // inner for second ring
          'title-color' => 0x000000,
          'text-color' => 0xffffff,
          'tick-color' => 0xffffff,
          'pointer-color' => 0xB82313,
          'linearmeter-pointer-color' => 0xAAAAAA,
          'rectangular-border' => 0x9fcccccc,
          'rectangular-bottom' => 0x9f777777,
          'glare' => array('meter', 'semicircle', 'rectangularmeter')
        ),
        'white' => array(
          'background' => 0xffffff,
          'label-color' => 0x444444,
          'border' => 0xffffff,
          'ring1' => 0xD5D5D5,
          'ring2' => 0xA0A0A0, //0x736F6E
          'ring3' => 0xCCCCCC, // inner for second ring
          'title-color' => 0x000000,
          'text-color' => 0x000000,
          'tick-color' => 0x000000,
          'pointer-color' => 0xB82313,
          'linearmeter-pointer-color' => 0x808080,
          'rectangular-border' => 0x9fcccccc,
          'rectangular-bottom' => 0x9fcccccc,
          'glare' => array()
        ),
        'default' => array(
          'background' => 0xeeeeee,
          'label-color' => 0x000000,
          'border' => 0xcccccc,
          'rectangular-border' => 0x3fcccccc,
          'ring1' => 0xD5D5D5,
          'ring2' => 0x736F6E,
          'ring3' => 0xCCCCCC, // inner for second ring
          'title-color' => 0x000000,
          'text-color' => 0x000000,
          'tick-color' => 0x000000,
          'pointer-color' => 0xB82313,
          'linearmeter-pointer-color' => 0x808080,
          'rectangular-border' => 0x9fcccccc,
          'rectangular-bottom' => 0x9fcccccc,
          'glare' => array()
        ),
      ),
    ),
    'semicircle' => array( 'size' => 350 ),
    'rectangularmeter' => array(
      'size' => 350
    ),
    'donutpercent' => array(
      'size' => 250,
      'color2' => 0xC2C3C2,
      'font' => array(
        'name' => 'arial.ttf',
        'color' => 0x444444 ,
      ),
      'orientation' => -1,
      'scale' => 6.25
    ),
    'softlightning' => true, // For column and bar charts toggles the softlightning effect  
    /*
    BottomLeft = 1
    BottomCenter = 2
    BottomRight = 3
    Left = 4
    Center = 5
    Right = 6
    TopLeft = 7
    TopCenter = 8
    TopRight = 9
    Top = TopCenter
    Bottom = BottomCenter
    TopLeft2 = 10
    TopRight2 = 11
    BottomLeft2 = 12
    BottomRight2 = 13
    */
    'softlightning_direction' => array(
      'column' => 4, // Left
      'column3d' => 4,
      'bar' => 8, // TopCenter
      'stackedbar' => 8,
      'stackedcolumn' => 4,
      'bar3d' => 8,
      'stackedbar3d' => 8,
      'stackedcolumn3d' => 4,
      'msbar' => 8,
      'mscolumn' => 4,
      'mscolumn3d' => 4,
      'percentbar' => 8,
      'percentcolumn' => 4,
    ),
    'always_use_link_menu' => false, // Whether single linked report shows menu or follows link 
    'default_marker' => 16, // GlassSphere2Shape
    'marker_size' => 10, // Marker size for scatter chart
    'marker_edge' => -1, // Marker edge color (-1=linecolor)
    'autoscale' => array('top'=>0.1, 'bottom'=>0.1, 'zeroaffinity'=>0.5),
    'embed_image' => false,
    'pie_label_format' => '$c->setLabelLayout("SideLayout",-1, 10);$c->setLabelStyle()->setBackground(Transparent, -1, 0);',
    'line_width' => 3,
    'width_per_item' => 110,
    'width_extra' => 80,
    'height_per_item' => 60,
    'height_extra' => 37,
    'gantt' => array(
      'baseline_pattern' => array('color' => 0xe0e0e0, 'border' => 0x808080, 'height' => 8),
      'baseline_text' => 'Baseline',
      'start_of_month_format' => '<*font=arialbd.ttf*>{value|mmm d}',
      'start_of_day_format' => '-{value|d}',
      'start_of_hour_format' => '-{value|hh}',
      'scale' => 7,
      'single_label' => 0
    ),
    'meters' => array(
      'font' => 'ariali.ttf',
    ),
    'linearmeter' => array(
      'size' => array(300, 60),
      'height' => 20,
      'label' => array( 'font' => 'arial.ttf', 'size'=>8, 'color'=> 0x00000000),
      'text' => array( 'font' => 'arial.ttf', 'size'=>8, 'color'=> 0x00000000),
      'pointer' => array( 'color'=> 0x808080),
      'type' => 'gradient',
      'zones' => array(
        array('from' => 0, 'to' => 33, 'color' => 0x4000FF00),
        array('from' => 33, 'to' => 66, 'color' => 0x40FFFF00),
        array('from' => 66, 'to' => 100, 'color' => 0x40FF0000),
      )
    ),
    'fix_y_axis' => array('yaxis_margin_right' => 8),
    'boxwhisker' => array( 'fill_color' => 0x3F90F4, 'whisker_color' => 0x3644CC, 'edge_color' => 0xCCCCCC),
    'floatingbox' => array( 'edge_color' => 0x444444),
    'skip_null_values' => true,
    'radar_transparency' => 30,
    'bubble' => array( 'transparency' => 10, 'edge_color' => 0xCCCCCC ),
    'fix_apple_browser_gradient_svg_bug' => 'png', // png = use png instead, step = use step, none = bug fixed
    'session_cache_clear' => 180, // How long the chart is kept in session in seconds
    'target' => array(
      'width' => 2,
      'gap' => 0.1
    ),
    'area' => array(
      'line' => array(
        'color' => null,
        'width' => 2
      ),
      'opacity' => 50, // Percentage
    ),
    'scatter3d' => array(
      'view_angle' => array(array( 30, 45 )),
      'wall_color' => array(array( '0xF8F8F8', '0xF8F8F8', '0xF8F8F8', '0x888888' )),
      'drop_line_color' => '0x888888'
    )
  ),
  'pager' => 20, // Default pager page size in rows 
  'automatic_parameters' => array(
    'username' => 'inLogin', // Automatic report parameter containing the username 
    'ip_address' => 'inIPAddress', // Users IP address
    'locale' => 'inLocale', // locale used in reports
    'user_agent' => 'inUseragent', // locale used in reports
    'autoexecute' => 'inAutoexecute', // Automatically executes the report if parameters are ok
    'accept_language' => 'inHTTP_ACCEPT_LANGUAGE', // HTTP_ACCEPT_LANGUAGE
    'referer' => 'inHTTP_REFERER', // HTTP_REFERER
    'export_format' => 'inExportFormat', // export=sql
    'report_url' => 'inAutoReportURL', // Report URL
    'theme' => 'inAutoTheme',
    'session_id' => 'inSessionIDHash',
    'import_filename' => 'inImportFilename'
    // 'sso_extra1' => 'in_SSO_OrganizationID', // optional extra SSO parameters
  ),
  'automatic_parameter_defaults' => array(), // Define extra-parameter values for local users, allows simulating sso_exrta
  'automatic_parameter_not_for_admin' => array(), // Define automatic-parameters which are not automatic-parameters for admin
  'parameters' => array(
    'name_suffix' => ':',
    'empty_numeric_results_null' => false,
    'empty_string_results_null' => false,
    'max_sso_extra_parameters' => 5,
    'allow_post_parameters' => false,
    'daterange_optional' => false,
    'GET_param_query' => false,
    'clean_tags_from_user_input' => true
  ),
  'missing_parameter_style' => 'color: red;',
  'password' => array( 'expiration' => 30, 'length' => 8),
  'autocomplete_max_rows' => 20,
  'header_logo_cell' => 'width: 120px; padding: 1px;',
  'category_colors' => array('00C322','FF7A00','D2006B','0066CC','CC6600', 'A300C3', 'C30061', 'C38500'), // Report category default colors
  'search' => false, // Display search + export by default
  'user_prefs_formatting' => true, // Allow user to have own preferences
  'prefs' => array(
    'allow_user_info_change' => true
  ),
  'proxy' => array(), // for optional proxy parameters
  'baselanguage' =>  'en_US',
  'translate_window' =>  array('width' => 500, 'heigth' => 600),
  'db_connection' => array(
    'translate_db_locale' =>  true, // Determines if locale is used in database operations
    'mysql_init' => array(),
    'sql_server_init' => 'SET ANSI_WARNINGS ON;SET ANSI_NULLS ON;',
    'sybase_init' => '',
    'sqlanywhere_init' => '',
    'show_verbose_error_messages_to_all_users' => false,
    'databases' => array(), /* If SQL Editor opens up too slowly due to slow "show databases"-command in MySQL, define the databases here */
    'pooled_connections' => true, /* Connections are pooled, will improve with servers that have slow connections */
    'fix_sqlsrv_field_metadata' => false,
  ),
  'linked_report' => array(
    'autoexecute_when_only_optional' => false,
    'strip_dbr_html_tags_from_links' => true,
  ),
  'active_directory_mydbr_groups' => 'myDBR Groups',
  'active_directory_mydbr_admin_group' => 'myDBR Admins',
  'active_directory_sync_users' => array(),
  'active_directory_synconly_users' => array(),
  'active_directory_recursive_user_groups' => true,
  'updater_users' => array('mydbr_updater'), // Usernames which can use HTTP basic access authentication to call myDBR update URL
  'query_builder_widths' => array('db' => 194, 'table' => 194, 'column' => 373, 'selected' => 454),
  'graphviz_dpi' => 96,
  'graphviz' => array( 'command_path' => null),
  'summary_row' => array(
    'aggregate_null' => '',
    'hide_prefix_when_empty' => true
  ),
  'export' => array(
    'export_choises' => array('Excel', 'PDF', 'CSV', 'SQL'),
    'colwidth_css' => 6.01,
    'excel' => array(
      'storage' => array(
        'cache_storage' => 'memory', // memory / cache_to_phpTemp / cache_in_memory_serialized / cache_in_memory_gzip / cache_to_apc
        'cache_settings' => array(
          'cache_to_phpTemp' => array( 'memoryCacheSize' => '10kb' ),
          'cache_to_apc' => array( 'cacheTime' => 600 ),
          'cache_to_memcache' => array( 'memcacheServer'  => 'localhost',
                                        'memcachePort'    => 11211,
                                        'cacheTime'       => 600 
                                 ),
          'cache_to_wincache' => array( 'cacheTime' => 600 ),
        )
      ),
      'type' => 'xlsx', // xls or xlsx as a default output format
      'line_height' => 12.75,
      'font_width' => 8.3,
      'include_image' => true,
      'utf8_replace' => array(),
      'evaluate_formulas' => false,
      'aggregate_formula' => true,
      'user_styles' => array(),
      'style' => array(
        'header' => array(
          'border'=> array(
            'style' => 'thin',
            'color' => array( 'rgb' => 'ff6600' )
          ),
        ),
        'header_top' => array(
          'borders'=> array(
            'top' => array(
              'style' => 'medium',
              'color' => array( 'rgb' => 'ff6600' )
            )
          ),
        ),
        'header_basic' => array(
          'borders'=> array(
            'top' => array(
              'style' => 'medium',
              'color' => array( 'rgb' => 'ff6600' )
            ),
            'bottom' => array(
              'style' => 'thin',
              'color' => array( 'rgb' => 'ff6600' )
            ),
          ),
        ),
        'title' => array( 'font' => array( 'bold' => true ) ),
        'subtitle' => array( 'font' => array( 'bold' => true ) ),
        'subtotal' => array(
          'font' => array( 'italic' => true ),
          'fill' => array(
            'type' => 'solid',
            'color' => array('rgb' => 'F0F0F0'),
          ),
          'borders'=> array(
            'top' => array(
              'style' => 'thin',
              'color' => array( 'rgb' => '0091A5' )
            ),
            'bottom' => array(
              'style' => 'thin',
              'color' => array( 'rgb' => '0091A5' )
            ),
          ),
        ),
        'footer' => array(
          'font' => array( 'italic' => true ),
          'borders' => array(
            'top' => array(
              'style' => 'thin',
              'color' => array( 'rgb' => 'AAAAAA' )
            ),
            'bottom' => array(
              'style' => 'medium',
              'color' => array( 'rgb' => '0091A5' )
            ),
          ),
        ),
        'pageview' => array(
          'fill' => array(
            'type' => 'solid',
            'color' => array( 'rgb' => 'E0E0E0' )
          ),
        ),
        'comment' => array(
          'fill' => array(
            'type' => 'solid',
            'color' => array( 'rgb' => 'FFFFCC' )
          ),
        ),
        'cell' => '', // Performance issues with PHPExcel, use only if needed
      ),
    ),
    'csv' => array(
      'delimiter' => ',',
      'decimal_point' => '.',
      'enclosure' => '"',
      'date_format' => 'Y-m-d', // See http://php.net/manual/en/function.date.php
      'time_format' => 'H:i:s',
      'charset' => 'utf-8', // utf-8, ISO-8859-1
      'line_ending' => "\n",
      'header' => true, // Output the resultset header?
      'enclose_string_with_leading_or_trailing_space' => true,
      'enclose_always' => false,
      'linefeed_between_resultsets' => true,
      'use_BOM_in_UTF' => true,
      'direct_mode' => false,
      'skip_formatting' => false
    ),
    'pdf' => array(
      'font' => 'Arial',
      'font_size' => 9,
      'font_width' => 8.5, // Arial 10 default width. Increase this if your PDF output produces columns too narrow
      'column_width_divider' => 5.941,
      'logo' => '', // in user/images
      'logo_width' => 40,
      'image_scale' => 1.25,
      'export_as_png' => true
    ),
    'wkhtmltopdf' => array(
      'command' => 'wkhtmltopdf',
      'use_as_default' => true,
      'tmp_directory' => null,
      'clean_tmp_file_in_case_of_error' => true,
      'show_failed_command_to_all' => false,
      'remind_missing' => true,
      'options' => array(
        'margin-top' => '--margin-top "20"',
        'header-spacing' => '--header-spacing "8"'
      ),
      'svg_modifications' => array(
        'chartdirector' => array(
          array('from' => '/stroke-opacity/', 'to' => 'opacity' ),
        ),
        'graphviz' => array(
          array('from' => '/<\?xml.*\<svg/s', 'to' => '<svg' )
        )
      ),
    ),
    'json' =>  array(
      'force_object' => false
    ),
    'sql' => array(
      'is_admin_only' => true
    ),
    'xml' => array('compatibility_mode' => false),
    'use_PCLZIP' => false,
    'new_window' => false,
  ),
  'favourites_enabled' => true,
  'mainview' => array(
    'favourites_enabled' => true,
    'show_edit' => true,
    'show_code' => true,
    'show_delete' => true,
    'toggle' => array(
      'enabled' => true,
      'default_state' => 'open',
      'default_state_favourites' => 'open'
    ),
    'toggle_mobile' => array(
      'enabled' => true,
      'default_state' => 'closed',
      'default_state_favourites' => 'open'
    ),
    'dashboard_on_all_folders' => true
  ),
  'sql_editor' => array(
    'show_query_builder_when_new' => false,
    'lines_when_to_use_dashes_in_comment' => 4,
    'max_saved' => array('proc' => 10, 'cmd' => 10 ),
    'autocomplete_mydbr' => true,
    'autocomplete_db' => array(),
  ),
  'file_manager' => array(
    'chmod' => 0644
  ),
  'ioncube_loader' => array(
    'recommended_version' => '10.0.0'
  ),
  'param_inline_pairs' => array( 'inStartDate' => 'inEndDate', 'dtFrom' => 'dtTo'),
  'notifications' => array( // if missing features are reminded of
    'chartdirector_missing' => 1,
    'chartdirector_not_default' => 1,
    'wkhtml_missing' => 1,
    'new_version_is_for_admin_only' => false,
    'ioncube_loader' => true
  ),
  'error_reporting' => E_ALL & ~E_NOTICE & ~E_STRICT  & ~E_DEPRECATED,
  'logout' => array(
    'url_redirect' => null,
    'url' => 'thankyou.html',
    'link_text' => '#{MYDBR_AMAIN_LOGOUT}',
    'allowed_redirect_urls_in_url_parameter' => array('')
  ),
  'google_maps_api_key' => '',
  'google_maps_business' => array('client_id' => null, 'private_key' => null),
  'cookie' => array(
    'host' => null,
    'secure_only' => false,
    'force_secure_only_on_https' => true
  ),
  'stickyheader' => true,
  'datetimefilter' => true,
  'IE_override_compatibility_view' => true,
  'single_sign_on' => array(
    'url_parameter' => 'url',
    'allow_admin' => true,
    'debug_failed_login' => false,
    'google' => array(
      'client_id' => null, // OAuth 2.0 client IDs in https://console.developers.google.com/
      'client_secret' => null, // https://console.developers.google.com/
      'redirect_uri' => null, // Define if google rejects your redirect uri
      'mydbr_url' => null, // Define if not based on your myDBR installation
      'show_local_login' => false, // Define if not based on your myDBR installation
      'prompt' => 'select_account',
      'hosted_domain' => null // Restrict login to your domain
    )
  ),
  'remote_server' => array ( 'timeout' => 60, 'skip_ssl_verify' => false ),
  'template' => array ( 'class' => 'template' ),
  'date_time_formats' => array(
    'fi_fi' => array('d' => 'd.m.Y', 't' => 'H.i.s'),
    'de_de' => array('d' => 'd.m.Y', 't' => 'H:i:s'),
    'en_us' => array('d' => 'Y-m-d', 't' => 'h:i:s a'),
    'es_es' => array('d' => 'd/m/Y', 't' => 'H.i.s'),
    'it_it' => array('d' => 'd/m/Y', 't' => 'H.i.s'),
    'nl_nl' => array('d' => 'd-m-Y', 't' => 'H.i.s'),
    'sv_se' => array('d' => 'Y-m-d', 't' => 'H:i:s')
  ),
  'connected_parameters' => array( 'show_empty_queries' => false ),
  'login' => array(
    'autocomplete' => 'on',
    'use_salted_hash' => true,
    'regenerate_session_id' => true,
  ),
  'tablesorter' => array(
    'default_sorting' => 'intelligent'
  ), /* intelligent/asc/desc */
  'show_main_without_login' => array(
    'enabled' => false,
    'show_login_link' => false
  ),
  'scrollcheckbox' => array( 'lang' => array('de', 'es', 'fi', 'it', 'nl','se') ),
  'admin_restrictions' => array(
    'restricted_actions' => array(),
    'limited_admins' => array(), /* Restrictions apply to these usernames */
    'full_admins' => array() /* No limitations to these usernames */
  ),
  'lfcr_post' => array(
    'chr' => array( chr(10), chr(13) ),
    'coded' => array( '#0A', '#0D' ),
    'to' => array( '_ x*_LF_*x_', '_ x*_CR_*x_' )
  ),
  'tooltip' => array(
    'last' => '<div class="tt_all"><div class="tt_value">%2$s</div><hr>%1$s</div>',
    'floatingbox' => '<div class="tt_all"><div class="tt_value">[%3$s] - [%4$s]</div><hr>[%1$s] / [%2$s]</div>',
  ),
  'crosstab' => array( 'tooltip' => true ),
  'password_reset' => array(
    'from' => array(
      'email' => null,
      'name' => null
    ),
    'replyto' => array(
      'email' => null,
      'name' => null
    ),
    'debug' => 0
  ),
  'mail' => array(
    'sleep_after_rows'  => 50,
    'sleep_microsec'    => 1000000, // microseconds
    'debug'             => 0,
    'notify_successful_mail' => 1,
    'keep_session' => false
  ),
  'https' => array(
    'https_in_use' => null, // If server does not use $_SERVER['https'] and you still use https, set this flag to true to force https.
    'HTTP_X_FORWARDED_PROTO' => true,  // use HTTP_X_FORWARDED_PROTO? This is in AWS
  ),
  'header_cache' => array(
    'Expires: Mon, 14 Oct 2002 05:00:00 GMT',              // Date in the past
    'Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT', // always modified
    'Cache-Control: no-store, no-cache, must-revalidate',  // HTTP 1.1
    'Cache-Control: post-check=0, pre-check=0',
    'Pragma: no-cache',                                    // HTTP 1.0
    // 'X-Frame-Options: SAMEORIGIN'
  ),
  'statistics' => array(
    'enabled' => true,
    'start' => 'sp_MyDBR_Stat_AddStart', // If changed, check sp_MyDBR_Stat_AddStart for parameters
    'end' => 'sp_MyDBR_Stat_AddEnd'
  ),
  'date_range' => array(
    'MYDBR_PARAM_DATE_RANGE_SELECT' => 'none',
    'MYDBR_PARAM_DATE_RANGE_TODAY' => array('from' => array('value' => 0, 'type' => 'day'), 'to' => array('value' => 0, 'type' => 'day')),
    'MYDBR_PARAM_DATE_RANGE_YESTERDAY' => array('from' => array('value' => -1, 'type' => 'day'), 'to' => array('value' => -1, 'type' => 'day')),
    'MYDBR_PARAM_DATE_RANGE_1WEEK' => array('from' => array('value' => -6, 'type' => 'day'), 'to' => array('value' => 0, 'type' => 'day')),
    'MYDBR_PARAM_DATE_RANGE_2WEEKS' => array('from' => array('value' => -13, 'type' => 'day'), 'to' => array('value' => 0, 'type' => 'day')),
    'MYDBR_PARAM_DATE_RANGE_THISWEEK' => array('from' => array('value' => 0, 'type' => 'week'), 'to' => array('value' => null, 'type' => 'week')),
    'MYDBR_PARAM_DATE_RANGE_LASTWEEK' => array('from' => array('value' => -1, 'type' => 'week'), 'to' => array('value' => -1, 'type' => 'week')),
    'MYDBR_PARAM_DATE_RANGE_THISMONTH' => array('from' => array('value' => 0, 'type' => 'month'), 'to' => array('value' => null, 'type' => 'month')),
    'MYDBR_PARAM_DATE_RANGE_LASTMONTH' => array('from' => array('value' => -1, 'type' => 'month'), 'to' => array('value' => -1, 'type' => 'month')),
    'MYDBR_PARAM_DATE_RANGE_1MONTH' => array('from' => array('value' => -1, 'type' => 'month'), 'to' => array('value' => 0, 'type' => 'month')),
    'MYDBR_PARAM_DATE_RANGE_2MONTHS' => array('from' => array('value' => -2, 'type' => 'month'), 'to' => array('value' => 0, 'type' => 'month')),
    'MYDBR_PARAM_DATE_RANGE_3MONTHS' => array('from' => array('value' => -3, 'type' => 'month'), 'to' => array('value' => 0, 'type' => 'month')),
    'MYDBR_PARAM_DATE_RANGE_QUARTER' => array('from' => array('value' => 0, 'type' => 'quarter'), 'to' => array('value' => null, 'type' => 'quarter')),
    'MYDBR_PARAM_DATE_RANGE_LASTQUARTER' => array('from' => array('value' => -1, 'type' => 'quarter'), 'to' => array('value' => -1, 'type' => 'quarter')),
    'MYDBR_PARAM_DATE_RANGE_YEARTODAY' => array('from' => array('value' => 0, 'type' => 'year'), 'to' => array('value' => 0, 'type' => 'month')),
    'MYDBR_PARAM_DATE_RANGE_THISYEAR' => array('from' => array('value' => -1, 'type' => 'year'), 'to' => array('value' => null, 'type' => 'year')),
    'MYDBR_PARAM_DATE_RANGE_LASTYEAR' => array('from' => array('value' => -1, 'type' => 'year'), 'to' => array('value' => -1, 'type' => 'year')),
    'MYDBR_PARAM_DATE_RANGE_YEAR' => array('from' => array('value' => -1, 'type' => 'year'), 'to' => array('value' => 0, 'type' => 'year')),
  ),
  'remote_files' => array('export_header_pdf.php'),
  'user_interface' => array(
    'tablets_use_desktop_ui' => true,
    'force_mobile' => '',
    'force_desktop' => '',
  ),
  'sync' => array(
    'folder' => 'myDBR Sync',
    'delimiter' => ':::',
  ),
  'hashing_algorithm' => array(
    'sso' => 'sha1',
    'automatic_parameter_session_id' => 'sha1',
    'default' => 'sha1'
  ),
  'server_error' => array(
   'text' => '#{MYDBR_ERROR_USER_TEXT}',
   'text_with_optional_link' =>' #{MYDBR_ERROR_USER_OPTIONAL}',
   'admin_mail_address' => '', // Define this and user error messages will include a mailto link
   'mail_subject' => '#{MYDBR_ERROR_USER_MAIL_SUBJECT}',
   'show_full_error_message_to_non_admin' => false
  ),
  /* OEM license capability to change the report logo dynamically, see user/reportheader_image.php */
  'reportheader_logo' => array(
    'enabled' => false,
    'param' => 'inLogin'
  ),
  'calendar' => array(
    'options' => array(
      'fixedWeekCount' => false,
      'weekNumbers' => true,
      'weekNumbers' => true,
      'navLinks' => true,
      'fixedWeekCount' => false,
      'columnFormat' => 'dddd',
      'header' => array('left' => 'prev,next today', 'center' => 'title', 'right' => 'month,agendaWeek,agendaDay,listMonth'),
      'displayEventEnd' => true
    ),
  ),
  'extension_like' => array(
    'code_editing' => array(
      'name' => '#{MYDBR_EXT_LIKE_CODE_EDITING}',
      'js' => array('lib/external/codemirror/editor.js'),
      'css' => array(array('src' => 'lib/external/codemirror/editor.css'))
    ),
    'calendar' => array(
      'name' => '#{MYDBR_EXT_LIKE_CALENDAR}',
      'js' => array('lib/external/calendar/fullcalendar.min.js','lib/external/calendar/locale-all.js'),
      'css' => array(
          array('src' => 'lib/external/calendar/fullcalendar.min.css' ),
          // array('src' => 'lib/external/calendar/fullcalendar.print.min.css', 'media'=>'print' )
        )
    ),
    'richtext' => array(
      'name' => '#{MYDBR_EXT_LIKE_RICHTEXT}',
      'js' => array('lib/external/tinymce/tinymce.min.js'),
      'css' => array()
    ),
  ),
  'request'=> array('ajax_only' => true),
  'extension_like_enabled' => array(), // internal to myDBR
  'dbr_head' => array('enabled' => true),
);

// User's definitions for $mydbr_defaults. Do not include the user/defaults.php in case of an error in the user edited script
if (!(  isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME'])=='fileedit_v.php' &&
  isset($_POST['action']) && $_POST['action']=='save_file')) {
  require_once( dirname(__FILE__).'/user/defaults.php' );
}