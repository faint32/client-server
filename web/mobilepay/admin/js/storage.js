$(document).ready(function(){
	  var validsub=$("#storageform").Validform({
		   ignoreHidden:true,
		   postonce:true,
		   showAllError:true,
		   tiptype:function(msg,o,cssctl){
			if(!o.obj.is("form")){//��֤��Ԫ��ʱo.objΪ�ñ�Ԫ�أ�ȫ����֤ͨ���ύ��ʱo.objΪ�ñ�����;
				var objtip=o.obj.siblings(".valid");
				cssctl(objtip,o.type);
				objtip.text(msg);
			}},
				datatype:{
			"need":function(gets,obj,curform,regxp){
				
				var need=5,numselected=0,arr_numselected;
				
					arr_numselected=curform.find("[rel='"+obj.attr("rel")+"']");
					arr_numselected.each(function(){
						
						if($(this).val() && $(this).val()!="0" && $(this).val()!="null" )
						{
							
							$(this).removeClass("valid_error");
							numselected++;
							
						}else{
							if(numselected==5)
							{
								numselected=0;
							}
							$(this).addClass("valid_error");
						
						} 
					});

					if(numselected<5){arr_numselected.addClass("valid_error");}else{arr_numselected.removeClass("valid_error");}
				return  numselected >= need ? true : "��������Ϣ��";
			},
				"phone":function(gets,obj,curform,regxp){
				/*����gets�ǻ�ȡ���ı�Ԫ��ֵ��
				  objΪ��ǰ��Ԫ�أ�
				  curformΪ��ǰ��֤�ı���
				  regxpΪ���õ�һЩ������ʽ�����á�*/

				var reg1=regxp["m"],
					reg2=/\d{2,3}-\d{5,9}/,
					mobile=curform.find(".mobile");
				//alert(mobile.val());
				if(reg1.test(mobile.val())){$(obj).removeClass("valid_error");$("#phonemsg").removeClass("valid_wrong").html("&#12288;�绰���롢�ֻ��������һ��");$("#mobilemsg").addClass("valid_right").removeClass("valid_wrong").html("����");return true;}
				if(reg2.test(gets)){return true;}
				
				return false;
			}
			
		}
			
	});

	checktime=function(obj){
				
				var need=4,numselected=0,arr_numselected,reg,msg;
				
					arr_numselected=$("[rel='worktime']");
					if($(obj).val()=="")
					{
					
					$("#workmsg").addClass("valid_wrong").removeClass("valid_right").html($(obj).attr("nullmsg"));
					$(obj).addClass("valid_error");
					
					}else{
					
					if($(obj).attr("test")=="hour"){reg=/[01][0-9]|2[0-4]/; msg="Сʱ��ʽ����ȷ";}else{reg=/[0-5][0-9]/; msg="�ָ�ʽ����ȷ";}
					if(!reg.test($(obj).val()))
					{
					$("#workmsg").addClass("valid_wrong").removeClass("valid_right").html(msg);
					$(obj).addClass("valid_error");
					}else{
					$(obj).removeClass("valid_error");
					}
					arr_numselected.each(function(){
					if($(this).attr("test")=="hour"){reg=/[01][0-9]|2[0-4]/;}else{reg=/[0-5][0-9]/;}
						if(reg.test($(this).val()))
						{	
							numselected++;
						}else{
							if(numselected==4)
							{
								numselected=0;
							}
							
						}
					});
					
					if(numselected>=4){
					$("#workmsg").addClass("valid_right").removeClass("valid_wrong").html("����");
					arr_numselected.removeClass("valid_error");
					}
	
			}
	
	}
$("#save_submit").click(function(){
	var errormsg=0;
 	 var arr_numselected=$("[rel='worktime']");
	 	arr_numselected.each(function(){
		if(!$(this).val())
		{	
			$("#workmsg").addClass("valid_wrong").removeClass("valid_right").html("������ʱ��");
			$(this).addClass("valid_error");
			errormsg=1;
		}
		});
		
			if($("#storage_endworkhour").val()<$("#storage_beginworkhour").val())
			{
				$("#workmsg").addClass("valid_wrong").removeClass("valid_right").html("�°�ʱ�䲻��С���ϰ�ʱ��");
			}else{
				if(!errormsg){$("#workmsg").addClass("valid_right").removeClass("valid_wrong").html("����");}
				validsub.submitForm(false);
			}
		
});
});

