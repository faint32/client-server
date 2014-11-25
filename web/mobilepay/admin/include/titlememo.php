<?
if($name=='内部职员设置'){
$titlememo = "title='^_^ 设置内部职员的基本资料'";
}else if($name=='客户资料'){
$titlememo = "title='^_^ 设置客户的基本资料'";
}else if($name=='供应商资料'){
$titlememo = "title='^_^ 设置供应商的基本资料'";
}else if($name=='商品资料'){
$titlememo = "title='^_^ 设置商品的基本资料'";
}else if($name=='部门设置'){
$titlememo = "title='^_^ 设置部门的基本资料'";
}else if($name=='地区设置'){
$titlememo = "title='^_^ 设置地区的基本资料'";
}else if($name=='商品类别'){
$titlememo = "title='^_^ 设置商品的类别名称'";
}else if($name=='单位设置'){
$titlememo = "title='^_^ 设置商品的单位名称'";
}else if($name=='费用类型'){
$titlememo = "title='^_^ 设置费用的类型名称'";
}else if($name=='收入类型'){
$titlememo = "title='^_^ 设置收入的类型名称'";
}else if($name=='帐户基本类型'){
$titlememo = "title='^_^ 设置银行帐户的基本资料'";
}else if($name=='仓库资料'){
$titlememo = "title='^_^ 设置仓库的基本资料'";
}else if($name=='科目设置'){
$titlememo = "title='^_^ 部分科目设定为固定值，不能增减子科目，其他的可以更改，但过帐的科目同样不能修改和删除'";
}else if($name=='期初库存商品'){
$titlememo = "title='^_^ 显示期初开帐的库存商品的数量成本价'";
}else if($name=='期初应收应付'){
$titlememo = "title='^_^ 显示期初开帐的应收应付的金额'";
}else if($name=='期初现金银行'){
$titlememo = "title='^_^ 显示期初开帐的银行存款的金额'";
}else if($name=='期初借出商品'){
$titlememo = "title='^_^ 显示期初开帐的借出商品的基本资料'";
}else if($name=='期初固定资产'){
$titlememo = "title='^_^ 显示期初开帐的固定资产的基本资料'";
}else if($name=='期初开帐'){
$titlememo = "title='^_^   在您完成了期初建账以后，必须要先进行开账处理，然后才能进行业务录入，开账之后还可以进行反开账回到期初状态，但是已经有过账单据之后就不能再反开账。'";
}else if($name=='商品合并'){
$titlememo = "title='^_^ 把原来的商品合并到另外的一个商品里，合并之前请先做好数据备份'"; 
}else if($name=='商品合并历史'){
$titlememo = "title='^_^ 显示商品合并的历史记录'";
}else if($name=='客户合并'){
$titlememo = "title='^_^ 把原来的客户合并到另外的一个客户里，合并之前请先做好数据备份'";
}else if($name=='客户合并历史'){
$titlememo = "title='^_^ 显示客户合并的历史记录'";
}else if($name=='供应商合并'){
$titlememo = "title='^_^ 把原来的供应商合并到另外的一个供应商里，合并之前请先做好数据备份'";
}else if($name=='供应商合并历史'){
$titlememo = "title='^_^ 显示供应商合并的历史记录'";
}else if($name=='其他收入合并'){
$titlememo = "title='^_^ 把原来的其他收入合并到另外的一个其他收入里，合并之前请先做好数据备份'";
}else if($name=='其他收入并历史'){
$titlememo = "title='^_^ 显示其他收入合并的历史记录'";
}else if($name=='费用合并'){
$titlememo = "title='^_^ 把原来的费用合并到另外的一个费用里，合并之前请先做好数据备份'";
}else if($name=='库存查询'){
$titlememo = "title='^_^ 仓库不同货品现存的数量，规格，品种，单价，总金额的情况'";
}else if($name=='库存商品流水帐'){
$titlememo = "title='^_^ 显示商品的来源和发生业务关系'"; 
}else if($name=='组装拆卸'){
$titlememo = "title='^_^ 对商品的组装或拆卸，注意源商品和目的商品的区别，可参考《系统说明书》'"; 
}else if($name=='组装拆卸单历史'){
$titlememo = "title='^_^ 显示所操作的组装拆卸的历史记录'";
}else if($name=='组装拆卸查询'){
$titlememo = "title='^_^ 查询并显示符合你查询条件的组装拆卸历史记录'";
}else if($name=='商品借出单'){
$titlememo = "title='^_^ 对借出的商品进行录入的过程'";
}else if($name=='商品借出历史单'){
$titlememo = "title='^_^ 显示借出的商品的历史记录'";
}else if($name=='商品归还单'){
$titlememo = "title='^_^ 对归还商品进行录入的过程'";
}else if($name=='商品归还单历史'){
$titlememo = "title='^_^ 显示归还的商品的历史记录'";
}else if($name=='借出商品查看'){
$titlememo = "title='^_^ 查询并显示借出商品的资料'";
}else if($name=='调拨单'){
$titlememo = "title='^_^ 把商品从一个仓库调到另一个仓库的录入过程，要注意单价的变化'";
}else if($name=='调拨单历史'){
$titlememo = "title='^_^ 显示调拨单的历史记录'";
}else if($name=='调拨查询'){
$titlememo = "title='^_^ 查询并显示符合你查询的调拨单历史'";
}else if($name=='滞销商品'){
$titlememo = "title='^_^ 销售量低于商品指标数的商品'";
}else if($name=='畅销商品'){
$titlememo = "title='^_^ 销售量高于商品指标数的商品'";
}else if($name=='库存上限报警'){
$titlememo = "title='^_^ 当库存商品超过你在“商品资料”设定的上限的时候，就会显示数据以示报警'";
}else if($name=='库存下限报警'){
$titlememo = "title='^_^ 当库存商品超过你在“商品资料”设定的下限的时候，就会显示数据以示报警'";
}else if($name=='库存状况分布'){
$titlememo = "title='^_^ 可以综合查询库存的分布，数量，金额等'";
}else if($name=='采购订单'){
$titlememo = "title='^_^ 采购部门对商品下订单的录入过程'";
}else if($name=='采购订单历史'){
$titlememo = "title='^_^ 显示采购订单的历史记录'";
}else if($name=='采购收货'){
$titlememo = "title='^_^ 对商品进行收货处理'";
}else if($name=='采购收货历史'){
$titlememo = "title='^_^ 显示商品收货的历史'";
}else if($name=='采购对账单'){
$titlememo = "title='^_^ 显示采购对账单'";
}else if($name=='退货处理'){
$titlememo = "title='^_^ 对商品进行退货处理'";
}else if($name=='退货历史'){
$titlememo = "title='^_^ 显示商品采购退货的历史'";
}else if($name=='销售单管理'){
$titlememo = "title='^_^ 对商品销售进行录入的过程'";
}else if($name=='销售单历史'){
$titlememo = "title='^_^ 显示销售的历史记录'";
}else if($name=='退货单管理'){
$titlememo = "title='^_^ 对商品销售退货进行处理'";
}else if($name=='退货单历史'){
$titlememo = "title='^_^ 显示商品销售退货的历史'";
}else if($name=='收款单'){
$titlememo = "title='^_^ 对各种单据进行收款的录入过程'";
}else if($name=='收款单历史'){
$titlememo = "title='^_^ 显示收款单的历史记录'";
}else if($name=='付款单'){
$titlememo = "title='^_^ 对各种单据进行付款的录入过程'";
}else if($name=='付款单历史'){
$titlememo = "title='^_^ 显示付款单的历史记录'";
}else if($name=='其他费用'){
$titlememo = "title='^_^ 对其他费用进行录入的过程'";
}else if($name=='其他费用历史单'){
$titlememo = "title='^_^ 显示其他费用的历史记录'";
}else if($name=='其他收入'){
$titlememo = "title='^_^ 其他收入的录入过程'";
}else if($name=='其他收入历史单'){
$titlememo = "title='^_^ 显示其他收入的历史记录'";
}else if($name=='帐户调拨'){
$titlememo = "title='^_^ 是银行存款、提取现金、银行转帐的录入过程'";
}else if($name=='帐户调拨历史单'){
$titlememo = "title='^_^ 显示帐户存款，提现，转帐的历史'";
}else if($name=='往来对帐单'){
$titlememo = "title='^_^ 查询并显示业务发生往来的各种单据历史'";
}else if($name=='帐户流水帐'){
$titlememo = "title='^_^  查询并显示某一帐户所发生的所有业务单据历史'";
}else if($name=='现金银行'){
$titlememo = "title='^_^ 显示不同帐户在不同时期里的帐户情况'";
}else if($name=='应收查看'){
$titlememo = "title='^_^ 查看每笔应收款的金额，并且在操作区可以明细帐'";
}else if($name=='应付查看'){
$titlememo = "title='^_^ 查看每笔应付款的金额，并且在操作区可以明细帐'";
}else if($name=='资产类别'){
$titlememo = "title='^_^ 是企业所拥有的设备，房产，材料等的固定资产类型'";
}else if($name=='资产登记'){
$titlememo = "title='^_^ 登记固定资产的规格，条码，购置日期，使用年限等等，并且在操作区可以将固定资产报废，折旧或变卖'";
}else if($name=='资产报废查看'){
$titlememo = "title='^_^ 对“资产登记”操作区的报废的历史显示'";
}else if($name=='资产折旧查看'){
$titlememo = "title='^_^ 对“资产登记”操作区的折旧的历史显示'";
}else if($name=='资产变卖记录'){
$titlememo = "title='^_^ 对“资产登记”操作区的变卖的历史显示'";
}else if($name=='报损登记'){
$titlememo = "title='^_^ 是指盘点出的商品小于账面数量或因其它原因要减少账面库存时要进行登记的录入过程'";
}else if($name=='报损单历史'){
$titlememo = "title='^_^ 显示报损的单据历史'";
}else if($name=='报溢登记'){
$titlememo = "title='^_^ 是指盘点出的商品大于账面数量或因其它原因要增加账面库存时要进行登记的录入过程'";
}else if($name=='报溢单历史'){
$titlememo = "title='^_^ 显示报溢的单据历史'";
}else if($name=='报损报溢单历史'){
$titlememo = "title='^_^ 显示报损报溢的单据历史'";
}else if($name=='商品调价'){
$titlememo = "title='^_^ 对商品进行调价处理'";
}else if($name=='调价单历史'){
$titlememo = "title='^_^ 显示调价单的历史记录'";
}else if($name=='帐户调帐'){
$titlememo = "title='^_^ 必须在“帐户基本类型”中设置银行账户才能开展工作，即对银行帐户进行金额调整'";
}else if($name=='帐户调帐历史'){
$titlememo = "title='^_^ 显示帐户调帐的历史记录'";
}else if($name=='应收款调帐增加'){
$titlememo = "title='^_^ 显示某一往来单位的应收款从少增加到多的数据'";
}else if($name=='应收款调帐减少'){
$titlememo = "title='^_^显示某一往来单位的应收款减少的数据 '";
}else if($name=='应收款调帐历史'){
$titlememo = "title='^_^ 显示应收款的调帐历史记录'";
}else if($name=='应付款调帐增加'){
$titlememo = "title='^_^ 显示某一往来单位的应付款从少增加到多的数据'";
}else if($name=='应付款调帐减少'){
$titlememo = "title='^_^显示某一往来单位的应付款减少的数据 '";
}else if($name=='应付款调帐历史'){
$titlememo = "title='^_^ 显示应付款的调帐历史记录'";
}else if($name=='经营历程'){
$titlememo = "title='^_^ 显示你所查询的业务发生单据'";
}else if($name=='其他收入查询'){
$titlememo = "title='^_^ 显示你所查询的所有其他收入的资料'";
}else if($name=='其他费用查询'){
$titlememo = "title='^_^ 显示你所查询的所有其他费用的资料'";
}else if($name=='进货分析'){
$titlememo = "title='^_^ 对采购进货的进行查询分析'";
}else if($name=='退货分析'){
$titlememo = "title='^_^ 对采购退货的进行查询分析'";
}else if($name=='销售分析'){
$titlememo = "title='^_^ 对商品销售的进行查询分析'";
}else if($name=='销售退货分析'){
$titlememo = "title='^_^ 对销售退货的进行查询分析'";
}else if($name=='商品销售排行'){
$titlememo = "title='^_^ 查询按商品按销售量的排名'";
}else if($name=='客户销售排行'){
$titlememo = "title='^_^ 查询客户按销售量的排名'";
}else if($name=='仓库销售排行'){
$titlememo = "title='^_^ 查询按销售量多少的仓库排名'";
}else if($name=='商品销售统计'){
$titlememo = "title='^_^ 对商品销售的数量和金额统计'";
}else if($name=='商品退货统计'){
$titlememo = "title='^_^ 对商品销售退货的数量和金额统计'";
}else if($name=='客户销售统计'){
$titlememo = "title='^_^ 对客户的商品销售数量和金额统计'";
}else if($name=='仓库销售分布'){
$titlememo = "title='^_^ 查询并显示不同仓库的销售情况'";
}else if($name=='资产负债表'){
$titlememo = "title='^_^ 业务发生的所有金额都会累加到里面对应科目里'";
}else if($name=='资产负债树型表'){
$titlememo = "title='^_^ 资产负债表的树型表示形式'";
}else if($name=='利润表'){
$titlememo = "title='^_^ 业务发生的所有金额都会累加到里面对应科目里'";
}else if($name=='库存进货分布'){
$titlememo = "title='^_^ 显示不同仓库的进货库存情况'";
}else if($name=='库存进货统计'){
$titlememo = "title='^_^ 显示不同商品进货的统计数'";
}else if($name=='供应商进货统计'){
$titlememo = "title='^_^ 显示供应商进货的统计数'";
}else if($name=='商品进退货统计'){
$titlememo = "title='^_^ 显示商品进退货的统计数'";
}else if($name=='供应商进退货统计'){
$titlememo = "title='^_^ 显示供应商进退货的统计数'";
}else if($name=='销售日报表'){
$titlememo = "title='^_^ 查询并显示当日的销售发生的所有单据'";
}else if($name=='销售月报表'){
$titlememo = "title='^_^ 查询并显示当月的销售发生的所有单据'";
}else if($name=='销售年报表'){
$titlememo = "title='^_^ 查询并显示当年的销售发生的所有单据'";
}else if($name=='经营日报表'){
$titlememo = "title='^_^ 查询并显示当日经营的所有单据'";
}else if($name=='进销存日报表'){
$titlememo = "title='^_^ 查询并显示当日进销存的所有单据'";
}else if($name=='个人资料'){
$titlememo = "title='^_^ 职员个人资料的录入'";
}else if($name=='修改密码'){
$titlememo = "title='^_^ 职员的密码设置和修改'";
}else if($name=='用户管理'){
$titlememo = "title='^_^ 设置用户的资料和在操作区的用户功能选择其权限'";
}else if($name=='用户组管理'){
$titlememo = "title='^_^ 设置用户组的资料和在操作区的用户组功能选择其权限'";
}else if($name=='个人设置'){
$titlememo = "title='^_^ 设置个人习惯的浏览数，页面，皮肤和菜单'";
}else if($name=='邮箱管理'){
$titlememo = "title='^_^ 从这个窗口可以直接登陆其他网站的邮箱'";
}else if($name=='快捷菜单设置'){
$titlememo = "title='^_^ 选择快捷菜单的使用权限'";
}else if($name=='系统公告设置'){
$titlememo = "title='^_^ 设置系统公告的内容'";
}else if($name=='月结存'){
$titlememo = "title='^_^ 对数据的月结，一年只能月结12次，并且不能再做月结前的帐了'";
}else if($name=='月结存查看'){
$titlememo = "title='^_^ 可以查看你所选的月份发生的所有单据'";
}else if($name=='年结存数据'){
$titlememo = "title='^_^ 操作生成年结存数据后，在下面的“年结存”功能那里可以查看生成的年结存数据'";
}else if($name=='年结存'){
$titlememo = "title='^_^ 可以查看“应收应付款”、“仓库商品”、“借出商品”、“固定资产”、“帐户金额”的年结存数据，也可以操作年结存使所有的数据结存，但在操作之前请做好数据备份'";
}else if($name=='单据编号设置'){
$titlememo = "title='^_^ 设置单据的编号'";
}else if($name=='数据备份/还原'){
$titlememo = "title='^_^ 将当前的数据备份或者还原，还可以在操作区下载，删除'";
}else{
$titlememo = "";
}
?>