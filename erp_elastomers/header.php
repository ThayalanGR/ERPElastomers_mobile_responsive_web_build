<?php

	$userName	=	$_SESSION['userdetails']['userName'];
	$fullName	=	$_SESSION['userdetails']['fullName'];
	$cName		=	$fullName;
	
	if(strlen($cName) > 0 && strlen($cName) > 10){
		$cName	=	substr($cName, 0, 10)." . . .";
	}
?>
<?php if(ISO_LOAD_MODULE != "login"): ?> 
    <div class="row justify-content-between fixed-top shadow-sm " style="height:50px; background-color:white; ">
        <div id="homelogo" class="col-sm-2 col-md-1   mt-0 text-left ml-4 ml-md-3" style=" width: 40px;">
            <div class="jumbotron-fluid shadow-sm rounded-circle btn btn-primary text-center mt-1"  style="height: 40px; width:40px; background-color: white; "> 
                <img src="../images/mmpl logo.jpg" style="height:20px; width: 20px; " class=""  alt="logo">
            </div>
            
        </div>
        <div id="userlogo" class="col clearfix" style="">
                <p class="float-left text-left text-primary mt-2 ml-1" style="font-size: 13px;">
                    <span class="badge "><i class="fa fa-user"></i></span><?php echo $userName." - "?> 
                    <br><span class="ml-1 text-info"><?php echo $cName ?> </span>
                </p>
                <a href="<?php echo (ISO_REWRITE_URL)?"/Logout":"?module=Logout"; ?>" class="btn shadow-sm mr-2   mr-md-4 text-right text-primary float-right" style="padding:0px; margin-top: 12px;">
                    <span class="badge"><i class="fa fa-sign-out-alt fa-2x  "></i></span>
                </a>
                <a href="<?php echo ISO_REWRITE_URL ; ?>" class="btn shadow-sm mr-3 text-primary btn-sm  mr-md-4 text-right text-primary float-right" style="padding:0px; margin-top: 12px;">
                                <span class="badge"><i class="fas fa-home fa-2x " ></i></span>
                </a>
        </div>
    </div>
    <div class="row justify-content-center" style="height:100em; padding-top: 65px;" >
    <div class="container-fluid "> 
        <div class="row"  style="padding-left: 10px; padding-right: 10px; ">   
        <?php
            // Get Menus
            $menuHeadSQL		=	@getMySQLData("select * from tbl_menu_head where status>0 order by menu_order asc");
            $menuCount			=	$menuHeadSQL['count'];
            $menuHead			=	$menuHeadSQL['data'];
            $menuSubCount		=	0;
            $menuSub			=	array();
            for($mh=0; $mh<$menuCount; $mh++){
                // Get Sub Menus
                $menuSubSQL		=	@getMySQLData("select * from tbl_menu_sub where menu_head='".$menuHead[$mh]['menu_head']."' and visible>0 and status>0 order by menu_order asc");
                $menuSubCount	=	$menuSubSQL['count'];
                $menuSub		=	$menuSubSQL['data'];
                $subMenu		=	' <div class="modal-body container-fluid"><div class="row">';
                $currHdMenu		=	"";					
                for($sm=0; $sm<$menuSubCount; $sm++){
                    $chkLink	=	in_array($menuSub[$sm]['autoId'], $_SESSION['userdetails']['userSubPermissions']);
                    $subLink	=	((!ISO_IS_REWRITE)?"?module=":"/").preg_replace("/[ ]/", '', $menuHead[$mh]['menu_head']."/".$menuSub[$sm]['menu_sub']);
                    if($menuSub[$sm]['menu_div'] != '')
                    {								
                        if($currHdMenu != $menuSub[$sm]['menu_div'])
                        {
                            $currHdMenu	=	$menuSub[$sm]['menu_div'];
                            $currHdMenuId = $menuSub[$sm]['menu_div'];
                            $menuDivIcon  = $menuSub[$sm]['menu_div_icon'];
                            if($currHdMenu == 'eWay Bill'){
                                $currHdMenuId = 'eWayBill';
                            }
                            if($currHdMenu == 'Compound '){
                                $currHdMenuId = 'CompoundId';
                            }
                            if($currHdMenu == 'Component'){
                                $currHdMenuId = 'ComponentId';
                            }
                            if($currHdMenu == 'Incoming Compound'){
                                $currHdMenuId = 'IncomingCompound';
                            }
                            if($currHdMenu == 'Approved List'){
                                $currHdMenuId = 'ApprovedList';
                            }

                            $subMenu	.=	'<div class="col btn hover1 text-center " style="" data-toggle="collapse" href="#'. $currHdMenuId.'" role="button" aria-expanded="false" aria-controls="collapseExample" ><div class="text-primary dropdown-toggle"><i class="'.$menuDivIcon.' fa-2x"> </i><br>'. $currHdMenu.' </div></div>
                                            <div class="collapse" id="'.$currHdMenuId.'">
                                                <div class="card card-body container-fluid">
                                                    <div class="row text-warning">
                                                            ' ;
                        }
                        $menuSubIcon  = $menuSub[$sm]['menu_sub_icon'];
                        $subMenu	.=	($chkLink)
                                            ?'
                                            <a class="col btn hover1 text-center " href="'.$subLink.'" style="" ><div class="text-warning"><i class="'.$menuSubIcon.' fa-2x"> </i><br>'.$menuSub[$sm]['menu_sub'].'</div></a>
                                            '
                                            :'                            
                                           
                                            ';
                    }
                    else if ($currHdMenu != "")
                    {
                        $subMenu .= '</div></div></div>';
                        $currHdMenu	=	"";
                    }
                    else
                    {
                        $menuSubIcon  = $menuSub[$sm]['menu_sub_icon'];
                        $subMenu	.=	($menuSub[$sm]['menu_sub'] != '-')
                                        ?($chkLink)
                                            ?'
                                            <a class="col btn hover1 text-center " href="'.$subLink.'" style="" ><div class="text-success"><i class="'.$menuSubIcon.' fa-2x"> </i><br>'.$menuSub[$sm]['menu_sub'].'</div></a>
                                           '
                                            :'
                                            
                                          '
                                        :'';
                    }
                }
                $subMenu		.=	'</div></div>';

                
                $mainLogo = '';
                if($menuHead[$mh]['menu_head'] == 'NPD'){
                    $mainLogo = 'fa fa-shield-alt';
                }
                if($menuHead[$mh]['menu_head'] == 'Compound'){
                    $mainLogo = 'fab fa-connectdevelop';
                }
                if($menuHead[$mh]['menu_head'] == 'Component'){
                    $mainLogo = 'fas fa-puzzle-piece';
                }
                if($menuHead[$mh]['menu_head'] == 'Quality'){
                    $mainLogo = 'fas fa-certificate';
                }
                if($menuHead[$mh]['menu_head'] == 'Sales'){
                    $mainLogo = 'fas fa-chart-line';
                }
                if($menuHead[$mh]['menu_head'] == 'Management'){
                    $mainLogo = 'fas fa-user-tie';
                }
                $displayMenu = '';
                if ((in_array($menuHead[$mh]['autoId'], $_SESSION['userdetails']['userPermissions']))){
                    $displayMenu = '<div class="col-6 btn hover1 text-center"  style="" data-toggle="modal" data-target="#'.$menuHead[$mh]['menu_head'].'"><div class="mt-4 text-primary"><i class="'.$mainLogo.' fa-3x"></i><br>'.$menuHead[$mh]['menu_head'].'</div></div>';

                }
                else{
                    $displayMenu = '';
                }
                // Output Menu
                echo '
                '.((in_array($menuHead[$mh]['autoId'], $_SESSION['userdetails']['userPermissions']))
                                ?($menuHead[$mh]['menu_link'])
                                    ?'<a class="bb" href="?module='.$menuHead[$mh]['menu_head'].'">'
                                    :''
                                :'').''.$displayMenu.'
                        '.((in_array($menuHead[$mh]['autoId'], $_SESSION['userdetails']['userPermissions']))
                                ?($menuHead[$mh]['menu_link'])
                                    ?'</a>'
                                    :''
                                :'').(
                                    (in_array($menuHead[$mh]['autoId'], $_SESSION['userdetails']['userPermissions']))
                                    ?'<div class="modal fade" id="'.$menuHead[$mh]['menu_head'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header " style="height: 50px; ">
                                                    <h6 class="modal-title" id="exampleModalLongTitle"><i class="'.$mainLogo.'"> </i> '.$menuHead[$mh]['menu_head'].'</h6>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <i class="fas fa-home " ></i>
                                                    </button>
                                                </div>
                                                '.$subMenu.'
                                            </div>
                                        </div>
                                    </div>'
                                    :''
                                ).'
                
                ';
            }
        ?>     
           </div>                 
        </div>         
    </div>
<?php endif; ?>


<!-- <a class="col btn text-center disabled " href="#" style="" ><div class="text-success"><i class="'.$menuSubIcon.' fa-2x"> </i><br>'.$menuSub[$sm]['menu_sub'].'</div></a> -->



 <!-- <a class="col btn text-center disabled " href="#" style="" ><div class="text-warning"><i class="'.$menuSubIcon.' fa-2x"> </i><br>'.$menuSub[$sm]['menu_sub'].'</div></a> -->