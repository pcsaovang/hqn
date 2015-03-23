<?php
//session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
{
?>
<script type="text/javascript">
$(document).ready(function () {
     var theme = "energyblue";
     var source = {
     	datatype: "json",
     	datafields: [
     		{name: 'UserId', type: 'string'},
     		{name: 'VoucherDate', type: 'date'},
     		{name: 'VoucherTime', type: 'string'},
     		{name: 'Amount', type: 'number'},
     		{name: 'SafftId', type: 'string'},
     		{name: 'Note', type: 'string'},
     	],
     	url: "payment.php",
     	data: {action: 'viewreport'},
     	cache: false,
     	root: 'Rows',
     	beforeprocessing: function(data){
     		source.totalrecords = data[0].TotalRows;
               source.totalmoney = data[0].TotalMoney;
               source.totalservice = data[0].TotalService;
               source.totaldebit = data[0].TotalDebit;
               source.totalundebit = data[0].TotalUnDebit;
     	},
     	filter: function(){
     		$("#jqxgird").jqxGrid('updatebounddata', 'filter');
     	},
     };

     var formatdate = function(value){
          var d = new Date(value);
          var d1 = d.getFullYear();
          var d2 = d.getMonth() + 1;
          var d3 = d.getDate();
          var h = d.getHours();
          var m = d.getMinutes();
          var s = d.getSeconds();
          if(h < 10) h = '0' + h;
          if(m < 10) m = '0' + m;
          if(s < 10) s = '0' + s;
          if(d2 < 10) d2 = '0' + d2;
          if(d3 < 10) d3 = '0' + d3;
          return d1 + "-" + d2 + "-" + d3 + " " + h + ":" + m + ":" + s;
     };

     var localizationObject = {
          pagergotopagestring: ["Đi đến trang:"],
          pagershowrowsstring: ["Hiện mẩu tin:"],
          currencysymbol: [" đ"],
          currencysymbolposition: ["after"],
          thousandsseparator: [","],
          emptydatastring: 'Không có dữ liệu',
          clearstring: 'Hủy',
          todaystring: 'Hôm nay',
          loadtext: 'Đang tải...'
     };

     var dataAdapter = new $.jqx.dataAdapter(source, {
          loadComplete: function(data){
               $("#jqxgird").jqxGrid({
                    renderstatusbar: function(statusbar){
                         var container = $("<div style='overflow: hidden; position: relative; margin: 5px; font-weight:bold;'></div>");
                         var spantotalmoney = $("<span style='padding-right: 20px;'>Tổng doanh thu: <label style='color: red;'>"+ source.totalmoney +"</label></span>");
                         var spantotalservice = $("<span style='padding-right: 20px;'>Doanh thu dịch vụ: <label style='color: red;'>"+source.totalservice+"</label></span>");
                         var spantotaldebit = $("<span style='padding-right: 20px;'>Khách nợ: <label style='color: red;'>"+source.totaldebit+"</label></span>");
                         var spantotalundebit = $("<span>Trả nợ: <label style='color: red;'>"+source.totalundebit+"</label></span>");
                         container.append(spantotalmoney);
                         container.append(spantotalservice);
                         container.append(spantotaldebit);
                         container.append(spantotalundebit);
                         statusbar.html(container);
                    }
               });
          }
     });

     $("#jqxgird").jqxGrid({
     	width: 970,
     	autoheight: true,
     	source: dataAdapter,
     	theme: theme,
     	pageable: true,
     	pagesize: 20,
          sortable: false,
     	filterable: false,
     	showfilterrow: false,
          showfiltercolumnbackground: false,
     	virtualmode: true,
          showstatusbar: true,
     	showtoolbar: true,
     	toolbarheight: 40,
          statusbarheight: 35,
     	rendertoolbar: function(toolbar){
     		var me = this;
     		var fd = new Date();
			var d = fd.getDate();
			var m = fd.getMonth() +1 ;
			var fy = fd.getFullYear();
			var dt = m + "-" + d + "-" + fy + " 23:59:59";

			var source = {
				datatype: "json",
				datafields: [{name: 'employeeId'}, {name: 'employeeName'}],
				url: "employee.php",
				async: false
			};
			var dataAdapter = new $.jqx.dataAdapter(source);

     		var container = $("<div style='margin: 5px;'></div>");
     		var lbl1 = $("<div style='float: left; padding: 5px 5px 0 0;''><label>Từ: </label></div>");
     		var datefrom = $("<div id='fromdate' style='float: left; margin 0 auto; padding-right: 10px;'></div>");
     		var lbl2 = $("<div style='float: left; padding: 5px 5px 0 0;''><label>Đến: </label></div>")
     		var dateto = $("<div id='todate' style='float: left; margin 0 auto; padding-right: 10px;'></div>");

     		var lbl3 = $("<div style='float: left; padding: 5px 10px 0 0;'><label>NV: </label></div>");
     		var employee = $("<div id='employee' style='float: left; margin: 0 auto;'></div>");
               var member = $("<input type='text' id='txtsearchname' style='float: left; margin-left: 10px;'>");

     		var reportButton = $("<div style='float: left; margin 0 auto; margin-left: 5px;'><img style='position: relative; margin-top: 2px;' src='images/catalogicon.png' width='14' height='14' /><span style='margin-left: 4px; position: relative; top: -3px;'>Xem</span></div>");
               var serviceButton = $("<div style='float: left; margin 0 auto; margin-left: 5px;'><img style='position: relative; margin-top: 2px;' src='images/services-icon.png' width='14' height='14' /><span style='margin-left: 4px; position: relative; top: -2px;'>Dịch vụ</span></div>");
               var optionButton = $("<div style='float: left; margin 0 auto; margin-left: 5px;'><img style='position: relative; margin-top: 2px;' src='images/icon-delete.png' width='14' height='14' /><span style='margin-left: 4px; position: relative; top: -2px;'>Xử lý</span></div>");

     		container.append(lbl1);
     		container.append(datefrom);
     		container.append(lbl2);
     		container.append(dateto);
     		container.append(lbl3);
     		container.append(employee);
               container.append(member);
     		container.append(reportButton);
               container.append(serviceButton);
               container.append(optionButton);
     		toolbar.append(container);

     		datefrom.jqxDateTimeInput({width: 170, height: 25, theme: theme, formatString: 'dd-MM-yyyy HH:mm:ss', showFooter: true});
     		dateto.jqxDateTimeInput({width: 170, height: 25, theme: theme, formatString: 'dd-MM-yyyy HH:mm:ss', value: dt, showFooter: true});
               member.jqxInput({placeHolder: 'Tìm tên', width: 100, height: 22, theme: theme});

			employee.jqxComboBox({
				source: dataAdapter,
				theme: theme,
				width: 100,
				height: 22,
				selectedIndex: 0,
				displayMember: 'employeeName',
				valueMember: 'employeeId'
			});
			reportButton.jqxButton({width: 80, height: 16, theme: theme});
               serviceButton.jqxButton({width: 80, height: 16, theme: theme});
               optionButton.jqxButton({width: 80, height: 16, theme: theme});
               
               reportButton.click(function(event){
                    var a = datefrom.jqxDateTimeInput('value');
                    var b = dateto.jqxDateTimeInput('value');
                    var dfrom = formatdate(a);
                    var dto = formatdate(b);
                    var cIndex = employee.jqxComboBox('getSelectedIndex');
                    var c = employee.jqxComboBox('getItem', cIndex);
                    var mb = member.jqxInput('val');

                    var filtergroup = new $.jqx.filter();
                    var from_filter = filtergroup.createfilter('stringfilter', dfrom, 'contains');
                    var to_filter = filtergroup.createfilter('stringfilter', dto, 'contains');
                    var safft_filter = filtergroup.createfilter('numericfilter', c.value, 'contains');
                    var member_filter = filtergroup.createfilter('stringfilter', mb, 'contains');

                    filtergroup.addfilter(1, from_filter);
                    filtergroup.addfilter(1, to_filter);
                    filtergroup.addfilter(1, safft_filter);
                    filtergroup.addfilter(1, member_filter);
                    
                    $("#jqxgird").jqxGrid('addfilter', 'VoucherDate', filtergroup);
                    $("#jqxgird").jqxGrid('applyfilters');
               });
               serviceButton.click(function(event){
                    var a = datefrom.jqxDateTimeInput('value');
                    var b = dateto.jqxDateTimeInput('value');
                    var dfrom = formatdate(a);
                    var dto = formatdate(b);
                    var cIndex = employee.jqxComboBox('getSelectedIndex');
                    var c = employee.jqxComboBox('getItem', cIndex);
                    showdetail(dfrom, dto, c.value);
                    $("#dialogview").jqxWindow({position: 'center'});
                    $("#dialogview").jqxWindow('open');
               });
               optionButton.click(function(event){
                    var a = datefrom.jqxDateTimeInput('value');
                    var b = dateto.jqxDateTimeInput('value');
                    var dfrom = formatdate(a);
                    var dto = formatdate(b);
                    $("#fromtodate").text(dfrom+" đến "+dto);
                    $("#popupWindow").jqxWindow({position: 'center'});
                    $("#popupWindow").jqxWindow('show');
               });
     	},
     	ready: function(data){
     		$("#jqxgird").jqxGrid('localizestrings', localizationObject);
     	},
     	rendergridrows: function(params){
     		return params.data;
     	},
     	columns: [
     		{text: 'Tên', datafield: 'UserId', width: 150},
     		{text: 'Ngày nạp', datafield: 'VoucherDate', width: 100, cellsformat: 'yyyy-MM-dd', filtertype: 'date'},
     		{text: 'Giờ nạp', datafield: 'VoucherTime', width: 100},
     		{text: 'Số tiền', datafield: 'Amount', width: 120, cellsformat: 'c', cellsalign: 'right'},
     		{text: 'Nhân viên', datafield: 'SafftId', width: 110},
     		{text: 'Ghi chú', datafield: 'Note', width: 390 },
     	]
     });
     $("#popupWindow").jqxWindow({width:450, height:170, resizable: false, theme: theme, isModal: true, autoOpen: false, cancelButton: $("#Cancel"), modalOpacity: 0.9});
     $("#del").jqxButton({theme: theme, width: 150});
     $("#cancel").jqxButton({theme: theme, width: 150});
     $("#del").click(function(){
          var a = $("#fromdate").jqxDateTimeInput('value');
          var b = $("#todate").jqxDateTimeInput('value');
          var dfrom = formatdate(a);
          var dto = formatdate(b);

          $.ajax({
               url: "payment.php",
               data:{action: 'del22h', f: dfrom, t: dto},
               cache: false,
               success: function(data){
                    $("#popupWindow").jqxWindow('hide');
                    $("#msg").html(data);
                    $("#message").jqxWindow({position: 'center'});
                    $("#message").jqxWindow('show');
                    $("#jqxgird").jqxGrid('updatebounddata');
               },
               error: function(){
                    $("#msg").html("Có lổi xẩy ra.");
               }
          });
     });
     $("#cancel").click(function(){
          $("#popupWindow").jqxWindow('hide');
     });

     $("#message").jqxWindow({width: 300, height: 110, resizable: false, theme: theme, isModal: true, autoOpen: false, modalOpacity: 0.9});
     $("#btnok").jqxButton({theme: theme, width: 150});
     $("#btnok").click(function(){
          $("#message").jqxWindow('hide');
     });

     $("#dialogview").jqxWindow({width: 730, resizable: false, theme: theme, isModal: true, autoOpen: false, modalOpacity: 0.9});

     $("#jqx-gird-service").on('bindingcomplete', function(event){
          var a = $("#jqx-gird-service").height();
          $("#dialogview").jqxWindow({height: a + 40, position: 'center'});
     });

     $("#jqx-gird-service").on('pagechanged', function(){
          var datainfo = $("#jqx-gird-service").jqxGrid('getdatainformation');
          var paginginfo = datainfo.paginginformation;
          self.label.text(1 + paginginfo.pagenum * paginginfo.pagesize + "-" + Math.min(datainfo.rowscount, (paginginfo.pagenum + 1) * paginginfo.pagesize) + ' of ' + datainfo.rowscount);
     });

     var pagerrenderer = function(){
          var element = $("<div style='margin-left: 10px; margin-top: 5px; width: 100%; height: 100%;'></div>");
          var datainfo = $("#jqx-gird-service").jqxGrid('getdatainformation');
          var paginginfo = datainfo.paginginformation;
          var leftButton = $("<div style='padding: 0px; float: left;'><div style='margin-left: 9px; width: 16px; height: 16px;'></div></div>");
          leftButton.find('div').addClass('jqx-icon-arrow-left');
          leftButton.width(36);
          leftButton.jqxButton({theme: theme});
          var rightButton = $("<div style='padding: 0px; margin: 0px 3px; float: left;'><div style='margin-left: 9px; width: 16px; height: 16px;'></div></div>");
          rightButton.find('div').addClass('jqx-icon-arrow-right');
          rightButton.width(36);
          rightButton.jqxButton({theme: theme});

          leftButton.appendTo(element);
          rightButton.appendTo(element);

          var label = $("<div style='font-size: 11px; margin: 2px 3px; font-weight: bold; float: left;'></div>");
          label.text("1-" + paginginfo.pagesize + ' of ' + datainfo.rowscount);
          label.appendTo(element);
          self.label = label;

          var handleStates = function(event, button, className, add){
               button.on(event, function(){
                    if(add = true) button.find('div').addClass(className);
                    else button.find('div').removeClass(className);
               });
          }
          if(theme != '')
          {
               handleStates('mousedown', rightButton, 'jqx-icon-arrow-right-selected-' + theme, true);
               handleStates('mouseup', rightButton, 'jqx-icon-arrow-right-selected-' + theme, false);
               handleStates('mousedown', leftButton, 'jqx-icon-arrow-left-selected-' + theme, true);
               handleStates('mouseup', leftButton, 'jqx-icon-arrow-left-selected-', theme, false);

               handleStates('mouseenter', rightButton, 'jqx-icon-arrow-right-hover-' + theme, true);
               handleStates('mouseleave', rightButton, 'jqx-icon-arrow-right-hover-' + theme, false);
               handleStates('mouseenter', leftButton, 'jqx-icon-arrow-left-hover-' + theme, true);
               handleStates('mouseleave', leftButton, 'jqx-icon-arrow-left-hover-' + theme, false);
          }
          rightButton.click(function(){
               $("#jqx-gird-service").jqxGrid('gotonextpage');
          });
          leftButton.click(function(){
               $("#jqx-gird-service").jqxGrid('gotoprevpage');
          });
          return element;
     }

     function showdetail(a, b, c){
          var source = {
               datatype: "json",
               datafields: [
                    {name: 'ServiceDate', type: 'date'},
                    {name: 'ServiceTime', type: 'string'},
                    {name: 'ServiceQuantity', type: 'number'},
                    {name: 'ServiceAmount', type: 'number'},
                    {name: 'ServiceName', type: 'string'},
                    {name: 'ServicePrice', type: 'number'},
                    {name: 'FirstName', type: 'string'},
               ],
               url: "payment.php",
               cache: false,
               data: {action: 'viewservice', fromdate: a, todate: b, emp: c}
          };
          dataAdapter = new $.jqx.dataAdapter(source);
          $("#jqx-gird-service").jqxGrid({
               width: '100%',
               autoheight: true,
               pageable: true,
               pagesize: 15,
               pagerrenderer: pagerrenderer,
               source: dataAdapter,
               theme: theme,
               ready: function(){
                    $("#jqx-gird-service").jqxGrid('localizestrings', localizationObject);
               },
               columns: [
                    {text: 'Ngày', datafield: 'ServiceDate', width: 100, cellsformat: 'yyyy-MM-dd'},
                    {text: 'Giờ', datafield: 'ServiceTime', width: 100},
                    {text: 'Tên hàng', datafield: 'ServiceName', width: 150},
                    {text: 'Số lượng', datafield: 'ServiceQuantity', width: 70, cellsalign: 'right'},
                    {text: 'Đơn giá', datafield: 'ServicePrice', width: 90, cellsformat: 'c', cellsalign: 'right'},
                    {text: 'Thành tiền', datafield: 'ServiceAmount', width: 110, cellsformat: 'c', cellsalign: 'right'},
                    {text: 'Nhân viên', datafield: 'FirstName', width: 100}
               ],
          });
     }
});
</script>
<div id="jqxgird"></div>
<div id="dialogview">
     <div>Nhật ký bán dịch vụ</div>
     <div style="overflow: hidden; background-color: #e0e9f5;">
          <div id="jqx-gird-service"></div>
     </div>
</div>
<div id="popupWindow">
     <div>Xử lý nhật ký giao dịch</div>
     <div style="overflow: hidden; text-align: center;">
          <p>Xử lý các nhật ký giao dịch từ 22h đến 8h hôm sau</p>
          <p>Trong khoản thời gian từ</p>
          <p><span id="fromtodate" style="font-weight:bold;"></span></p>
          <button id="del">Có</button>
          <button id="cancel">Không</button>
     </div>
</div>
<div id="message">
     <div>Thông báo</div>
     <div style="overflow: hidden; text-align: center;">
          <p><span id="msg" style="font-weight:bold;"></span></p>
          <button id="btnok">OK</button>
     </div>
</div>
<?php
}
else
{
     echo "<h1 align='center'>Truy cập bất hợp pháp hả?</h1>";
}
?>