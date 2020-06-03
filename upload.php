<?php
$foto_type = $_FILES['foto']['type'];

$directory      = "./media/source/";
$directory_compress      = "./media/compressed/";

$tgl = date("YmdHis");
$file = $directory . $tgl;

/**
 * Mengubah Base 64 Disik
 */
$data = file_get_contents($_FILES['foto']['tmp_name']);
$type = explode("/", $foto_type);

/**
 * Di Anggap File Sudah base64
 */
$base64_encode = 'data:image/' . $type[1] . ';base64,' . base64_encode($data);

$img_base64 = explode(";base64,", $base64_encode);

/**
 * Mengambil meta data dari base64
 */
$f = finfo_open();

$base64_type_files = finfo_buffer($f, base64_decode($img_base64[1]), FILEINFO_MIME_TYPE);
$type = explode("/", $base64_type_files);

//Upload File Asli
file_put_contents($file . "." . $type[1], base64_decode($img_base64[1]));

//Identitas File Gambar
$realImages = "";
if ($type[1] == "png") {
  $realImages = imagecreatefrompng($file . "." . $type[1]);
} else {
  $realImages = imagecreatefromjpeg($file . "." . $type[1]);
}
$width = imageSX($realImages);
$height = imageSY($realImages);

/**
 * Menentukan Ukuran Compressed Gambar
 * 75 itu 75% tinggal di ganti sesuai kebutuhan
 */
$img_width = (75 / 100) * $width;
$img_height = ($img_width / $width) * $height;

//Mengubah Ukuran Image
$compressed_img = imagecreatetruecolor($img_width, $img_height);
imagecopyresampled($compressed_img, $realImages, 0, 0, 0, 0, $img_width, $img_height, $width, $height);

//Simpan Gambar Thumbnail
imagejpeg($compressed_img, $directory_compress . "compressed_" . $tgl . ".jpeg");

// //Menghapus Objek Dalam Memory
imagedestroy($realImages);
imagedestroy($compressed_img);

// //Menghapus Image Asli
unlink($file . "." . $type[1]);

echo "Sukses";
