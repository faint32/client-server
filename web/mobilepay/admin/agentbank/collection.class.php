<?php
/*
* 本类是一个创建文件TXT的一个类文件
*/
class Collection
{
    public $batchNumber = '';//批次号
    public $collectionNumber = '';//代收号
    public $ersionNumber = '';//版本号
    public $merchantId = '';//商户ID//银联网络分配
    public $recordsAll = 0;//总记录数
    public  $amountAll = 0;//总金额
    public $businessType = '';//业务类型

    /**
     * 获取文件内容
     */
    public function GetContent( $fileData )
    {
        $data = '';
        $this->batchNumber = $this->CreatePassword(5);//随机生成批次号
        $name = $this->merchantId . "_" . $this->collectionNumber . $this->ersionNumber . date("Ymd") . "_" . $this->batchNumber . ".txt";//创建文件名
        $data = $this->collectionNumber.",".$this->merchantId.",".date("Ymd").",".$this->recordsAll.",".$this->amountAll.",".$this->businessType."\r\n";
        $data .= $fileData;
        $data = (string)$data;
        $this->batchNumber  = '';
        $this->ExportTxt( $name , $data );
    }

    /**
     * 创建文件TXT
     */
    public function CreatFile($data)
    {
        $name = $this->merchantId . "_" . $this->collectionNumber . $this->ersionNumber . date("Ymd") . "_" . $this->batchNumber . ".txt";//创建文件名
        $fp = fopen( date("Ymd") . "/" . $name , "a+" );
        if ( !$fp )
        {
            echo "system error";
            exit();
        }
        else
        {
            $fileData = $this->collectionNumber.",".$this->merchantId.",".date("Ymd").",".$this->recordsAll.",".$this->amountAll.",".$this->businessType."\r\n";
            $fileData = $fileData.$data;
            fwrite( $fp , $fileData );
            fclose( $fp );
        }
    }

    /**
     * 创建文件夹
     */
    public function CreateFolder( $path )
    {
        if ( !file_exists($path) )
        {
            $this ->createFolder( dirname( $path ) );
            mkdir( $path , 0777 );
        }
    }

    /**
     * 随机创建数字字符串
     */
    public function CreatePassword( $length = 8 )
    {
        $randpwd = '';
        for ($i = 0; $i < $length; $i ++) 
        {
            $randpwd .= chr(mt_rand(48, 57));
        }
        return $randpwd;
    }

    /**
     * 导出TXT文件
     */
    public function ExportTxt( $filename , $data )
    {
        header("Content-type:text/txt");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
    }
}
?>