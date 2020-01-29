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
# Templates

## Ficheiros que compõem um template no Wordpress

* **index.php** - Ficheiro correspondente à página principal e é necessário em todos os templates.
* **style.css** - Ficheiro de folha de estilo principal. É necessário em todos os Templates e contém o cabeçalho de informação do template.
* **single.php** - Ficheiro de artigo único é usado quando é solicitado um único artigo.
* **single-{post-type}.php** - Ficheiro de artigo único correspondente a um determinado tipo de post personalizado.
* **archive.php** - O ficheiro archive é usado quando são solicitadas arquivos por categoria, autor ou data. Nota: Este ficheiro é substituído se houver modelos mais específicos como category.php, author.php e date.php.
* **archive-{post-type}.php** - Este ficheiro é usado quando são solicitados artigos de um tipo de post personalizado.
* **searchform.php** - Corresponde ao formulário de pesquisa
* **search.php** - Este ficheiro é usado para mostrar resultados de uma determinada pesquisa.
* **404.php** - Este ficheiro é usado quando o wordpress não consegue localizar um post, página ou outro conteúdo.
* **comments.php** - Ficheiro que corresponde ao template dos comentários.
* **footer.php** - Ficheiro que corresponde ao rodapé.
* **header.php** - Ficheiro que corresponde ao cabeçalho.
* **sidebar.php** - Ficheiro que corresponde à barra lateral.
* **page.php** - Este ficheiro é usado quando é solicitada uma página específica.
* **tag.php** - Este ficheiro é usado quando são solicitados posts por tag.
* **screenshot.png** - Imagem do template (Aparece no painel de controlo do Wordpress).
* **functions.php** - Permite adicionar recursos.

