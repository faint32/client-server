(function () {
     //Section 1 : �����Զ��尴ťʱִ�еĴ���
     var a = {
         exec: function (editor) {
             show();
         }
     },
     b = 'addpic';
     CKEDITOR.plugins.add(b, {
         init: function (editor) {
             editor.addCommand(b, a);
             editor.ui.addButton('addpic', {
                 label: '���ͼƬ',
                 icon: this.path + 'addpic.jpg',
                 command: b
             });
         }
     });
 })();