<?
/*

函数名：zhuanxiang($src,$fx,1),实现图片8个方向展示的效果

参数设置：$src--原始图片地址；$fx--图片转向，默认1，1,2--向上（1左2右，其他组相同）3,4--转左，5,6--下，7,8--转右

错误提示：原图片地址确保正确，$fx：1-8

*/


function zhuanxiang($src,$fx=1){

//图片相关信息数组

$arr=@getimagesize($src);

if(!$arr)die('图片参数存在问题');

switch($arr[2]){

case 1:$img=imagecreatefromgif($src);break;

case 2:$img=imagecreatefromjpeg($src);break;

case 3:$img=imagecreatefrompng($src);break;

default:die('不支持的图片类型');

}

//获得宽高

$k=imagesx($img);

$g=imagesy($img);



//echo 'k:'.$k.'-g:'.$g.'<br>';

//创建图片画布，根据方向，设置宽高$hk,$hg



switch($fx){

//向上

case 1:$hk=$k;$hg=$g;break;

case 2:$hk=$k;$hg=$g;break;

//转左

case 3:$hk=$g;$hg=$k;break;

case 4:$hk=$g;$hg=$k;break;

//向下

case 5:$hk=$k;$hg=$g;break;

case 6:$hk=$k;$hg=$g;break;

//转右
case 7:$hk=$g;$hg=$k;break;

case 8:$hk=$g;$hg=$k;break;

default:die('方向参数错了');
}

$img2=imagecreatetruecolor($hk,$hg);

//根据原图片宽高，取像素得到垂直图像

$wid=$k-1;

$hid=$g-1;

for($y=0;$y<$g;$y++){

//取一行的图像

for($x=0;$x<$k;$x++){

switch($fx){

case 1:imagecopyresized($img2,$img,$x,$y,$x,$y,1,1,1,1);break;

case 2:imagecopyresized($img2,$img,$wid-$x,$y,$x,$y,1,1,1,1);break;

case 3:imagecopyresized($img2,$img,$hid-$y,$x,$x,$y,1,1,1,1);break;

case 4:imagecopyresized($img2,$img,$hid-$y,$wid-$x,$x,$y,1,1,1,1);break;

case 5:imagecopyresized($img2,$img,$wid-$x,$hid-$y,$x,$y,1,1,1,1);break;

case 6:imagecopyresized($img2,$img,$x,$hid-$y,$x,$y,1,1,1,1);break;

case 7:imagecopyresized($img2,$img,$y,$wid-$x,$x,$y,1,1,1,1);break;

case 8:imagecopyresized($img2,$img,$y,$x,$x,$y,1,1,1,1);break;

default:die('方向参数不正确');

}//end switch($fx)



}//end for($j=0;$j<$k;$j++)

}//end for($i=0;$i<$g;$i++)

//根据类型输出图片


switch($arr[2]){

case 1://header('content-type:image/gif');
    imagegif($img2,$src,100);break;

case 2://header('content-type:image/jpeg');
    imagejpeg($img2,$src,100);break;

case 3://header('content-type:image/png');
   imagepng($img2,$src,100);break;

}
 

}




?>