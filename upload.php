<?php
$foto_tmp = $_FILES['foto']['tmp_name'];
$foto_name = $_FILES['foto']['name'];
$foto_type = $_FILES['foto']['type'];
$foto_size = $_FILES['foto']['size'];

$directory      = "./media/source/";
$directory_compress      = "./media/compressed/";

$tgl = date("YmdHis");
$file = $directory . $tgl;

//Simpan Gambar Ukuran Asli
$realImagesName = $_FILES['foto']['tmp_name'];

//Upload File Asli
move_uploaded_file($realImagesName, $file);

//Identitas File Gambar
$realImages = imagecreatefromjpeg($file);
$width = imageSX($realImages);
$height = imageSY($realImages);

//Menentukan Ukuran Compressed Gambar
$img_width = (75 / 100) * $width;
$img_height = ($img_width / $width) * $height;

//Mengubah Ukuran Image
$compressed_img = imagecreatetruecolor($img_width, $img_height);
imagecopyresampled($compressed_img, $realImages, 0, 0, 0, 0, $img_width, $img_height, $width, $height);

//Simpan Gambar Thumbnail
imagejpeg($compressed_img, $directory_compress . "compressed_" . $tgl . ".jpeg");

//Menghapus Objek Dalam Memory
imagedestroy($realImages);
imagedestroy($compressed_img);

//Menghapus Image Asli
unlink($file);

echo "Sukses";
