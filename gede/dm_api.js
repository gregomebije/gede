// This is an open source Javascript client for our API.
// Feel free to modify it and share it freely.

function LMS_API(args){
    this.url = args.url;
    this.api_key = args.api_key;
    this.api_version = args.api_version;
    
    // version
    this.get_version = function(success, failure){
        return this.invoke('get_version', null, success, failure);
    }
    
    // create
    this.create_row = function(table_data, success, failure){
	   return this.invoke('crud', table_data, success, failure);
		
    }
   
    //get_data   
   this.get_data = function(table, sSearch, iDisplayStart, iDisplayLength, 
      success, failure){
        return this.invoke('get_data', {
	    id: '-1',
            table: table,
		data: '',
                sSearch: sSearch,
		iDisplayStart: iDisplayStart,
	       iDisplayLength: iDisplayLength
        }, success, failure);
    }
   
    // array helper
    this.to_array = function(ids){
        return (ids instanceof Array ? ids : [ids]);
    }
    
    // invoke
    this.invoke = function(method, args, success, failure){
        var params = [['api_key', this.api_key]];
        
        if (this.api_version != null) {
            params.push(['api_version', this.api_version]);
        }
        
	if (args != null) {
            for (var property in args) {
                params.push([property, args[property]]);
            }
        }

        var parts = [];
        
        for (var i = 0; i < params.length; i++) {
            if (params[i][1] instanceof Array) {
                for (var j = 0; j < params[i][1].length; j++) {
                    parts.push(params[i][0] + '[]=' 
                      + encodeURIComponent(params[i][1][j]));
                }
            }
            else {
                parts.push(params[i][0] + '=' 
                   + encodeURIComponent(params[i][1]));
            }
        }
	var method_url = this.url + '/api/' + method + '.php' + '?' 
            + parts.join('&') + "&rand=" + Math.random();
        
	//dataType: 'jsonp',
        $.ajax({
            url: method_url,
            dataType: 'json',
            success: function(json, text_status, jqXHR){
	    	//alert(method_url);
	        //alert(JSON.stringify(json));
		success(json, args);  //Old code success(json)
			  
            },
            error: function(jqXHR, text_status, errorThrown){
	    	alert(text_status);
		alert(errorThrown);
                failure(jqXHR.status, text_status);
				
            }
        });
    }
}
