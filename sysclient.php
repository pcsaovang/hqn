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
			{name: 'cpu', type: 'string'},
			{name: 'ram', type: 'string'},
			{name: 'vga', type: 'string'},
			{name: 'vgachip', type: 'string'},
			{name: 'vgamem', type: 'string'},
			{name: 'lannic', type: 'string'},
			{name: 'cpname', type: 'string'}
		],
		url: "sysclients.php",
		cache: false,
		root: "Rows",
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

	$("#jqx-grid-sysclient").jqxGrid({
		width: 970,
		autoheight: true,
		source: dataAdapter,
		theme: theme,
		pagesize: 20,
		pageable: true,
		pagesizeoptions: ['10', '20', '30', '50'],
		virtualmode: true,
		rendergridrows: function(params){
			return params.data;
		},
		ready: function(){
			$("#jqx-grid-sysclient").jqxGrid('localizestrings', localizationObject);
		},
		columns: [
			{text: 'CPU', datafield: 'cpu', width: 350},
			{text: 'RAM', datafield: 'ram', width: 50},
			{text: 'VGA', datafield: 'vga', width: 150},
			{text: 'ChipType', datafield: 'vgachip', width: 150},
			{text: 'VgaMem', datafield: 'vgamem', width: 90},
			{text: 'LAN', datafield: 'lannic', width: 100},
			{text: 'Name', datafield: 'cpname', width: 80}
		]
	});
});
</script>
<div id="jqx-grid-sysclient"></div>
<?php
}
else
{
	echo "<h1 align='center'>Truy cập bất hợp pháp hả?</h1>";
}
?>