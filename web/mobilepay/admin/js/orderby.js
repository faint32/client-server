			function changeorderby(obj,count)
			{
				if(count !=0 && count !=1)
				{
					$("#showimg1").remove();
					$("#showimg0").remove();
				}
				if(count==0 || count==1 )
				{
					if(count==0)
					{
						$("#showimg1").remove();
					}else{
						$("#showimg0").remove();
					}
					if($(obj).attr("class") != 'orderbydown sorting_asc')
					{
						if($("#showimg"+count).attr("src"))
						{
							$("#showimg"+count).attr("src",'../skin/skin0/images/btn_up.gif');
						}else{
							$(obj).append("<img src='../skin/skin0/images/btn_up.gif' id='showimg"+count+"'></img>");
						}
						$(obj).addClass("orderbydown").removeClass("orderbyup");
						
						
					}else{
						$(obj).addClass("orderbyup").removeClass("orderbydown");
						$("#showimg"+count).attr("src",'../skin/skin0/images/btn_kz.gif');
						
					}
				}
			}