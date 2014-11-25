<?php        //��PHP��������ͼƬ����ͼ  

   

    function mkdirs($dirname,$mode=0777)    //����Ŀ¼(Ŀ¼, ��ģʽ��)  

    {  

        if(!is_dir($dirname))  

        {  

            mkdirs($dirname,$mode); //���Ŀ¼������,�ݹ齨��  

            return mkdir($dirname,$mode);  

        }  

        return true;  

    }  

   

    function savefile($filename,$content='')        //�����ļ�(�ļ�, �����ݣ�)  

    {  

        if(function_exists(file_put_contents))  

        {  

            file_put_contents($filename,$content);  

        }  

        else 

        {  
       
            $fp=fopen($filename,"wb");  
            fwrite($fp,$content);  

            fclose($fp);  

        }  

    }  

   

    function getsuffix($filename)       //��ȡ�ļ�����׺  

    {  

        return end(explode(".",$filename));  

    }  

   

    function checksuffix($filename,$arr)        //�Ƿ�Ϊ��������(��ǰ, ����)  

    {  

        if(!is_array($arr))  

        {  

            $arr=explode(",",str_replace(" ","",$arr));  

        }  

        return in_array($filename,$arr) ? 1 : 0;  

    }  

   

    class image  

    {  

        var $src;           //Դ��ַ  

        var $newsrc;        //��ͼ·��(���ػ���)  

        var $allowtype=array(".gif",".jpg",".png",".jpeg");     //�����ͼƬ����  

        var $regif=0;       //�Ƿ�����GIF, Ϊ0������  

        var $keep=1;        //�Ƿ���Դ�ļ�(1Ϊ����, 0ΪMD5)  

        var $over=0;        //�Ƿ���Ը����Ѵ��ڵ�ͼƬ,Ϊ0�򲻿ɸ���  

        var $dir;           //ͼƬԴĿ¼  

        var $newdir;        //������Ŀ¼  

   

        function image($newdir=0)  

        {  
           
			$this->newdir=$newdir ? $newdir : "./images_s";  
        }  

   

        function reNames($src)  

        {  

            $md5file=date("Y").date("m").date("d").date("H").date("i").date("s").mt_rand(111,999)."thum".strrchr($src,"."); //�����ļ�����(����:3293okoe.gif)  

            
        
            return $this->newdir."/".$md5file;                   //��ԴͼƬ,MD5�ļ����󱣴浽�µ�Ŀ¼��  

        }  

   

        function Mini($src,$w,$h,$q=80)     //��������ͼ Mini(ͼƬ��ַ, ���, �߶�, ����)  

        {  

            $this->src=$src;  

            $this->w=$w;  

            $this->h=$h;  

            if(strrchr($src,".")==".gif" && $this->regif==0) //�Ƿ���GIFͼ  

            {  

                return $this->src;  

            }  

            if($this->keep==0)       //�Ƿ���Դ�ļ���Ĭ�ϲ�����  

            {  

                $newsrc=$this->reNames($src);    //��������ļ���ַ  

            }  

            else                    //����ԭ��  

            {  

                $src=str_replace("\\","/",$src);  

                $newsrc=$this->newdir.strrchr($src,"/");  

            }  

            if(file_exists($newsrc) && $this->over==0)       //����Ѵ���,ֱ�ӷ��ص�ַ  

            {  

                return $newsrc;  

            }  

            if(strstr($src,"http://") && !strstr($src,$_SERVER['HTTP_HOST']))//����������ļ�,�ȱ���  

            {  

                $src=$this->getimg($src);  

            }  

            $arr=getimagesize($src);    //��ȡͼƬ����  

            $width=$arr[0];  

            $height=$arr[1];  

            $type=$arr[2];  

            switch($type)  

            {  

                case 1:     //1 = GIF��  

                    $im=imagecreatefromgif($src);  

                    break;  

                case 2:     //2 = JPG  

                    $im=imagecreatefromjpeg($src);  

                    break;  

                case 3:     //3 = PNG  

                    $im=imagecreatefrompng($src);  

                    break;  

                default:  

                    return 0;  

            }  

   

            //��������ͼ  

            $nim=imagecreatetruecolor($w,$h);  

            $k1=round($h/$w,2);  

            $k2=round($height/$width,2);  

            if($k1<$k2)  

            {  

                $width_a=$width;  

                $height_a=round($width*$k1);  

                $sw=0;  

                $sh=($height-$height_a)/2;  

   

            }  

            else 

            {  

                 $width_a=$height/$k1;  

                 $height_a=$height;  

                 $sw=($width-$width_a)/2;  

                 $sh = 0;  

            }  

   

            //����ͼƬ  

            if(function_exists(imagecopyresampled))  

            {  

                imagecopyresampled($nim,$im,0,0,$sw,$sh,$w,$h,$width_a,$height_a);  

            }  

            else 

            {  

                imagecopyresized($nim,$im,0,0,$sw,$sh,$w,$h,$width_a,$height_a);  

            }  

            if(!is_dir($this->newdir))  

            {  

                mkdir($this->newdir);  

            }  

   

            switch($type)       //����ͼƬ  

            {  

                case 1:  

                    $rs=imagegif($nim,$newsrc);  

                    break;  

                case 2:  

                    $rs=imagejpeg($nim,$newsrc,$q);  

                    break;  

                case 3:  

                    $rs=imagepng($nim,$newsrc);  

                    break;  

                default:  

                    return 0;  

            }  

            return $newsrc;     //���ش����·��  

        }  

   

        function getimg($filename)  

        {  

            $md5file=$this->dir."/".substr(md5($filename),10,10).strrchr($filename,".");  

            if(file_exists($md5file))  

            {  

                return $md5file;  

            }  

            //��ʼ��ȡ�ļ�,��������·��  

            $img=file_get_contents($filename);  

            if($img)  

            {  

                if(!is_dir($this->dir))  

                {  

                    mkdir($this->dir);  

                }  

                savefile($md5file,$img);  

                return $md5file;  

            }  

        }  

   

        function reImg($src,$w,$h,$q)   //ת������ͼ(�ļ����ͽṹ����)  

        {  

            $this->keep=0;  

            return $this->Mini($src,$w,$h,$q);       //return ���ɵĵ�ַ  

        }  

   

    }  

/*
 //Ҫ��ȡ��Ŀ¼
 $folder="./old";
 $folder2="./images_s";

 //��Ŀ¼
$fp=opendir($folder);
$fp2=opendir($folder2);

 //�Ķ�Ŀ¼
while(false!=$file=readdir($fp))
{
//�г������ļ���ȥ��'.'��'..'
    if($file!='.' &&$file!='..')
    {
        //$file="$folder/$file";
        $file="$file";

        //��ֵ������
        $arr_file[]=$file;

        }
}
 //������
 if(is_array($arr_file))
 {
    while(list($key,$value)=each($arr_file))
    {
        //echo "$value<br>";
    $image=new image();  

    echo $image->reImg("old/$value",220,180,80);  
    echo "<br>";
    }

   }



closedir($fp);

*//*
    $image=new image();  

   echo $image->reImg("old/shower_03.jpg",100,80,40);  

*/







?> 
