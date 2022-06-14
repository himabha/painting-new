<?php
include_once(dirname(__FILE__) . '/../config.php');
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
    $http = "https://";
} else {
    $http = "http://";
}
$cond = "";
if (isset($_POST['type']) && $_POST['type'] != "") {
    $cond = 'and type = "' . addslashes($_POST['type']) . '"';
}
if (isset($_POST['color']) && $_POST['color'] != "") {
    $cond .= ' and color = "' . addslashes($_POST['color']) . '"';
}

// prepare and bind
$results = $wpdb->get_results("SELECT * from frames where active = 1 " . $cond . " order by sort asc");
if (empty($results)) {
    $html = "";
} else {
    $i = 0;
    $html = "";
    while ($i < count($results)) {
        $data = json_encode($results[$i]);
        if ($i % 3 == 0) {
            $html .= '<div class="tr">';
        }
        $images = json_decode($results[$i]->img_path);
        $needle = '-p';
        $ret = key(array_filter($images, function($var) use ($needle){
            return strpos($var, $needle) !== false;
        }));
        $html .= '<div class="td">
		<img onclick="onframechose(this, true)" width="150px" height="150px" class="frame_img" src="' . plugins_url($images[$ret], dirname(__FILE__)) . '"/><input type="hidden" class="frame_data" name="frame_data_<?php echo $i;?>" id="frame_data_' . $i . '" value=\'' . $data . '\'/></div>';
        if ($i > 0 && (($i + 1) % 3 == 0 || ($i+1) == count($results))) {
            $html .= '</div>';
        }
        $i++;
    }
}
echo $html;
