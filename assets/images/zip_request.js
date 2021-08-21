function create_zip() {
var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel"><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/>';
 tab_text = tab_text + '<head><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>';
 tab_text = tab_text + '<x:Name>Test Sheet</x:Name>';
 tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
 tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"></head><body>';
 tab_text = tab_text + "<table border='1px'>";
//get table HTML code
 tab_text = tab_text + $('#print').html();
 tab_text = tab_text + '</table></body></html>';
  
  var zip = new JSZip();
zip.file(Date()+" Invoice.xls", tab_text);
zip.generateAsync({type:"blob"})
.then(function(content) {
    saveAs(content, Date()+"invoice.zip");
});
  

}