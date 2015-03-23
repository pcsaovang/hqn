<?php
//session_start();
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
{
?>
<script type="text/javascript">
$(document).ready(function(){
	var theme = 'energyblue';
	var source = {
		datatype: "json",
		datafields: [
			{name: 'Status', type: 'string'},
			{name: 'RecordDate', type: 'string'},
			{name: 'RecordTime', type: 'string'},
			{name: 'Note', type: 'string'}
		],
		url: "serverlogs.php",
		cache: false,
		root: 'Rows',
		beforeprocessing: function(data){
			source.totalrecords = data[0].TotalRows;
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

	var dataAdapter = new $.jqx.dataAdapter(source);

	$("#jqxgrid").jqxGrid({
		source: dataAdapter,
		theme: theme,
		width: 970,
		pageable: true,
		virtualmode: true,
		rendergridrows: function(){
			return dataAdapter.records;
		},
		pagesizeoptions: ['10', '20', '30'],
		pagesize: 20,
		autoheight: true,
		ready: function(){
			$("#jqxgrid").jqxGrid('localizestrings', localizationObject);
		},
		columns: [
			{text: 'Trạng thái', datafield: 'Status', width: 200},
			{text: 'Ngày', datafield: 'RecordDate', width: 100},
			{text: 'Giờ', datafield: 'RecordTime', width: 80},
			{text: 'Ghi chú', datafield: 'Note', width: 590},
		],
	});
})
</script>
<div id="jqxgrid"></div>
<?php
}
else
{
	echo "<h1 align='center'>Truy cập bất hợp pháp hả?</h1>";
}
?>