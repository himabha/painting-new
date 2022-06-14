<?php include_once(dirname(__FILE__).'/../config.php');
$uploads_dir = plugin_dir_path(__FILE__). "/../uploading/";
if ($_FILES["upload_print"]["error"] == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES["upload_print"]["tmp_name"];
    // basename() may prevent filesystem traversal attacks;
    // further validation/sanitation of the filename may be appropriate
    $name = basename($_FILES["upload_print"]["name"]);
    try {
        if (move_uploaded_file($tmp_name, "$uploads_dir/$name")) {
            $image_size = getimagesize("$uploads_dir/$name");


// First we create our stamp image manually from GD
            //$stamp = imagecreatetruecolor(100, 70);
            $stamp = imagecreatefrompng("$uploads_dir/$name");
            //imagefilledrectangle($stamp, 0, 0, 200, 69, 0x0000FF);
            imagefilledrectangle($stamp, 9, 9, 90, 60, 0xFFFFFF);
            imagestring($stamp, 5, 20, 20, 'libGD', 0x0000FF);
            imagestring($stamp, 3, 20, 40, '(c) 2007-9', 0x0000FF);

// Set the margins for the stamp and get the height/width of the stamp image
            $marge_right = 10;
            $marge_bottom = 10;
            $sx = imagesx($stamp);
            $sy = imagesy($stamp);
            imagepng($stamp, './test.png');

            /*
                        // Load the stamp and the photo to apply the watermark to
                        $im = imagecreatefromjpeg("$uploads_dir/$name");

            // First we create our stamp image manually from GD
                        $stamp = imagecreatetruecolor(100, 70);
                        imagefilledrectangle($stamp, 0, 0, 99, 69, 0x0000FF);
                        imagefilledrectangle($stamp, 9, 9, 90, 60, 0xFFFFFF);
                        imagestring($stamp, 5, 20, 20, 'libGD', 0x0000FF);
                        imagestring($stamp, 3, 20, 40, '(c) 2007-9', 0x0000FF);

            // Set the margins for the stamp and get the height/width of the stamp image
                        $marge_right = 10;
                        $marge_bottom = 10;
                        $sx = imagesx($stamp);
                        $sy = imagesy($stamp);

            // Merge the stamp onto our photo with an opacity of 50%
                        imagecopymerge($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), 50);

            // Save the image to file and free memory
                        imagepng($im, './photo_stamp.png');
                        imagedestroy($im);
            */


            // Create the image handle, set the background to white
            $im = imagecreatetruecolor(500, 500);
            imagefilledrectangle($im, 0, 0, 500, 500, imagecolorallocate($im, 100, 255, 255));

            //$dest = imagepng($im);
// Draw an ellipse to fill with a black border
            //imageellipse($im, 50, 50, 50, 50, imagecolorallocate($im, 0, 0, 0));
            //header('Content-type: image/png');
            //$dest = imagecreatefrompng("$uploads_dir/$name");
            //$dest = imagepng($im);
            //print_r($dest);

            $src = imagecreatefrompng("$uploads_dir/$name");
            //imagepng($src,'./example-cropped.png');

            $src1 = imagecreatefrompng("$uploads_dir/$name");
            $im = imagerotate($src1, 90, 0);
            //imagepng($im,'./example-cropped1.png');


            echo $sx = imagesx($im);
            echo $sy = imagesy($im);
            imagecopymerge($src, $im, imagesx($src) - $sx - $marge_right, imagesy($src) - $sy - 10, 0, 0, imagesx($im), imagesy($im), 50);

            imagepng($src, './photo_stamp1.png');
            imagedestroy($src);
            imagedestroy($src1);
            imagedestroy($im);
            //$src = imagepng($src);
            //imagecopymerge($im, $src, 30, 30, 100, 100, $width, $height, 0);

            //header('Content-type: image/png');
            //imagepng($im, './example-cropped.png');
            //imagedestroy($src);
            //imagedestroy($im);

            //imagedestroy($dest);
// Set the border and fill colors
            //$border = imagecolorallocate($im, 0, 0, 0);
            //$fill = imagecolorallocate($im, 255, 0, 0);

// Fill the selection
            //imagefilltoborder($im, 50, 50, $border, $fill);

// Output and free memory
            header('Content-type: image/png');

            exit;

            $im = imagecreatefrompng("$uploads_dir/$name");
            echo "<pre>";

            print_r(imagepalettetotruecolor($im));
            echo imagesx($im);
            echo imagesy($im);
            $size = min(imagesx($im), imagesy($im));
            echo "<pre>";
            print_r($size);

            $width  = imagesx($im);                             //get width of source image
            $height = imagesy($im);                             //get height of source image
            $image2 = imagecreatetruecolor($width,$height);        //create new image of true colors with given width and height
            $im2 = imagecopy($image2,$im,0,0,0,0,$width,$height);
            imagepng($im2, './example-cropped.png');
            imagedestroy($image2);
            $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
            if ($im2 !== FALSE) {
                imagepng($im2, './example-cropped.png');
                imagedestroy($im2);
            }
            imagedestroy($im);

            // Create a 55x30 image
            $im = imagecreatetruecolor(55, 30);
            $white = imagecolorallocate($im, 255, 255, 100);

// Draw a white rectangle
            imagefilledrectangle($im, 10, 10, 50, 25, $white);

// Save the image
            imagepng($im, './imagefilledrectangle.png');
            imagedestroy($im);

            exit;


            $width = $image_size[0];
            $height = $image_size[1];
            $proportion = $width/$height;
            $url = "";
            if (isset($name)) {
                $ServerURL = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $url = plugins_url("/uploading/$name", dirname(__FILE__));
            }
            echo json_encode(
                array(
                    "proportion" => $width/$height,
                    "url" => $url,
                    "filename" => $name,
                    "width" => $width,
                    "height" => $height
                )
            );
        } else {
            echo json_encode(array("error" => "File cannot be uploaded"));
        }
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
} else {
    echo json_encode(array("error" => $_FILES["upload_print"]["error"]));
}
