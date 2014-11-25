Ext.onReady(function(){   
    Ext.QuickTips.init();  
    // Create a variable to hold our EXT Form Panel.    
    // Assign various config options as seen.       
    var login = new Ext.FormPanel({    
        labelWidth:80,   
        url:'login.php',    
        frame:true,    
        title:'系统登录',    
        defaultType:'textfield',   
        monitorValid:true,  
        items:[{    
                fieldLabel:'帐号',    
                name:'username',
				
                allowBlank:false    
            },{    
                fieldLabel:'密码',
                name:'password',    
                inputType:'password',    
                allowBlank:false    
            }], 
		 keys: [{
         key: 13,//即为回车
        fn:fnlogin
		}], 
          buttons:[{  
				text:'登录',
                formBind: true,     
                // Function that fires when user clicks the button    
                handler:function(){    
                    fnlogin();
                }}
				]    
       });   
    
      function fnlogin() 
       { 
          login.getForm().submit({    
                        method:'POST',    
                        waitTitle:'连接',    
                        waitMsg:'传送数据中...',   
                        success:function(form, action){
							obj = Ext.util.JSON.decode(action.response.responseText);    
						        if (obj.notice.chgpass=="{1}")
								{
									var redirect = '../updatepass/updatepass.php';
									window.location = redirect;
                                
								}else
								{
									var redirect = '../loadpopup.htm';    
                                    window.location = redirect; 
								}
                        },   
                        failure:function(form, action){    
                            if(action.failureType == 'server'){    
                                obj = Ext.util.JSON.decode(action.response.responseText);    
                                Ext.Msg.alert('登录失败!', obj.errors.reason);    
                            }else{    
                                Ext.Msg.alert('警告!', '不可用 : ' + action.response.responseText);    
                            }    
                            login.getForm().reset();    
                        }    
                    });    
         } 
    // This just creates a window to wrap the login form.    
    // The login object is passed to the items collection.          
    var win = new Ext.Window({   
        layout:'fit',   
        width:300,   
        height:150,   
        closable: false,   
        resizable: false,   
        plain: true,   
        border: false,   
        items: [login]   
    });   
    win.show();   
});  
