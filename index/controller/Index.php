<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;
use \app\index\model\book;
use think\Route;
class Index extends Controller//与文件名一致
{
    public function Index()
    {
        $res = Db::connect();
        // dump(book::field("publisher")->select());
        $res = book::field(['name','writer','publisher','ISBN','ims'])
        ->order('num desc')
        ->select();

        $this -> assign('bookin',$res);
        return $this ->fetch('index'); 
    }
    public function page(){
            $sc = input('search');
            $sres = book::where('name','eq',$sc)
            ->select();
            $this -> assign('booksc',$sres);
            
        return $this->fetch('page');
    
    }
    public function upload(){
        if (request()->isPost()) {
            $data = [
                'name'=>input('name'),
                'writer'=>input('writer'),
                'publisher'=>input('publisher'),
                'ISBN'=>input('isbn'),
                'ims'=>$_FILES["file"]["name"]
            ];
            if (file_exists("/thinkphp5/public/static/upload/" . $_FILES["file"]["name"]))
            {
                echo $_FILES["file"]["name"] . " 文件已经存在。 ";
                echo "<a href='upload.php'>返回</a>";
            }
            else
            {
                $upload_path = $_SERVER['DOCUMENT_ROOT']."/thinkphp5/public/static/upload/";
                // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $upload_path.$_FILES["file"]["name"])) {
                    echo "文件存储在: " . "/thinkphp5/public/static/upload/" . $_FILES["file"]["name"];
                }
                
            
            $up = new book;
            if ($up->save($data)) {
                $this->success('添加成功');
                return $this->fetch();
            }else{
                $this ->error('添加失败');
                return $this->fetch();
            }
        }
           
        }
        return $this->fetch(); 
    }
}

