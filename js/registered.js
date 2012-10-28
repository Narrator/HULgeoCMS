function GetXmlHttpObject() //function for passing lat/lngs to php
	{
		if (window.XMLHttpRequest)
		{
		  // code for IE7+, Firefox, Chrome, Opera, Safari
		  return new XMLHttpRequest();
		}
		if (window.ActiveXObject)
		{
		  // code for IE6, IE5
		  return new ActiveXObject("Microsoft.XMLHTTP");
		}
		return null;
	}
	
	function stateChanged() // Used for same purpose as above
	{
		if (xmlhttp.readyState==4)
		{
			//alert(xmlhttp.responseText); // this will alert "true";
		}
	}
	
	function CallPHP(geomstring,end,type)
	{
		var url="/js/server/addgeomtodb.php";
		url = url+"?"+geomstring+"n="+end+"&type="+type+"&cat="+cattagvideo[0]+"&tag="+cattagvideo[1];
		if(cattagvideo[2]) {
			url = url + "&video="+cattagvideo[2];
		}
		if(photoarray) {
			url = url + "&photo="+photoarray;
		}
		
		xmlhttp=GetXmlHttpObject();
		if (xmlhttp==null)
		{
			alert ("Browser does not support HTTP Request");
			return;
		}

		xmlhttp.onreadystatechange=stateChanged;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
	}
		
	var drawControl = new L.Control.Draw({
	styles: {
		polygon: {
			color: '#bada55'
		}
	}
	});
	map.addControl(drawControl);
	
	var drawnItem = [],
	geomstring = "",
	type = new String(),
	end = 0,
	stored = false;
	
	map.on('draw:polyline-created', function (e) {
		placed = true;
		stored = false;
		drawnItem = e.poly;
		var latlng=(e.poly.getLatLngs());
		end = latlng.length;
		type = "polyline";
		geomstring = "";
		for(var i=0;i<end;i++)
		{
			geomstring=geomstring + "lat" + i + "=" + latlng[i].lat + "&lng" + i + "=" + latlng[i].lng + "&";
		}
		map.addLayer(drawnItem);
		
		$('#dialog_save').dialog( "option" , "position" , [60,250] );
		$('#dialog_save').dialog("open");
		diopen = true;
	});
	
	map.on('draw:polygon-created', function (e) {
		placed = true;
		stored = false;
		drawnItem = e.poly;
		var latlng=(e.poly.getLatLngs());
		end = latlng.length;
		type = "polygon";
		geomstring = "";
		for(var i=0;i<end;i++)
		{
			geomstring=geomstring + "lat" + i + "=" + latlng[i].lat + "&lng" + i + "=" + latlng[i].lng + "&";
		}
		map.addLayer(drawnItem);
		$('#dialog_save').dialog( "option" , "position" , [60,250] );
		$('#dialog_save').dialog("open");
		diopen = true;
	});
	
	map.on('draw:marker-created', function (e) {
		placed = true;
		stored = false;
		drawnItem = e.marker;
		var latlng=(e.marker.getLatLng());
		type = "point";
		end = 1;
		geomstring = "";
		for(var i=0;i<end;i++)
		{
			geomstring=geomstring + "lat" + i + "=" + latlng.lat + "&lng" + i + "=" + latlng.lng + "&";
		}
		map.addLayer(drawnItem);
		$('#dialog_save').dialog( "option" , "position" , [60,250] );
		$('#dialog_save').dialog("open");
		diopen = true;
	});
	
	function undolast()
	{
		if(drawControl.handlers.polyline._poly) drawControl.handlers.polyline.undo();
		else if(drawControl.handlers.polygon._poly) drawControl.handlers.polygon.undo();
	}
	
	$(document).ready(function(){
	
		document.getElementById("categoriesSelect").selectedIndex = -1;
		document.getElementById("tagsSelect").selectedIndex = -1;
		$("#geomdata").validate({
			debug: false,
			rules: {
				categories: {required: true,minlength:1},
				tags: {required: true,minlength:1},
				videolink: {url:true}
			},
			messages: {
				categories: "*Please choose a category",
				tags: "*Please choose a tag",
				videolink: "*Please enter a valid url"
			},
			submitHandler: function(form) {
				// Pass to PHP to return required string
				var phpurl = '/js/server/geomdata.php';
				$.post(phpurl, $("#geomdata").serialize(), function(data) {
					cattagvideo = data.split("<|>");
					CallPHP(geomstring,end,type);
					stored = true;
					fileuploadcounter = 0;
					fileaddcounter = fileaddcounter +3;
					$('#dialog_save').dialog("close");
					diopen = false;
					placed = false;
					document.getElementById("tagsSelect").selectedIndex = -1;
					document.getElementById("categoriesSelect").selectedIndex = -1;
					$('#dialog_confirm').dialog('open');
					confirmboxtext = '</br><div id="confirmation" class=\"formfields\" style=\"font-weight:normal;font-size:12px;\">' + '<b>Your Geometry has been succesfully added!</b></br></br>' + '<b>Geometry type:</b> ' + type + '</br>' + '<b>Category:</b> ' + cattagvideo[0] + '</br><b>Tag</b>: ' + cattagvideo[1] + '</br></br></br>' + 'Please Refresh your browser to see changes. This dialog will close automatically</div>';
					document.getElementById('dialog_confirm').innerHTML = confirmboxtext;
				});
			}
		});
	});
	
	// Functions to handle opening and closing of the upload box
	function uploadopen() {
		$('#fileupload').fileupload('option', 'maxNumberOfFiles', fileaddcounter);
		if(fileuploadcounter < 3)
		{
			$("#dialog_upload").dialog("open");
			$("#dialog_upload").bind("dialogbeforeclose",function(e) {
				$('#fileupload button:reset').click();
			});
		}
		
		else alert('You can\'t upload more than 3 files for one geometry');

		
	}
	function uploadclose() {
			$("#dialog_upload").dialog("close");
	}

	//Save/discard changes form functions
	function confirm()
	{	
		$('#geomdata').submit();
		if($("#geomdata").validate().element('#categoriesSelect') && $("#geomdata").validate().element('#tagsSelect'))
		{
			// if new tag is added - add to database (5) - after (3)
			// also bind popup to geometry here (6)
		}
	}
	
	function discard()
	{
		map.removeLayer(drawnItem);
		stored = false;
		$('#dialog_save').dialog("close");
		diopen = false;
		placed = false;
	}
		
	//Event Handling for the image upload modal box
	function buttonUploadshow() {
		buttonstart.appendTo('#elem');
		buttonpresent = 1;	
	}
	
	$('#fileupload')
		.bind('fileuploaddone', function (e, data) {
			fileuploadcounter = fileuploadcounter + 1;
			 photoarray.push(data.files[0].name);
		})
		
		.bind('fileuploadadd', function (e, data) {
			if(buttonpresent == 0) {
				buttonUploadshow();
			}
			
			buttonstart.bind('click.uploadsubmit', function(){ 
				data.submit();
			})	
		})
		
		.bind("fileuploadfail", function(e) {					
					$("button").remove(":contains('Start all uploads')");
					buttonpresent = 0;
				})
		
		.bind('fileuploadstop',function(e,data){
			buttonstart.unbind('click.uploadsubmit'); // important - remove all event handlers
			$('#fileupload button:reset').click();
			$("button").remove(":contains('Start all uploads')");
			buttonpresent = 0;
			$("#dialog_upload").dialog("close");
			alert("All Images Uploaded");
		});
