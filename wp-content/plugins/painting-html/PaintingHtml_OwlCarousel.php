<?php
class PaintingHtml_OwlCarousel {
    public function register_post_types()
    {
        if(!post_type_exists('painting_gallery'))
        {
            $args = array(
                'public'    => true,
                'exclude_from_search' => true,
                'label'     => __('Painting Galleries', 'painting_html'),
                'menu_icon' => 'dashicons-format-gallery',
                'has_archive'=> true,
                'supports' => ['title'],
                'register_meta_box_cb' => array(&$this, 'gallery_mb')
            );
            register_post_type('painting_gallery', $args);
        }

        if(!post_type_exists('single_gallery'))
        {
            $args = array(
                'public'    => true,
                'exclude_from_search' => true,
                'label'     => __('Single Galleries', 'painting_html'),
                'menu_icon' => 'dashicons-format-gallery',
                'has_archive'=> true,
                'supports' => ['title'],
                'register_meta_box_cb' => array(&$this, 'gallery_single_mb')
            );
            register_post_type('single_gallery', $args);
        }
    }

    public function gallery_mb()
    {
        add_meta_box( 'gallery_mb', __( 'ITEMS', 'painting_html' ), array(&$this,'gallery_mb_callback'), 'painting_gallery' );
    }
    public function gallery_mb_callback()
    {
        global $post;
        $slides_json = get_post_meta($post->ID, 'pg_items', true);
        $slides_arr = [];
        if(!empty($slides_json))
        {
            $slides_arr = json_decode($slides_json, true);
        }
        $lies = '';
        if(is_array($slides_arr) && count($slides_arr) > 0)
        {
            foreach($slides_arr as $k=>$v)
            {
                $lies .= '<li class="gmb_item">';
                $lies .= '<table style="background-color:rgb(241, 241, 241); border-radius:5px;">';
                $lies .= '<tr>';
                $lies .= '<td style="width:250px; max-width:250px; padding:0 1em 1em 1em; text-align:center;"><img src="' . wp_get_attachment_url($v['id']) . '" style="width:100%;" /><br/><span style="cursor:pointer; color:red;" onclick="do_remove(this)" >remove</span></td>';
                $lies .= '<td style="vertical-align:top; padding-left:1em; padding-right:1em;">';
                $lies .= '<p><input type="text" style="width:400px;" placeholder="Title" value="' . $v['title'] . '" name="pg_item_title_' . ($k + 1) . '"></p>';
                $lies .= '<p><textarea rows="5" style="width:400px;" placeholder="Description" name="pg_item_sub_'  . ($k + 1) . '">' . $v['sub'] . '</textarea></p>';
                $lies .= '<p><input type="text" style="width:400px;" placeholder="Button Text" value="' . $v['btn'] . '" name="pg_item_btn_'  . ($k + 1) . '"></p>';
                $lies .= '<p><input type="text" style="width:400px;" placeholder="Button Url" value="' . $v['url'] . '" name="pg_item_url_' . ($k + 1) . '"></p>';

                $lies .= '<p><input type="number" min="1" step="1" style="width:100px;" placeholder="Order" value="' . $v['seq'] . '" name="pg_item_seq_'  . ($k + 1) . '"></p>';
                $lies .= '<input type="hidden" name="pg_item_id_'  . ($k + 1) . '" value="' . $v['id'] . '"></input>';
                $lies .= '</td>';
                $lies .= '</tr>';
                $lies .= '</table>';
                $lies .= '</li>';
            }
        }
        
        ?>
        <p><input type="button" value="ADD" class="button btn-primary" id="btnGmbAddItem" /></p>
        <ul id="gmbContainer"><?= $lies ?></ul>
        <?php
    }
    public function save_gallery_mb($post_id, $post, $update)
    {
        if(!in_array($post->post_type, ['painting_gallery', 'single_gallery'])) {
            return;
        }

        switch($post->post_type)
        {
            case 'painting_gallery':
                if(is_array($_POST) && count($_POST) > 0)
                {
                    $slides = [];
                    foreach($_POST as $k=>$v)
                    {
                        if(substr($k, 0, 11) == 'pg_item_id_')
                        {
                            $index = intval(str_replace("pg_item_id_", "", $k));
                            if(!empty($index) && !empty($v))
                            {
                                $arr = ['id' => $v];
                                $arr['title'] = isset($_POST['pg_item_title_' . $index]) ? $_POST['pg_item_title_' . $index] : '';
                                $arr['sub'] = isset($_POST['pg_item_sub_' . $index]) ? $_POST['pg_item_sub_' . $index] : '';
                                $arr['btn'] = isset($_POST['pg_item_btn_' . $index]) ? $_POST['pg_item_btn_' . $index] : '';
                                $arr['url'] = isset($_POST['pg_item_url_' . $index]) ? $_POST['pg_item_url_' . $index] : '';
                                $arr['seq'] = isset($_POST['pg_item_seq_' . $index]) ? $_POST['pg_item_seq_' . $index] : 0;
                                $slides[] = $arr;
                            }
                        }
                    }
                    if(is_array($slides) && count($slides) > 0) 
                    {
                        $seq = array_column($slides, 'seq');
                        array_multisort($seq, SORT_ASC, $slides);
                        $json = json_encode($slides, JSON_UNESCAPED_UNICODE);
                        update_post_meta( $post_id, 'pg_items', $json);
                    }
                }
                break;
            case 'single_gallery':
                if(is_array($_POST) && count($_POST) > 0)
                {
                    $slides = [];
                    foreach($_POST as $k=>$v)
                    {
                        if(substr($k, 0, 11) == 'pg_item_id_')
                        {
                            $index = intval(str_replace("pg_item_id_", "", $k));
                            if(!empty($index) && !empty($v))
                            {
                                $arr = ['id' => $v];
                                $arr['seq'] = isset($_POST['pg_item_seq_' . $index]) ? $_POST['pg_item_seq_' . $index] : 0;
                                $slides[] = $arr;
                            }
                        }
                    }
                    if(is_array($slides) && count($slides) > 0) 
                    {
                        $seq = array_column($slides, 'seq');
                        array_multisort($seq, SORT_ASC, $slides);
                        $json = json_encode($slides, JSON_UNESCAPED_UNICODE);
                        update_post_meta( $post_id, 'pg_items', $json);
                    }
                }
                break;
        }

    }

