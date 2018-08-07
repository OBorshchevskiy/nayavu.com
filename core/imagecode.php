<?
# стартовать сессию только тем, у кого уже стартовала сессия
if (isset($_REQUEST[session_name()])) {
session_start();
}

header("Content-type: image/png");

# создаем изображение
$im=imagecreate(151,26);

# выделяем цвет фона(белый)
$w=imagecolorallocate($im,255,255,255);

# выделяем цвет для фона(светло-серый)
$g1=imagecolorallocate($im,192,192,192);

# выделяем цвет для более темных помех(темно-серый)
$g2=imagecolorallocate($im,172,172,172);

# выделяем четыре случайных темных цвета для символов
$cl1=imagecolorallocate($im,rand(0,128),rand(0,128),rand(0,128));
$cl2=imagecolorallocate($im,rand(0,128),rand(0,128),rand(0,128));
$cl3=imagecolorallocate($im,rand(0,128),rand(0,128),rand(0,128));
$cl4=imagecolorallocate($im,rand(0,128),rand(0,128),rand(0,128));
$cl5=imagecolorallocate($im,rand(0,128),rand(0,128),rand(0,128));
$cl6=imagecolorallocate($im,rand(0,128),rand(0,128),rand(0,128));

# рисуем сетку
for ($i=0;$i<=151;$i+=5) imageline($im,$i,0,$i,25,$g1);
for ($i=0;$i<=25;$i+=5) imageline($im,0,$i,151,$i,$g1);

# получаем имя сессии
$secret_number_name = $_GET['secret_number_name'];

# выводим каждую цифру по отдельности, немного смещая случайным образом
imagestring($im, 5, 0+rand(0,10), 5+rand(-5,5), substr($_SESSION["$secret_number_name"],0,1), $cl1);
imagestring($im, 5, 25+rand(-10,10), 5+rand(-5,5), substr($_SESSION["$secret_number_name"],1,1), $cl2);
imagestring($im, 5, 50+rand(-10,10), 5+rand(-5,5), substr($_SESSION["$secret_number_name"],2,1), $cl3);
imagestring($im, 5, 75+rand(-10,10), 5+rand(-5,5), substr($_SESSION["$secret_number_name"],3,1), $cl4);
imagestring($im, 5, 100+rand(-10,10), 5+rand(-5,5), substr($_SESSION["$secret_number_name"],4,1), $cl5);
imagestring($im, 5, 125+rand(-10,10), 5+rand(-5,5), substr($_SESSION["$secret_number_name"],5,1), $cl6);

# выводим пару случайных линий темного цвета, прямо поверх символов
for ($i=0;$i<5;$i++) imageline($im,rand(0,100),rand(0,25),rand(0,100),rand(0,25),$g2);

# коэфициент увеличения/уменьшения картинки
$k=1.7;

# cоздаем новое изображение, увеличенного размера
$im1=imagecreatetruecolor(151*$k,26*$k);

# копируем изображение с изменением рамеров в большую сторону
imagecopyresized($im1, $im, 0, 0, 0, 0, 151*$k, 26*$k, 151, 26);

# создаем новое изображение, нормального размера
$im2=imagecreatetruecolor(151,26);

# копируем изображение с изменением рамеров в меньшую сторону
imagecopyresampled($im2, $im1, 0, 0, 0, 0, 151, 26, 151*$k, 26*$k);

# генерируем изображение
imagepng($im2);

# освобождаем память
imagedestroy($im2);
imagedestroy($im1);
imagedestroy($im);
?>
