function onkeyboard()//自定义运行函数 
{ 
if(window.event.keyCode=0X11+0X83) //提交表单 
{ 
  submit_save(); //添加快捷键所需运行的操作函数，即实现怎样的效果 
}else if(window.event.keyCode=0X10+0X83){ //保存
  submit_savet();
}else if(window.event.keyCode=0X1B){  //返回
   location='{gotourl}';
}

} 

