<div class="" style="margin-top:100px;">
<div class="row justify-content-center mt-5">
    <div class="jumbotron-fluid shadow-sm rounded-circle text-center" style="height: 85px; width:85px; "> 
        <img src="<?php echo ISO_REWRITE_URL; ?>images/mmpl logo.jpg" style="height:50px; width: 50px; " class="mt-3"  alt="logo">
    </div>           
</div>

<div class="row justify-content-center text-center mt-5">
    <form action="#" class="" onsubmit="return false" >
        <h5> <span> <i class="fa fa-lock  mt-3"></i> </span> Login</h5>
        <div class="alert alert-warning" style="display:none;" id="err_msg"></div>
        <div class="input-container form-control-sm mt-3">
            <i class="fa fa-user mt-2 mr-2 text-primary"></i>
            <input class="input-sm form-control" type="text" placeholder="Username"  id="username" autofocus >
        </div>
        
        <div class="input-container form-control-sm">
            <i class="fa fa-key mt-2 mr-2 text-primary"></i>
            <input class="input-sm form-control" type="password" placeholder="Password" id="password">
        </div>
        
        <button type="submit" class="btn btn-block btn-sm btn-primary" onclick="loginUser()" id="login">Login</button>
    </form>            
</div>    
</div>