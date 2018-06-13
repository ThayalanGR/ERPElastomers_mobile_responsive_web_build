// ----- Default Functions---------------------------------------------------------------------------------------------------------------- //
invList		= 	new Array();

function waitAndCall()
{
	setTimeout(function(){createAutoComplete();}, 1000);
}

function createAutoComplete(){
	
	invRef			=	$("#invRef").val();
	//console.log(invRef)
	if(invRef != "" && invRef != null)
	{
		var mouldList   =	postback(actionFile,"type=GETDEF&invref="+invRef.toLowerCase(),"POST","XML");
		//alert(mouldList);
		invXML			=	parseXMLdoc(mouldList);
		rowXML			=	invXML.getElementsByTagName("row");	
		if(rowXML[0] != null && rowXML[0] != "undefined")
		{
			row			=	rowXML[0].childNodes;
			data		=	new Array();		
			for(cx=0; cx<row.length; cx++){
				data[row[cx].nodeName]	=	(row[cx].firstChild)?row[cx].firstChild.nodeValue:'';
			}
			invId		=	data['invid'];
			docType		=	data['doctype'];
			invQty		=	data['invqty'];
			if(invList.indexOf(invId) == -1)
			{
				invList.push(invId);
				addr		=	data['invconsignee'].split("|");
				consignee	=	addr[0]; //+"<BR />"+addr[2];
				numPacks	=	getCurrency(data['numpacks'],0);
				objList		=	$('#content_body');
				ol			=	objList.length;
				nextRow		=	ol + 1;
				newRow		=	`<div class="col-12 mt-2" id="${nextRow}" docid="${invId}" doctype= "${docType}" invqty = "${invQty}">
                                                    <div class="container shadow text-left">
                                                            <div class="row ">
                                                                <div class="col-5 bg-dark" >S.No</div>
                                                                <div class="col-7 bg-dark">${nextRow}</div>
                                                            </div>
                                                            <div class="row border-bottom">
                                                                <div class="col-5">Inv. Ref</div>
                                                                <div class="col-7 text-success">${invId}</div>
                                                            </div>
                                                            <div class="row border-bottom">
                                                                <div class="col-5">Inv. Date</div>
                                                                <div class="col-7 text-success">${data}</div>
                                                            </div>
                                                            <div class="row border-bottom">
                                                                <div class="col-5">Consignee</div>
                                                                <div class="col-7 text-success">${consignee}</div>
                                                            </div>
                                                            <div class="row border-bottom">
                                                                <div class="col-5">Part Number</div>
                                                                <div class="col-7 text-success">${data['partnumber']}</div>
                                                            </div>
                                                            <div class="row border-bottom">
                                                                <div class="col-5">Total Qty</div>
                                                                <div class="col-7 text-success">${getCurrency(invQty,0)}</div>
                                                            </div>
                                                            <div class="row border-bottom">
                                                                <div class="col-5">Inv. Amount</div>
                                                                <div class="col-7 text-success">${getCurrency(data['invgrandtotal'],2)}</div>
                                                            </div>
                                                            <div class="row border-bottom">
                                                                <div class="col-5">No. of Packets</div>
                                                                <div class="col-7 text-success"><input id="input_${nextRow}" style="" class='invisible_text d-none' value="${numPacks}"  onFocus="FieldHiddenValue(this, 'in', '${numPacks}')" onBlur="FieldHiddenValue(this, 'out', '${numPacks}')"></input></div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-5">Remove</div>
                                                                <div class="col-7 "><button class="btn btn-danger btn-sm" type="button" id="delrm_${nextRow}" target='absmiddle' ><i class="fa fa-times"></i></button></div>
                                                            </div>
                                                    </div> 
                                                </div>`;
	
				if(ol > 0)
				{	
					$('#content_body').after(newRow);		
				}
				else
				{	
					$('.window_error').html("");
					newTable	=	newRow;
					$('#content_body').html(newTable);
					
				}
				
				$("#delrm_" + nextRow).click(new Function("$(\"#"+nextRow+"\").remove(); updateAllItems();"));	
			}
			else
			{
				displayError($("#new_item_error"), "error", "<strong>Error !</strong> - Item:"+invId+" Already Added!!!" );		
			}		
		}
		else
		{
			displayError($("#new_item_error"), "error", "<strong>Error !</strong> - No Such Item "+invRef+" or Item Already Despatched!!!" );	
		}
		$("#invRef").val('');
		
	}

}

