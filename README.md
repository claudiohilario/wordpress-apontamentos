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
