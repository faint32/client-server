<?
/*

��������zhuanxiang($src,$fx,1),ʵ��ͼƬ8������չʾ��Ч��

�������ã�$src--ԭʼͼƬ��ַ��$fx--ͼƬת��Ĭ��1��1,2--���ϣ�1��2�ң���������ͬ��3,4--ת��5,6--�£�7,8--ת��

������ʾ��ԭͼƬ��ַȷ����ȷ��$fx��1-8

*/


function zhuanxiang($src,$fx=1){

//ͼƬ�����Ϣ����

$arr=@getimagesize($src);

if(!$arr)die('ͼƬ������������');

switch($arr[2]){

case 1:$img=imagecreatefromgif($src);break;

case 2:$img=imagecreatefromjpeg($src);break;

case 3:$img=imagecreatefrompng($src);break;

default:die('��֧�ֵ�ͼƬ����');

}

//��ÿ��

$k=imagesx($img);

$g=imagesy($img);



//echo 'k:'.$k.'-g:'.$g.'<br>';

//����ͼƬ���������ݷ������ÿ��$hk,$hg



switch($fx){

//����

case 1:$hk=$k;$hg=$g;break;

case 2:$hk=$k;$hg=$g;break;

//ת��

case 3:$hk=$g;$hg=$k;break;

case 4:$hk=$g;$hg=$k;break;

//����

case 5:$hk=$k;$hg=$g;break;

case 6:$hk=$k;$hg=$g;break;

//ת��
case 7:$hk=$g;$hg=$k;break;

case 8:$hk=$g;$hg=$k;break;

default:die('�����������');
}

$img2=imagecreatetruecolor($hk,$hg);

//����ԭͼƬ��ߣ�ȡ���صõ���ֱͼ��

$wid=$k-1;

$hid=$g-1;

for($y=0;$y<$g;$y++){

//ȡһ�е�ͼ��

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

default:die('�����������ȷ');

}//end switch($fx)



}//end for($j=0;$j<$k;$j++)

}//end for($i=0;$i<$g;$i++)

//�����������ͼƬ


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