// JavaScript Document
// http://www.kunalbabre.com/projects/table2CSV.php
jQuery.fn.table2CSV = function(options) {
    var options = jQuery.extend({
        separator: ',',
        header: [],
        url: '',
        filename: 'csv-report',
        title: 'CSV Report',
        dataType: 'csv',
        delivery: 'popup' // popup, value
    },
    options);

    var csvData = [];
    var headerArr = [];
    var el = this;

    //header
    var numCols = options.header.length;
    var headerRow = []; // construct header avalible array
	var numArray = [];
	var count = 0;
    if (numCols > 0) {
        for (var i = 0; i < numCols; i++) {
            headerRow[headerRow.length] = options.header[i];
        }
    } else {
		var tmpColumns = grid.getColumns();
		for (var key in tmpColumns) {    
			var obj = tmpColumns[key];    
			for (var prop in obj) {       
				//alert(prop + " = " + obj[prop]);
				if (prop == "name") {
					headerRow[headerRow.length] = obj[prop];
				}
				if (prop == "id") {
					numArray[obj[prop]] = count++;
					//alert(numArray);	
				}
			}
			
		}
        /*
		THIS IS ONLY IF IT IS AN HTML CODED TABLE, NOT FOR DYNAMIC TABLES
		
		$(el).filter(':visible').find('th').each(function() {
            if ($(this).css('display') != 'none') tmpRow[tmpRow.length] = formatData($(this).html());
        });*/
    }

    row2CSV(headerRow);
    // actual data
	
	for (var key in loader.data) {    
		var row = loader.data[key];
		var tmpRow = [];
		// 
		for ( var name in numArray ) {
			// numArray = ['name': 'value' ]
			tmpRow[numArray[name]] = row[name];
		}
		
		row2CSV(tmpRow);
	}
    /*
	THIS IS ONLY IF IT IS AN HTML CODED TABLE, NOT FOR DYNAMIC TABLES
	
	$(el).find('tr').each(function() {
        var tmpRow = [];
        $(this).filter(':visible').find('td').each(function() {
            if ($(this).css('display') != 'none') tmpRow[tmpRow.length] = formatData($(this).html());
        });
        row2CSV(tmpRow);
    });*/
    if (options.delivery == 'popup') {
        var mydata = csvData.join('\n');
        return popup(mydata);
    } else {
        var mydata = csvData.join('\n');
        return mydata;
    }

    function row2CSV(tmpRow) {
        var tmp = tmpRow.join('') // to remove any blank rows
        // alert(tmp);
        if (tmpRow.length > 0 && tmp != '') {
            var mystr = tmpRow.join(options.separator);
            csvData[csvData.length] = mystr;
        }
    }
    /*
	This is also only needed for HTML coded tabls, not for dynamic
	function formatData(input) {
        // replace " with â€œ
        var regexp = new RegExp(/["]/g);
        var output = input.replace(regexp, "â€œ");
        //HTML
        var regexp = new RegExp(/\<[^\<]+\>/g);
        var output = output.replace(regexp, "");
        if (output == "") return '';
        return '"' + output + '"';
    }*/
    function popup(data) {
        $("body").append('<form id="exportform" action="'+options.url+'" method="post">'+
            '<input type="hidden" id="exportData" name="exportdata" />'+
            '<input type="hidden" name="title" value="'+options.title+'" />' +
            '<input type="hidden" name="filename" value="'+options.filename+'" />' +
            '<input type="hidden" name="dataType" value="'+options.dataType+'" />' +
            '</form>');
        $("#exportData").val(data);
        $("#exportform").submit().remove();
        return true;
    }
};