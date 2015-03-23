<?php
//session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
{
?>
<script type="text/javascript">
$(document).ready(function(){
	var theme = "energyblue";
	var source = {
		datatype: "json",
		datafields: [
			{name: 'URLId', type: 'string'},
			{name: 'URL', type: 'string'},
			{name: 'RecordDate', type: 'string'},
			{name: 'UserId', type: 'number'},
			{name: 'Machine', type: 'string'}
		],
		url: "history.php",
		data: {action: 'web'},
		cache: false,
		root: "Rows",
		beforeprocessing: function(data){
			source.totalrecords = data[0].TotalRows;
		},
		filter: function(){
			$("#jqx-grid-web").jqxGrid('updatebounddata', 'filter');
		},
		deleterow: function(rowid, commit){
			
		}
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
     };

    var linkrenderer = function(row, column, value)
    {
    	if(value.indexOf('#') != -1) value = value.substring(0, value.indexOf('#'));
    	var format = {target: '"_blank"'};
    	var html = $.jqx.dataFormat.formatlink(value, format);
    	return html;
    }

	var dataAdapter = new $.jqx.dataAdapter(source);
	$("#jqx-grid-web").jqxGrid({
		width: 970,
		autoheight: true,
		source: dataAdapter,
		theme: theme,
		showfiltercolumnbackground: false,
		sortable: false,
		pageable: true,
		pagesize: 20,
		pagesizeoptions: ['10', '20', '30', '50', '100', '200', '500'],
		virtualmode: true,
		showtoolbar: true,
		rendertoolbar: function(toolbar){
			var container = $("<div style='margin: 5px;'></div>");
			var cbodatetime = $("<div id='cbodatetime' style='float: left; margin 0 auto; margin-right: 10px;'></div>");
			var filterwebButton = $("<div style='float: left; margin 0 auto; margin-right: 10px;'><img style='position: relative; margin-top: 2px;' src='images/search.png' width='14' height='14' /><span style='margin-left: 4px; position: relative; top: -3px;'>Xem</span></div>");
			var delwebButton = $("<div style='float: left; margin 0 auto; margin-right: 10px;'><img style='position: relative; margin-top: 2px;' src='images/icon-delete.png' width='14' height='14' /><span style='margin-left: 4px; position: relative; top: -3px;'>Xóa</span></div>");
			var optionButton = $("<div style='float: right; margin 0 auto;'><img style='position: relative; margin-top: 2px;' src='images/add.png' width='14' height='14' /></div>");
			container.append(cbodatetime);
			container.append(filterwebButton);
			container.append(delwebButton);
			container.append(optionButton);
			toolbar.append(container);
			filterwebButton.jqxButton({width: 80, height: 16, theme: theme});
			delwebButton.jqxButton({width: 80, height: 16, theme: theme});
			optionButton.jqxButton({width: 40, height: 16, theme: theme});
			var sourcedatetime = new Array("Hôm nay", "Tuần trước", "Tháng trước", "Tất cả");
			cbodatetime.jqxComboBox({source: sourcedatetime, width: 100, height: 22, theme: theme, selectedIndex: 0});
			filterwebButton.click(function(event){
				var value = cbodatetime.jqxComboBox('getSelectedIndex');
				var filtergroup = new $.jqx.filter();

				var filter = filtergroup.createfilter('stringfilter', value, 'contains');
				filtergroup.addfilter(1, filter);

				$("#jqx-grid-web").jqxGrid('addfilter', 'RecordDate', filtergroup);
				$("#jqx-grid-web").jqxGrid('applyfilters');
			});
			delwebButton.click(function(event){
				var value = $("#cbodatetime").jqxComboBox('getSelectedItem');
				$("#fromtodate").text(value.label);
                $("#popupWindow").jqxWindow({position: 'center'});
                $("#popupWindow").jqxWindow('show');

				//$("#jqx-grid-web").jqxGrid('deleterow',1);
			});
			optionButton.click(function(event){
				var lines = new Array();
				$.get("url.txt", function(data){
					lines = data.split("\n");
					$("#jqxurl").jqxListBox({width: 290, height: 320, theme: theme, source: lines});
				});
				$("#dialogview").jqxWindow({position: 'center'});
                $("#dialogview").jqxWindow('open');
			});
		},
		showstatusbar: true,
		statusbarheight: 35,
		renderstatusbar: function(statusbar)
		{
			var container = $("<div style='overflow: hidden; position: relative; margin: 5px; font-weight:bold;'></div>");
			var span = $("<span id='test'></span>");
			container.append(span);
			statusbar.append(container);
		},
		rendergridrows: function(params){
			return params.data;
		},
		ready: function(){
			$("#jqx-grid-web").jqxGrid('localizestrings', localizationObject);
		},
		columns: [
			{text: 'ID', datafield: 'URLId', width: 80},
			{text: 'Địa chỉ', datafield: 'URL', width: 520, cellsrenderer: linkrenderer},
			{text: 'Ngày', datafield: 'RecordDate', width: 170},
			{text: 'Người dùng', datafield: 'UserId', width: 100},
			{text: 'Máy sử dụng', datafield: 'Machine', width: 100}
		]
	});

	$("#popupWindow").jqxWindow({width:450, height:140, resizable: false, theme: theme, isModal: true, autoOpen: false, cancelButton: $("#Cancel"), modalOpacity: 0.9});
    $("#del").jqxButton({theme: theme, width: 150});
    $("#cancel").jqxButton({theme: theme, width: 150});

    $("#del").click(function(){
    	var value = $("#cbodatetime").jqxComboBox('getSelectedIndex');
		$.ajax({
			url: 'history.php',
			data: {action: 'deleteurl', d: value},
			cache: false,
			success: function(data, status, xhr){
				//$("#test").html("Đã xóa thành công "+data+" địa chỉ web đen");
				$("#popupWindow").jqxWindow('hide');
				$("#msg").html("Đã xóa thành công "+data+" địa chỉ web đen");
    			$("#message").jqxWindow({position: 'center'});
        		$("#message").jqxWindow('show');
				$("#jqx-grid-web").jqxGrid('updatebounddata');
			},
			error: function(jqXHR, textStatus, errorThrown){
				//$("#test").html("Có lổi xẩy ra trong lúc xóa nhật ký web.");
			}
		});
     });
     $("#cancel").click(function(){
          $("#popupWindow").jqxWindow('hide');
     });

     $("#message").jqxWindow({width: 350, height: 110, resizable: false, theme: theme, isModal: true, autoOpen: false, modalOpacity: 0.9});
     $("#btnok").jqxButton({theme: theme, width: 150});
     $("#btnok").click(function(){
          $("#message").jqxWindow('hide');
     });

	$("#dialogview").jqxWindow({width: 300, height: 400, resizable: false, theme: theme, isModal: true, autoOpen: false, modalOpacity: 0.9});
	$("#txturl").jqxInput({placeHolder: 'Nhập địa chỉ website', width: 240, height: 25, theme: theme});
	$("#btnaddurl").jqxButton({width: 35, height: 18, theme: theme});
	$("#btnaddurl").click(function(event){
		var value = $("#txturl").jqxInput('val');
		$.ajax({
			url: 'history.php',
			data: {action: 'addurl', v: value},
			cache: false,
			success: function(data){
				alert(data);
				$("#txturl").jqxInput('val', '');
			},
			error: function(){
				alert("co loi");
			}
		});
	});
});
</script>
<div id="jqx-grid-web"></div>
<div id="dialogview">
     <div>Danh sách URL sạch</div>
     <div style="overflow: hidden; background-color: #e0e9f5;">
          <div id="jqxurl"></div>
          <div style="padding-top: 10px;">
          	<input type="text" id="txturl" />
          	<div id="btnaddurl" style="float: right;"><img style='position: relative; margin-top: 2px;' src='images/add.png' width='14' height='14' /></div>
          </div>
     </div>
</div>
<div id="popupWindow">
     <div>Xử lý địa chỉ website</div>
     <div style="overflow: hidden; text-align: center;">
          <p>Xử lý các địa chỉ web đen và ngoài giờ truy cập</p>
          <p>Trong khoản thời gian <span id="fromtodate" style="font-weight:bold;"></span></p>
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