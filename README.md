# Apontamentos de Wordpress

## Menus no Wordpress
No ficheiro functions.php do template colocar
```php
register_nav_menus( array(
  'menu-1' => 'Primary',
  'menu-2' => 'Secondary',
) );
```

Aceder aos itens do menu:
```php
  $menuLocations = get_nav_menu_locations(); // Todas as localizações ['menu-1' => 1, 'menu-2' => 2]
  $menuID = $menuLocations['menu-1']; 
  $menuItems = wp_get_nav_menu_items($menuID); // Todos os items do menu
```

Função utilitária:
```php 
  function getMenus($menuLocation) {
    $menuIDByLocation = get_nav_menu_locations()[$menuLocation];
		$menuItems = wp_get_nav_menu_items($menuIDByLocation);

		$menus = array();

		foreach($menuItems as $item) {
		  $id = $item->ID;
		  $name = $item->post_title;
		  $url = $item->url;
		  $menuParentId = $item->menu_item_parent;

		  $menus[$id] = array(
			  'id' => $id,
			  'name' => $name,
			  'url' => $url,
			  'parentId' => $menuParentId,
			);
		}
		return $menus;
	}
      
echo('<pre>');
var_dump(getMenus('menu-1'));
echo('</pre>');
```
## WPDB - Manipulação de dados com wordpress
### Criar uma tabela custom no wordpress (utilizado por exemplo na ativação do plugin)
```php
            function createCustomTable($dropTable = false) {
                global $wpdb;

                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

                $table_name = $wpdb->prefix . "custom_table";
                $charset_collate = $wpdb->get_charset_collate();

                if($dropTable) {
                    $sqlDropTable = "DROP TABLE IF EXISTS $table_name" ;
                    $wpdb->query($sqlDropTable);
                }

                // SQL para criar a tabela custom
                $sqlCreateTable = "CREATE TABLE IF NOT EXISTS $table_name (
                    id int(10)
                  ) $charset_collate";

                dbDelta( $sqlCreateTable );
            }
	    
	    //
	    
	    // Exemplo drop de tabela ao desativar um determinado plugin
	    register_deactivation_hook( __FILE__, 'my_plugin_remove_database' );
function my_plugin_remove_database() {
     global $wpdb;
     $table_name = $wpdb->prefix . 'table_name';
     $sql = "DROP TABLE IF EXISTS $table_name";
     $wpdb->query($sql);
     delete_option("my_plugin_db_version");
}   

```


