<?php
// deny access
if( !defined('ABSPATH') ) die('Security check');
if(!current_user_can(MODMAN_CAPABILITY)) die('Access Denied');
?>

<div id="module-manager-wrap" class="wrap">
    <?php screen_icon('module-manager'); ?>
    <h2><?php _e('Module Manager','module-manager'); ?></h2><br />
    
    <?php
    
    $mtabs=array(
        'modules' => __('Define Modules','module-manager'),
        'import' => __('Import Modules','module-manager'),
        'library' => __('Modules Library','module-manager')
    );

    if (!isset($_GET['tab']) || !in_array($_GET['tab'],array_keys($mtabs)))
        $current_tab='modules';
    else
        $current_tab=$_GET['tab'];
    
    //$murl=admin_url('options-general.php').'?page=ModuleManager_Modules';
    //$murl = add_query_arg( 'tab', $current_tab, remove_query_arg( array( 'tab' ), $murl ) );
    $_base_url=ModuleManager::$page;
    $murl = add_query_arg( 'tab', $current_tab, remove_query_arg( array( 'tab' ), $_base_url ) );
    
    echo '<h2 class="modman-tabs-wrapper nav-tab-wrapper">';
        foreach( $mtabs as $tab => $tabtitle)
        {
            $_tab_url = add_query_arg( 'tab', $tab, $_base_url);
            $class = ( $tab == $current_tab ) ? 'nav-tab-active' : '';
            echo "<a class='nav-tab $class' href='{$_tab_url}'>{$tabtitle}</a>";
        }
    echo '</h2>';
    
    if (defined('WPCF_VERSION') && version_compare(WPCF_VERSION, '1.2.1', '<'))
    {
        echo "<div class='error'><p>".__('Types 1.2.1 or greater is required for Module Manager', 'module-manager')."</p></div>";
    }
    if (defined('WPV_VERSION') && version_compare(WPV_VERSION, '1.2.1', '<'))
    {
        echo "<div class='error'><p>".__('Views 1.2.1 or greater is required for Module Manager', 'module-manager')."</p></div>";
    }
    if (defined('CRED_FE_VERSION') && version_compare(CRED_FE_VERSION, '1.1.3', '<'))
    {
        echo "<div class='error'><p>".__('CRED 1.1.4 or greater is required for Module Manager', 'module-manager')."</p></div>";
    }
    
    switch($current_tab)
    {
        case 'import':
            // import module
            $model=ModMan_Loader::get('MODEL/Modules');
            $sections=$model->getRegisteredSections();
            echo ModMan_Loader::tpl('import', array(
                'sections' => $sections,
                'url'=>$murl,
                'mm_url'=>ModuleManager::$page
            ));
            break;
        case 'library':
           	// display library
        	$library_class_path=MODMAN_LIBRARYCLASS_PATH.'/Class_Modules_Library.php';
        	require_once($library_class_path);
        	$Class_Modules_Library = new Class_Modules_Library;
            break;
        case 'modules':
        default:
        // define/export modules
            $model=ModMan_Loader::get('MODEL/Modules');
            $sections=$model->getRegisteredSections();
            echo ModMan_Loader::tpl('modules', array(
                'sections' => $sections,
                'items' => $model->getRegisteredItemsPerSection($sections),
                'modules' => $model->getModules()
            ));
            break;
    }
    ?>
    
</div>