function updateAllItems(){
	$("#content_body").each(function(index, element) {
		$(element).removeClass((index%2)?'content_rows_dark':'content_rows_light');
        $(element).addClass((index%2)?'content_rows_light':'content_rows_dark');
		$(element).find("td:first").text((index+1));
    });
}

// --------------------------------------------------------------------------------------------------------------------------------------- //


$(document).ready(function(){

	$("#invRef").focus();	

	$("#button_submit").button().click(function(){
		newError	=	$("#new_item_error");
		pickedBy	=	$("#new_PickedBy");
		vechNumber	=	$("#new_VehicleNum");
		if(pickedBy.val() == null || pickedBy.val() == ""){
			displayError(newError, "error", "<strong>Error !</strong> - Pickup Person Name Missing.");
			pickedBy.focus();
			return false;
		}
		else if(vechNumber.val() == null || vechNumber.val() == "" ){
			displayError(newError, "error", "<strong>Error !</strong> - Vehicle Number Missing.");
			vechNumber.focus();
			return false;
		}		
		invIds 		= 	new Array();		
		invTypes 	= 	new Array();
		invQtys		= 	new Array();
		numPacks	= 	new Array();
		planList	=	$('#content_body table tr');
		for(rl=0; rl<planList.length; rl++){
			fetchId		=	$("#"+planList[rl].id).attr("id");			
			numPack		=	$("#input_" + fetchId).val();
			if (numPack.toNumber() > 0)
			{
				numPacks.push(numPack);
				invIds.push($("#"+fetchId).attr("docid"));				
				invTypes.push($("#"+fetchId).attr("doctype"));
				invQtys.push($("#"+fetchId).attr("invqty"));
			}
			else
			{
				displayError(newError, "error", "<strong>Error !</strong> - Please enter correct Packet Numbers: "+fetchId+".");
				$("#input_"+fetchId).focus();
				return false;
			}
		}
		if(invIds.length > 0)
		{
			param			=	"type=" + "INSDESPDETS" +
								"&pickedby=" + pickedBy.val() +
								"&vehnumber="+vechNumber.val() ;
			arrParams		=	"";
			for(rm=0; rm<invIds.length; rm++){
				arrParams = arrParams + "&docIds[]=" + invIds[rm] ;
				arrParams = arrParams + "&docTypes[]=" + invTypes[rm] ;
				arrParams = arrParams + "&totQtys[]=" + invQtys[rm] ;
				arrParams = arrParams + "&numPacks[]=" + numPacks[rm] ;
			}
			param	= param + arrParams;			
			confirmCont = '<table width="100%" >' +
				'<tr><td><b>Are you Sure to Update Despatch Details?</b></td></tr>' +
				'</table>'	;		
			$("#confirm_dialog").html(confirmCont).dialog({
													title:'Despatch Entry',
													width:450,
													height:'auto',
													resizable:false,
													modal:true,
													buttons: [
														{
															text: "Yes",
															click: function(){
																$(this).dialog("close");
																//alert(param); return false;
																error	=	postback(actionFile,param,"POST","XML");
																if(error == "success"){
																	displayError(newError, "highlight", "<strong>Info !</strong> - Despatch Details Updated Successfully");
																	setTimeout(function(){window.location.reload();}, 500);
																}
																else{
																	displayError(newError, "error", "<strong>Error !</strong> - Failed to Create Plans - " + error );
																	return false;
																}																
															}
														},
														{
															text: "No",
															click: function() { $(this).dialog("close"); }
														}
													],
													close: function(event, ui) {
															$(this).dialog("destroy");
													} 
			});
		}
		else	
		{
			displayError(newError, "error", "<strong>Error !</strong> - Please enter values for atleast one invoice!");
			return false;			
		}
	});	

	//Cancel Items
	$("#button_cancel").button().click(function(){
		document.getElementById('errorId').innerHTML = `<div class="bg-light ">Are you sure to clear?<br>
		<span class="text-center"><button class="btn btn-sm btn-danger" onClick="window.location.reload();">Yes</button></span>
		<button class="btn btn-sm btn-success" id="noClear" onClick="noClear();" >No</button>
		</div>`	
	});

	

});


function noClear(){

	var displayError = document.getElementById('errorId')

	displayError.innerHTML = ' '
	return 1;
	

}
	