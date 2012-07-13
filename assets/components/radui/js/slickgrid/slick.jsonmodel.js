(function ($) {
  /***
   * A AJAX data store implementation for MODX 
   * Right now, it's hooked up to load all Apple-related Digg stories, but can
   * easily be extended to support and JSONP-compatible backend that accepts paging parameters.
   * @param (object) options - name: value pairs
   */
  function JsonModel(options) {
    // private
    // var PAGESIZE = 50; replaced by options.limit
    var data = {length: 0};
    //var searchstr = "apple"; replaced by options.query
    //var sortcol = null;   replace by options.sort
    //var sortdir = 1;      replaced by options.dir
    var h_request = null;
    var req = null; // ajax request

    // events
    var onDataLoading = new Slick.Event();
    var onDataLoaded = new Slick.Event();
    
    // settings
    var defaults = {
        baseUrl: '',
        urlParams: null,            // (Object) name=>value, add base params to the Url
        action: 'getCollection',    // Save/Remove/GetObject/GetCollection
        limit: 25,                  // Pagesize/limit
        sort: null,                 // the column/field to sort
        dir: 'ASC',                 // ASC or DESC
        queryColumn: null,          // search column 
        query: null,                // search string
        ajaxMethod: 'jsonp',         // json, jsonp, ajax
        ajaxType: 'GET',            // GET/POST
        callback: "callback"        // the default callback function 
        
    };
    
    
    /**
     * constructor
     */
    function init() {
        options = $.extend({}, defaults, options);
    }

    /**
     * 
     */
    function isDataLoaded(from, to) {
        for (var i = from; i <= to; i++) {
            if (data[i] == undefined || data[i] == null) {
                return false;
            }
        }
        return true;
    }

    /**
     * 
     */
    function clear() {
        for (var key in data) {
            delete data[key];
        }
        data.length = 0;
    }

    /**
     * @param (int) start (start record)
     * @param (int) to (end record)
     */
    function ensureData(start, to) {
        
        if (req) {
            req.abort();
            for (var i = req.fromPage; i <= req.toPage; i++) {
                data[i * options.limit] = undefined;
            }
        }

        if (start < 0) {
            start = 0;
        }

        var fromPage = Math.floor(start / options.limit);
        var toPage = Math.floor(to / options.limit);

        while (data[fromPage * options.limit] !== undefined && fromPage < toPage)
            fromPage++;

        while (data[toPage * options.limit] !== undefined && fromPage < toPage)
            toPage--;

        if (fromPage > toPage || ((fromPage == toPage) && data[fromPage * options.limit] !== undefined)) {
            // TODO:  look-ahead
            //console.log("return ensureData");
            return;
        }

        var url = options.baseUrl + ( options.baseUrl.indexOf("?") >= 0 ? "" : "?") + 
                "query=" + options.query + 
                "&start=" + start + 
                "&limit=" + options.limit +
                "&sort=" + options.sort +
                "&dir=" + options.dir +
                "&action=" + options.action + 
                paramString(options.urlParams);

        if (h_request != null) {
            clearTimeout(h_request);
        }

        h_request = setTimeout(function () {
            for (var i = fromPage; i <= toPage; i++)
                data[i * options.limit] = null; // null indicates a 'requested but not available yet'
            //console.log("ensure load Data");
            onDataLoading.notify({from: start, to: to});
            // alert("URL: " + url);
            switch (options.ajaxMethod) {
                case 'jsonp':
                    req = $.jsonp({
                        url: url,
                        callbackParameter: options.callback,
                        cache: true, // 
                        success: onSuccess,
                        error: function () {
                            onError(fromPage, toPage)
                        }
                    });
                    break;
                case 'json':
                default:
                    req = $.ajax({
                        type: options.ajaxType,
                        url: url,
                        callbackParameter: options.callback,
                        cache: true, // 
                        success: onSuccess,
                        error: function () {
                            onError(fromPage, toPage)
                        }
                    });
                    break;
            }
            
            req.fromPage = fromPage;
            req.toPage = toPage;
        }, 50);
    }
    /**
     * Ajax on fail/error
     * @param fromPage - the page it came form?
     * @param toPage
     */
    function onError(fromPage, toPage) {
        // alert("error loading pages " + fromPage + " to " + toPage);
        var from = 0;
        var to = 0;
        
        req = null;
        onDataLoaded.notify({from: from, to: to});
        $('#jsonMessage')
            .html('Error')
            .fadeIn('fast')
            .fadeOut(2500);
        
    }
    /**
     * Ajax onSuccess
     * @param response
     */
    function onSuccess(resp) {
        /* types: dataTables, modx, .NET */
        var total = 0;
        var count = 0;
        var newData = {};
        
        var type = 'MODX';
        switch( type) {
            case 'dataTables':
                total = resp.iTotalRecords;// total in DB
                count = resp.iTotalDisplayRecords;//
                newData = resp.aaData;
                break;
            case '.NET':
                break;
            case 'MODX'://
                total = resp.total;// total in DB
                count = resp.count;//
                newData = resp.data;
            default:
                break;
        }
        // turn resp into: 
        
        var from = this.fromPage * options.limit;
        var to = from + count;
        
        data.length = parseInt(total);

        for (var i = 0; i < count; i++) {
            data[from + i] = newData[i];
            data[from + i].index = from + i;
        }
        req = null;
        onDataLoaded.notify({from: from, to: to});
        $('#jsonMessage')
            .html(resp.message)
            .fadeIn('fast')
            .fadeOut(2500);
    }
    /**
     * Reload data
     * @param (Int) from
     * @param (Int) to 
     */
    function reloadData(from, to) {
        for (var i = from; i <= to; i++)
            delete data[i];
        
        ensureData(from, to);
    }

    /**
     * Set the sort
     * @param (String) column
     * @param (String) dir
     */
    function setSort(column, dir) {
        options.sort = column;
        options.dir = dir;
        clear();
    }
    /**
     * Set the params
     * @param (String) name
     * @param (String) value
     */
    function setParam(name,value) {
        var len = options.urlParams.length;
        var set = false;
        for ( i=0; i < len; i++ ) {
            if ( options.urlParams[i].name == name ) {
                options.urlParams[i].value = value;
                set = true;
            }
        }
        if ( !set ) {
            options.urlParams.push({'name': name, 'value':value});
        }
    }
    /**
     * set the search 
     * @param (String) str
     */
    function setSearch(str) {
        options.query = str;
        clear();
    }
    /**
     * make a url from an object
     */
    function paramString(object) {
        var string = '';
        var len = object.length;
        //console.log("O: " + object );
        //console.log("Len: " + len);
        for ( i=0; i < len; i++ ) {
            //console.log ("Name: " + object[i].name );
            //console.log ("Name: " + object[i][0] + " v: " + object[i][1] );
            //alert("Name: "+ object[i]);
            string += '&'+ object[i].name+'='+object[i].value;
        }
        return string;
    }
    /**
     * now run the constuctor
     */
    init();

    return {
          // properties
          "data": data,
    
          // methods
          "clear": clear,
          "isDataLoaded": isDataLoaded,
          "ensureData": ensureData,
          "reloadData": reloadData,
          "setSort": setSort,
          "setParam": setParam,
          "setSearch": setSearch,
    
          // events
          "onDataLoading": onDataLoading,
          "onDataLoaded": onDataLoaded
        };
  }

  // Slick.Data.JsonModel
  $.extend(true, window, { Slick: { Data: { JsonModel: JsonModel }}});
  
})(jQuery);