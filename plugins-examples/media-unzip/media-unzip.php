<?php
/*
 * Plugin Name: Media Unzip
 * Plugin URI: http://www.example.com
 * Description: Plugin developed to send zipped images
 * Version: 1.0
 * Author: Cláudio Hilário
 * Author URI: http://www.example.com
 * Text Domain: media-unzip
 * License: MIT
 */

if(!defined('ABSPATH')) exit;

class Media_Unzip {
  private static $instance;

  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {
      add_action( 'admin_menu', array($this, 'start_media_file_unzip') );
  }

  public function start_media_file_unzip() {
    add_menu_page(
        'Upload Media Zip',
        'Upload Media Zip', 
        'manage_options',
        'upload_media_zips',
        'Media_Unzip::upload_media_zips',
        'dashicons-media-archive',
        10
    );
  }


  public function allowed_file_types($filetype) {
    $allowed_file_types = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');
    if(in_array($filetype, $allowed_file_types)) {
        return true;
    }
    return false;
  }

  public function upload_media_zips() {
    _e('<h3>Upload de ficheiros ZIP</h3>', 'media-unzip');

    if(isset($_FILES['fileToUpload'])) {
        $dir = "../wp-content/uploads".wp_upload_dir()['subdir'];

        $target_file = $dir.'/'.basename($_FILES['fileToUpload']['name']);
        move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file);

        $file_name = basename($_FILES['fileToUpload']['name']);

        $zip = new ZipArchive();

        $res = $zip->open($target_file);

        if($res == TRUE) {
            $zip->extractTo($dir);
            echo "<h3 style='color:#090;'> O ficheiro $file_name foi extraído com sucesso ".wp_upload_dir()['url']."</h3>";

            // Mostrar mensagem com o numero de ficheiros de midia no zip.

            echo "Tem ".$zip->numFiles." ficheiros neste zip <br>";

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $media_file_name = wp_upload_dir()['url'].'/'.$zip->getNameIndex($i);
                $filetype = wp_check_filetype(basename($media_file_name), null);

                $allowed = Media_Unzip::allowed_file_types($filetype['type']);

                if($allowed) {
                    echo '<a href="'.$media_file_name.'" target="_blank">'.$media_file_name.'</a>
                          Tipos: '.$filetype['type'].' <br />';

                    $attachment = array(
                        'guid' => $media_file_name,
                        'post_mime_type' => $filetype['type'],
                        'post_title'  => preg_replace('/\.[^.]+$/', '', $zip->getNameIndex($i)),
                        'post_content' => '',
                        'post_status' => 'inherit',
                    );

                    $attach_id = wp_insert_attachment( $attachment, $dir.'/'.$zip->getNameIndex($i));

                    $attach_data = wp_generate_attachment_metadata($attach_id, $dir.'/'.$zip->getNameIndex($i));

                    wp_update_attachment_metadata($attach_id, $attach_data );
                }
                else {
                    echo $zip->getNameIndex($i).' Não pode ser enviado, o tipo '.$filetype['type'].' não é permitido <br>';
                }
            }
        } else {
            echo "<h3 style='color: #F00;'> O arquivo zip não foi extraído com sucesso</h3>";
        }

        $zip->close();
    }

    echo '
        <form style="margin-top: 20px;" action="/wordpress/wp-admin/admin.php?page=upload_media_zips" enctype="multipart/form-data" method="post">
            Selecione o ficheiro zip <input type="file" name="fileToUpload" id="fileToUpload" />
            <br />
            <input type="submit" value="Upload do gicheiro" name="submit" />
        </form>
    ';
  }
}

Media_Unzip::getInstance();