    public function painting_gallery($atts)
    {
        $html = '';
        //if(is_front_page()) {
            $id = !empty($atts['id']) ? intval($atts['id']) : 0; 
            $class = !empty($atts['class']) ? "carousel " . $atts['class'] : "carousel"; 
            if(!empty($id))
            {
                $slides_json = get_post_meta( $id, 'pg_items', true);
                if(!empty($slides_json))
                {
                    $slides_arr = json_decode($slides_json, true);
                    if(is_array($slides_arr) && count($slides_arr) > 0)
                    {
                        $html .= '<div id="pg_' . $id . '" class="' . $class . '">';
                        foreach($slides_arr as $v)
                        {
                            $html .= '<div class="slide">'; 
                                $html .= '<img src="' . wp_get_attachment_url($v['id']) . '" class="background-image" alt="' . $v['title'] . '">';
                                $html .= '<div class="content">';
                                    $html .= '<h2>' . $v['title'] . '</h2>';
                                    $html .=  '<p>' . $v['sub'] . '<p>';
                                    if(!empty($v['btn'])) 
                                    {
                                        $html .= '<a href="' . (!empty($v['url']) ? $v['url'] : '#')  . '" class="slide-btn">' . $v['btn'] . '</a>';
                                    }
                                $html .= '</div>';
                            $html .= '</div>';
                        }
                        $html .= '</div>';
                    }
                }
            }
        //}
     
        return $html;
    }

    /* SINGLE */
    public function gallery_single_mb()
    {
        add_meta_box( 'gallery_single_mb', __( 'ITEMS', 'painting_html' ), array(&$this,'gallery_single_mb_callback'), 'single_gallery' );
    }
    public function gallery_single_mb_callback()
    {
        global $post;
        $slides_json = get_post_meta($post->ID, 'pg_items', true);
        $slides_arr = [];
        if(!empty($slides_json))
        {
            $slides_arr = json_decode($slides_json, true);
        }
        $lies = '';
        if(is_array($slides_arr) && count($slides_arr) > 0)
        {
            foreach($slides_arr as $k=>$v)
            {
                $lies .= '<li class="gmb_item">';
                $lies .= '<table style="background-color:rgb(241, 241, 241); border-radius:5px;">';
                $lies .= '<tr>';
                $lies .= '<td style="width:250px; max-width:250px; padding:0 1em 1em 1em; text-align:center;"><img src="' . wp_get_attachment_url($v['id']) . '" style="width:100%;" /><br/><span style="cursor:pointer; color:red;" onclick="do_remove(this)" >remove</span></td>';
                $lies .= '<td style="vertical-align:top; padding-left:1em; padding-right:1em;">';
                $lies .= '<p><input type="number" min="1" step="1" style="width:100px;" placeholder="Order" value="' . $v['seq'] . '" name="pg_item_seq_'  . ($k + 1) . '"></p>';
                $lies .= '<input type="hidden" name="pg_item_id_'  . ($k + 1) . '" value="' . $v['id'] . '"></input>';
                $lies .= '</td>';
                $lies .= '</tr>';
                $lies .= '</table>';
                $lies .= '</li>';
            }
        }
        
        ?>
        <p><input type="button" value="ADD" class="button btn-primary" id="btnGmbAddItemSingle" /></p>
        <ul id="gmbContainerSingle"><?= $lies ?></ul>
        <?php
    }

    public function painting_gallery_single($atts)
    {
        $html = '';
        //if(is_front_page()) {
            $id = !empty($atts['id']) ? intval($atts['id']) : 0; 
            $class = !empty($atts['class']) ? "carousel-single " . $atts['class'] : "carousel-single"; 
            if(!empty($id))
            {
                $slides_json = get_post_meta( $id, 'pg_items', true);
                if(!empty($slides_json))
                {
                    $slides_arr = json_decode($slides_json, true);
                    if(is_array($slides_arr) && count($slides_arr) > 0)
                    {
                        $html .= '<header class="section-carousel">';
                        $html .= '<div id="pg_' . $id . '" class="' . $class . ' owl-carousel owl-drag owl-theme">';
                        foreach($slides_arr as $v)
                        {
                            $html .= '<div class="carousel-item w-100 d-flex align-items-center" style="background-image: url(' . wp_get_attachment_url($v['id']) . ');"></div>';
                        }
                        $html .= '</div>';
                        $html .= '</header>';
                    }
                }
            }
        //}
     
        return $html;

    }
}

?>