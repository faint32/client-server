function onkeyboard()//�Զ������к��� 
{ 
if(window.event.keyCode=0X11+0X83) //�ύ�� 
{ 
  submit_save(); //��ӿ�ݼ��������еĲ�����������ʵ��������Ч�� 
}else if(window.event.keyCode=0X10+0X83){ //����
  submit_savet();
}else if(window.event.keyCode=0X1B){  //����
   location='{gotourl}';
}

} 

