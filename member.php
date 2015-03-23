<?php
//session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] == '')
{
	$message = array();
?>
<div id='home'>
	<div id='home-left'>
		<form action='#' id='frmlogin' class='frmlogin'>
		<table cellpadding='5' cellspacing='0' border='0'><tr>
			<td colspan='2'><div id='title-login'><h1>ĐĂNG NHẬP</h1></div></td></tr><tr>
			<td><label class='lbl-login'>Tài khoản: </label></td>
			<td><input type='text' name='txtuname' id='txtuname' /></td></tr><tr>
			<td><label class='lbl-login'>Mật khẩu: </label></td>
			<td><input type='password' name='txtpw' id='txtpw' /></td></tr><tr>
			<td colspan=2 align='center'><input id='btnlogin' type='submit' value='Đăng nhập' /></td></tr>
		</table>
		</form>
		<div id='errors'></div>
	</div>
	<div id='home-right'></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	var theme = "energyblue";
	$("#btnlogin").jqxButton({theme: theme});
	$("#frmlogin").jqxValidator({
		rules: [
			{input: '#txtuname', message: ' ', action: 'keyup, blur', rule: 'required'},
			{input: '#txtpw', message: ' ', action: 'keyup, blur', rule: 'required'}
		],
		theme: theme,
		hintType: 'label',
	});
	$("#btnlogin").click(function(){
		$.ajax({
			type: 'POST',
			url: 'login.php',
			data: $("#frmlogin").serialize() + "&action=login",
			success: function(data){
				if(data.length > 0){
					$("#errors").html(data);
				}
				else
				{
					window.location.href='index.php';
				}
			}
		});
		return false;
	});
})
</script>
<?php
}
else
{
?>
<style type="text/css">
	.editedRow {color: #b90f0f; font-style: italic;}
</style>
<script type="text/javascript">
$(document).ready(function(){
	var theme = 'energyblue';
	var editedRows = new Array();
	var source = {
		datatype: "json",
		datafields: [
			{name: 'UserId', type: 'number'},
			{name: 'FirstName', type: 'string'},
			{name: 'Debit', type: 'number'},
			{name: 'MoneyPaid', type: 'number'},
			{name: 'RemainMoney', type: 'number'},
			{name: 'RecordDate', type: 'date'},
			{name: 'PayOld', type: 'string'},
			{name: 'Active', type: 'bool'}
		],
		url: "data.php",
		id: "UserId",
		cache: false,
		filter: function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'filter');
		},

		sort: function(){
			$("#jqxgrid").jqxGrid('updatebounddata', 'sort');
		},

		updaterow: function(rowid, rowdata, commit){
			var data = "update=true&Debit="+ rowdata.Debit +"&MoneyPaid="+ rowdata.MoneyPaid +"&RemainMoney="+ rowdata.RemainMoney+"&UserId="+rowdata.UserId + "&Active="+rowdata.Active;
			var rowindex = $("#jqxgrid").jqxGrid('getrowboundindexbyid', rowid);			
            editedRows.push({ index: rowindex, data: rowdata });
			$.ajax({
				type: "GET",
				url: "data.php",
				data: data,
				success: function(data, status, xhr){
					commit(true);
				},
				error: function(){
					commit(false);
				}
			});
		},
		deleterow: function(rowid, commit){
			var data = "delete=true&" + $.param({UserId: rowid});
			$.ajax({
				//dataType: 'json',
				url: 'data.php',
				data: data,
				cache: false,
				success: function(data, status, xhr){
					$("#jqxgrid").jqxGrid('updatebounddata');
					commit(true)
				},
				error: function(jqXHR, textStatus, errorThrown){
					commit(false);
				}
			});
		},
		root: 'Rows',
		
		beforeprocessing: function(data){
			if(data != null)
			{
				source.totalrecords = data[0].TotalRows;
			}
			source.totalmoney = data[0].TotalMoney;
			source.totaldebit = data[0].TotalDebit;
		}
		
	};
	var dataAdapter = new $.jqx.dataAdapter(source, {
		loadError: function(xhr, status, error){
			alert(error);
		},
		loadComplete: function(data){
			$("#jqxgrid").jqxGrid({
				renderstatusbar: function(status){
					var me = this;
					var container = $("<div style='overflow: hidden; position: relative; margin: 5px; font-weight:bold;'></div>");
					var spantotalmoney = $("<span style='padding-right: 20px;'>Tổng tiền còn của khách: <label style='color: red;'>"+ source.totalmoney +"</label></span>");
                    var spantotaldebit = $("<span style='padding-right: 20px;'>Tổng nợ: <label style='color: red;'>"+source.totaldebit+"</label></span>");
					container.append(spantotalmoney);
					container.append(spantotaldebit);
					status.html(container);
				}
			});
		}
	});
	
	var cellclass = function (row, datafield, value, rowdata) {
		for (var i = 0; i < editedRows.length; i++) {
			if (editedRows[i].index == row) {
				return "editedRow";
			}
		}
	}
	
	var getLocalization = function(){
		var localizationobj = {};
		localizationobj.pagergotopagestring = "Đi đến trang:";
		localizationobj.pagershowrowsstring = "Hiện mẩu tin: ";
		localizationobj.currencysymbol = " đ";
		localizationobj.currencysymbolposition = "after";
		localizationobj.thousandsseparator = ",";
		localizationobj.sortascendingstring = "Sắp xếp tăng dần";
		localizationobj.sortdescendingstring = "Sắp xếp giảm dần";
		localizationobj.sortremovestring = "Hủy sắp xếp";
		localizationobj.filterclearstring = "Hủy lọc";
		localizationobj.filterstring = "Lọc";
		localizationobj.filtershowrowstring = "Kiểu lọc";
		localizationobj.filterorconditionstring = "Hoặc";
		localizationobj.filterandconditionstring = "Và";
		localizationobj.filterstringcomparisonoperators = ['Bắt đầu', 'Có chứa'];
     	localizationobj.filternumericcomparisonoperators = ['Bằng', 'Nhỏ hơn', 'Lớn hơn', 'Nhỏ hơn hoặc bằng', 'Lớn hơn hoặc bằng'];
     	localizationobj.filterdatecomparisonoperators = ['Bằng', 'Nhỏ hơn', 'Lớn hơn', 'Nhỏ hơn hoặc bằng', 'Lớn hơn hoặc bằng'];
     	localizationobj.filterbooleancomparisonoperators = ['Bằng'];
		return localizationobj;
	}
	
	$("#jqxgrid").jqxGrid({
		width: 970,
		source: dataAdapter,
		theme: theme,
		editable: true,
		showfilterrow: false,
		filterable: true,
		sortable: true,
		autoheight: true,
		pageable: true,
		virtualmode: true,
		altrows: false,
		//selectionmode: 'multiplecellsadvanced',
		pagesizeoptions: ['10', '20', '30'],
		pagesize: 20,
		editmode: 'dblclick',
		showaggregates: false,
		localization: getLocalization(),
		showheader: true,
		showtoolbar: true,
		toolbarheight: 40,
		showstatusbar: true,
		
		rendertoolbar: function(toolbar){
			var container = $("<div style='overflow: hidden; position: relative; margin: 5px;'></div>");
			var viewButton = $("<div style='float: left; margin-left: 5px;'><img style='position: relative; margin-top: 2px;' src='images/tasksIcon.png'/><span style='margin-left: 4px; position: relative; top: -3px;'>Xem nhật ký nạp tiền</span></div>");
			var deleteButton = $("<div style='float: left; margin-left: 5px;'><img style='position: relative; margin-top: 2px;' src='images/close.png'/><span style='margin-left: 4px; position: relative; top: -3px;'>Xóa hội viên</span></div>");
			var reloadButton = $("<div style='float: left; margin-left: 5px;'><img style='position: relative; margin-top: 2px;' src='images/refresh.png'/><span style='margin-left: 4px; position: relative; top: -3px;'>Tải lại danh sách</span></div>");
			container.append(viewButton);
			container.append(deleteButton);
			container.append(reloadButton);
			toolbar.append(container);

			viewButton.jqxButton({theme: theme, width: 180, height: 20});
			deleteButton.jqxButton({theme: theme, width: 180, height: 20});
			reloadButton.jqxButton({theme: theme, width: 180, height: 20});

			viewButton.click(function(event){
				var selectedrowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
				if(selectedrowindex >= 0){
					var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', selectedrowindex);
					$("#titlemember").text(dataRecord.FirstName);
					showpayment(dataRecord.UserId);
					$("#dialogview").jqxWindow({position: 'center'});
					$("#dialogview").jqxWindow('open');
				}
			});

			deleteButton.click(function(event){
				var selectedrowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
				if(selectedrowindex >= 0){
					var dataRecord = $("#jqxgrid").jqxGrid('getrowdata', selectedrowindex);
					$("#title-member").text(dataRecord.FirstName);
					$("#popupWindow").jqxWindow({position: 'center'});
					$("#popupWindow").jqxWindow('show');
				}
			});

			reloadButton.click(function(event){
				$("#jqxgrid").jqxGrid({source: dataAdapter});
			});
		},
		
		rendergridrows: function(obj){
			return obj.data;
		},
		
		updatefilterconditions: function(type, defaultconditions){
     		var stringcomparisonoperators = ['STARTS_WITH', 'CONTAINS'];
     		var numericcomparisonoperators = ['EQUAL', 'LESS_THAN', 'GREATER_THAN', 'LESS_THAN_OR_EQUAL', 'GREATER_THAN_OR_EQUAL'];
     		var datecomparisonoperators = ['EQUAL','LESS_THAN', 'GREATER_THAN', 'LESS_THAN_OR_EQUAL', 'GREATER_THAN_OR_EQUAL'];
     		var booleancomparisonoperators = ['EQUAL'];
     		switch (type){
     			case 'stringfilter':
     				return stringcomparisonoperators;
     			case 'numericfilter':
     				return numericcomparisonoperators;
     			case 'datefilter':
     				return datecomparisonoperators;
     			case 'booleanfilter':
     				return booleancomparisonoperators;
     		}
     	},
     	updatefilterpanel: function(filtertypedropdown1, filtertypedropdown2, filteroperatordropdown, filterinputfield1, filterinputfield2, filterbutton, clearbutton, columnfilter, filtertype, filterconditions){
     		var index1 = 0;
     		var index2 = 0;
     		if(columnfilter != null)
     		{
     			var filter1 = columnfilter.getfilterat(0);
     			var filter2 = columnfilter.getfilterat(1);
     			if(filter1)
     			{
     				index1 = filterconditions.indexOf(filter1.comparisonoperator);
     				var value1 = filter1.filtervalue;
     				filterinputfield1.val(value1);
     			}
     			if(filter2)
     			{
     				index2 = filterconditions,indexOf(filter2.comparisonoperator);
     				var value2 = filter2.filtervalue;
     				filterinputfield2.val(value2);
     			}
     		}
     		filtertypedropdown1.jqxDropDownList({autoDropDownHeight: true, selectedIndex: index1});
     		filtertypedropdown2.jqxDropDownList({autoDropDownHeight: true, selectedIndex: index2});
     	},
		columns: [
			{
				text: 'Mã tài khoản',
				datafield: 'UserId',
				width: 70,
				editable: false,
				cellclassname: cellclass
			},
			{
				text: 'Tên tài khoản',
				datafield: 'FirstName',
				width: 180,
				editable: false,
				cellclassname: cellclass
			},
			{
				text: 'Tiền nợ',
				datafield: 'Debit',
				cellsformat: 'c',
				cellsalign: 'right',
				width: 100,
				columntype: 'numberinput',
				spinButtons: true,
				cellclassname: cellclass,
				validation: function(cell, value){
					if(value < 0)
					{
						return {result: false, message: "Không được nhập số âm."};
					}
					return true;
				},
				createeditor: function(row, cellvalue, editor){
					editor.jqxNumberInput({decimalDigits: 0, digits: 6});
				}
			},
			{
				text: 'Tiền đã nạp',
				datafield: 'MoneyPaid',
				cellsformat: 'c',
				cellsalign: 'right',
				width: 150,
				columntype: 'numberinput',
				cellclassname: cellclass,
				validation: function(cell, value){
					if(value < 0)
					{
						return {result: false, message: "Không được nhập số âm."};
					}
					return true;
				},
				createeditor: function(row, cellvalue, editor){
					editor.jqxNumberInput({decimalDigits: 0, digits: 7});
				}
			},
			{
				text: 'Tiền còn',
				datafield: 'RemainMoney',
				cellsformat: 'c',
				cellsalign: 'right',
				width: 100,
				columntype: 'numberinput',
				cellclassname: cellclass,
				validation: function(cell, value){
					if(value < 0)
					{
						return {result: false, message: "Không được nhập số âm."};
					}
					return true;
				},
				createeditor: function(row, cellvalue, editor){
					editor.jqxNumberInput({decimalDigits: 0, digits: 6});
				}
			},
			{
				text: 'Ngày tạo',
				datafield: 'RecordDate',
				width: 150,
				cellsformat: 'dd-MM-yyyy',
				filtertype: 'date',
				editable: false,
				cellsalign: 'right',
				cellclassname: cellclass
			},
			{
				text: 'Nạp cách đây',
				datafield: 'PayOld',
				cellsalign: 'right',
				width: 120,
				editable: false,
				cellclassname: cellclass,
				sortable: false,
				filterable: false
			},
			{
				text: 'Trạng thái',
				datafield: 'Active',
				cellsalign: 'center',
				width: 100,
				columntype: 'checkbox',
				filtertype: 'bool',
				cellclassname: cellclass
			}
		]
	});
	if (theme != "") {
		$("#inputField").addClass('jqx-input-' + theme);
	}
	$("#popupWindow").jqxWindow({width:450, height:110, resizable: false, theme: theme, isModal: true, autoOpen: false, cancelButton: $("#Cancel"), modalOpacity: 0.9});

	$("#del").jqxButton({theme: theme, width: 150});
	$("#cancel").jqxButton({theme: theme, width: 150});
	$("#del").click(function(){
		var selectedrowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
		var rowscount = $("#jqxgrid").jqxGrid('getdatainformation').rowscount;
		if(selectedrowindex >= 0 && selectedrowindex < rowscount)
		{
			var id = $("#jqxgrid").jqxGrid('getrowid', selectedrowindex);
			$("#jqxgrid").jqxGrid('deleterow', id);
			$("#popupWindow").jqxWindow('hide');
		}
	});
	$("#cancel").click(function(){
		$("#popupWindow").jqxWindow('hide');
	});

	$("#dialogview").jqxWindow({width: 600, resizable: false, theme: theme, isModal: true, autoOpen: false, modalOpacity: 0.9});

	var self = this;
	var pagerrenderer = function(){
		var element = $("<div style='margin-left: 10px; margin-top: 5px; width: 100%; height: 100%;'></div>");
		var datainfo = $("#jqx-gird-pay").jqxGrid('getdatainformation');
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
			$("#jqx-gird-pay").jqxGrid('gotonextpage');
		});
		leftButton.click(function(){
			$("#jqx-gird-pay").jqxGrid('gotoprevpage');
		});
		return element;
	}

	$("#jqx-gird-pay").on('pagechanged', function(){
		var datainfo = $("#jqx-gird-pay").jqxGrid('getdatainformation');
		var paginginfo = datainfo.paginginformation;
		self.label.text(1 + paginginfo.pagenum * paginginfo.pagesize + "-" + Math.min(datainfo.rowscount, (paginginfo.pagenum + 1) * paginginfo.pagesize) + ' of ' + datainfo.rowscount);
	});

	$("#jqx-gird-pay").on('bindingcomplete', function(event){
		var a = $("#jqx-gird-pay").height();
		$("#dialogview").jqxWindow({height: a + 40, position: 'center'});
	});

	function showpayment(uid)
	{
		var source =
		{
			datatype: "json",
			datafields: [
				{ name: 'VoucherDate', type: 'date'},
				{ name: 'VoucherTime'},
				{ name: 'Amount', type: 'number'},
				{ name: 'Note'}
			],
			url: 'payment.php',
			cache: false,
			data: {action: 'viewpay', uid: uid}
		};

		var dataAdapter = new $.jqx.dataAdapter(source);
			
		$("#jqx-gird-pay").jqxGrid(
		{
		width: '100%',
		autoheight: true,
		sortable: true,
		pageable: true,
		pagesize: 15,
		localization: getLocalization(),
		pagerrenderer: pagerrenderer,
		source: dataAdapter,
		theme: theme,
		columns: [
			{ text: 'Ngày nạp', datafield: 'VoucherDate', width: 100, cellsformat: 'dd-MM-yyyy'},
			{ text: 'Giờ nạp', datafield: 'VoucherTime', width: 100 },
			{ text: 'Số tiền', datafield: 'Amount', width: 100, cellsformat: 'c', cellsalign: 'right' },
			{ text: 'Ghi chú', datafield: 'Note', width: 290 },
		]
		});
	}
})
</script>
<div id="jqxgrid"></div>
<div id="popupWindow">
	<div>Cẩn thận xóa hội viên</div>
	<div style="overflow: hidden; text-align: center;">
		<p>Bạn chắc chắn muốn xóa hội viên <span id="title-member" style="font-weight:bold;"></span> không?</p>
		<button id="del">Có</button>
		<button id="cancel">Không</button>
	</div>
</div>
<div id="dialogview">
	<div>Nhật ký giao dịch của <span id="titlemember" style="font-weight:bold;"></span></div>
	<div style="overflow: hidden; background-color: #e0e9f5;">
		<div id="jqx-gird-pay"></div>
	</div>
</div>
<?php
}
?>