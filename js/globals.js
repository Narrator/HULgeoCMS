	$(function(){
		// Dialog
		$('#cwzinfo').dialog({
			autoOpen:false,
			width:300,
			height:300,
			position: [900,300]
		});
		$('#dialog_save').dialog({
			autoOpen: false,
			width: 300,
			height: 300,
			close: function() {
				if(stored == false){
					map.removeLayer(drawnItem);
					placed = false;
					diopen = false;
				}
			},
			open: function() {
				document.getElementById("categoriesSelect").selectedIndex = -1;
				document.getElementById("tagsSelect").selectedIndex = -1;
				var innercontent = new String();
				innercontent = "<option value=\"\" style=\"display:none;\"></option>";
				for(var i=0; i<kcategory.length; i++)
				{
					if(type == kcategorytype[i]){
						innercontent = innercontent + "<option>"; 
						innercontent = innercontent + kcategory[i];
						innercontent = innercontent + "</option>";
					}
				}
				document.getElementById('categoriesSelect').innerHTML = innercontent;
			},
			resizable: false,
			buttons: {"Save and close": function() {confirm();}, "Discard and Redraw": function() {discard();}}
		});
		$('#dialog_undo').dialog({
			autoOpen: false,
			width: 170,
			height: 10,
			position: [20,300],
			resizable: false,
			buttons: {"Undo Previous Point": function() {undolast();}}
		});
		$("#dialog_confirm").dialog({
			autoOpen: false,
			width:300,
			height: 270,
			modal: true,
			position: "center",
			open: function(event, ui){
			 setTimeout("$('#dialog_confirm').dialog('close')",8000);
			},
			close: function(event, ui){ 
				$('#confirmation').remove();
			}
		});
		$("#dialog_upload").dialog({
			autoOpen: false,
			width: 500,
			height: 300,
			modal: true,
			position: "center",
			close: function(event, ui){
				$("button").remove(":contains('Start all uploads')");
				buttonpresent = 0;
			}
		});
    });
	
	var placed = false;
	var diopen = false;
	function kaushik(e) {
		if(placed == true && diopen == true) {
			map.removeLayer(drawnItem);
			$('#dialog_save').dialog("close");
		}
	}
	
	//Global variables for all the geometries
	var geojson_Polygon = [],
	geojson_Point = [],
	geojson_Polyline = [],
	polygonid = [],
	pointid = [],
	polylineid = [];
	
	//Global variables for all the categories and tags
	var kcategory = [],
	ktags = [],
	kcategorytype=[],
	kcatid = [],
	ktagcatid = [],
	ktagid = [];
	
	//Global variables for all photolinks and videolinks
	var kphotos = [],
	kvideos = new String();
	
	//Global variables for the upload button feature
	var buttonstart = $('<button type="submit" class="btn btn-primary start"><i class="icon-upload icon-white"></i><span>Start all uploads</span></button>'),
	buttonpresent = 0,
	fileuploadcounter = 0,
	fileaddcounter = 3;
	
	//Global variable to store temp photo array
	var photoarray = [];
	
	//Global variable to store temp cat,tag and video as array
	var cattagvideo = [];
	var checker = 0;
	
	//Global variables for the confirmation box
	var confirmboxtext;
	
	//Global variables for the popupboxes
	var pointcategories=[],
	polylinecategories=[],
	polygoncategories=[];
	
	//Global variables for cat and tags of geometries
	var pointcattag = [],
	polylinecattag=[],
	polygoncattag=[];
	var commentarray = [];
	
	function Circle () {
		this.gid=0,
		this.circle_id=0,
		this.circlename= "",
		this.zone_id= 0,
		this.zonename= "",
		this.circleadd= "",
		this.circlphone= "",
		this.zonalcom= "",
		this.zcnumber= "",
		this.zcemail= "",
		this.the_geom= ""
	};
	
	function Ward () {
		this.gid=0,
		this.ward_id=0,
		this.wardname="",
		this.circle_id=0,
		this.circlename= "",
		this.zone_id= 0,
		this.zonename= "",
		this.elected= "",
		this.party= "",
		this.electphone="",
		this.electemail="",
		this.circleadd="",
		this.circlphone="",
		this.zonalcom= "",
		this.zcnumber= "",
		this.zcemail= "",
		this.the_geom= ""
	};
	
	function Zone () {
		this.gid=0,
		this.zone_id= 0,
		this.zonename= "",
		this.zonalcom= "",
		this.zcnumber= "",
		this.zcemail= "",
		this.the_geom= ""
	};