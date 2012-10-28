<!-- Panel -->
<div id="toppanel-wrapper">
<div id="toppanel">
	<div id="panel">
		
		<div class="content clearfix">
			 <div class="left">
				<h1>Hyderabad Urban Lab</h1>
				<h2>Please register yourself to contribute to this community. A short introduction can also be here</h2>
			</div>
            <?php
			
			if(!isset($_SESSION['id'])):
			
			?>

			<div class="left">
				<!-- Login Form -->
				<form class="clearfix" action="" method="post">
					<h1>Member Login</h1>
                    
                    <?php
						
						if(isset($_SESSION['msg']['login-err']))
						{
							echo '<div class="err">'.$_SESSION['msg']['login-err'].'</div>';
							unset($_SESSION['msg']['login-err']);
						}
					?>
					<label class="grey" for="username">Username:</label>
					<input class="field" type="text" name="username" id="username" value="" size="23" />
					<label class="grey" for="password">Password:</label>
					<input class="field" type="password" name="password" id="password" size="23" />
	            	<label><input name="rememberMe" id="rememberMe" type="checkbox" checked="checked" value="1" /> &nbsp;Remember me</label>
        			<div class="clear"></div>
					<input type="submit" name="submit" value="Login" class="bt_login" />
				</form>
			</div>
			<div class="left right">			
				<!-- Register Form -->
				<form action="" method="post">
					<h1>Not a member yet? Sign Up!</h1>		
                    
                    <?php
						
						if(isset($_SESSION['msg']['reg-err']))
						{
							echo '<div class="err" style="color:red;">'.$_SESSION['msg']['reg-err'].'</div>';
							unset($_SESSION['msg']['reg-err']);
						}
						
						if(isset($_SESSION['msg']['reg-success']))
						{
							echo '<div class="success" style="color:green;">'.$_SESSION['msg']['reg-success'].'</div>';
							unset($_SESSION['msg']['reg-success']);
						}
					?>
                    <p class="grey" style="color:red;">All fields are required</p>	
					<label class="grey" for="username">Username:</label>
					<input class="field" type="text" name="username" id="username" value="" size="23" />
					<label class="grey" for="email">Email:</label>
					<input class="field" type="text" name="email" id="email" size="23" />
					<label class="grey" for="name">Name:</label>
					<input class="field" type="text" name="name" id="name" size="23" />
					<label class="grey" for="phone">Phone:</label>
					<input class="field" type="text" name="phone" id="phone" size="23" />
					<label class="grey" for="address">Address:</label>
					<input class="field" type="text" name="address" id="address" size="23" />
					<label>A password will be e-mailed to you.</label>
					<input type="submit" name="submit" value="Register" class="bt_register" />
				</form>
			</div>
            
            <?php
			
			else:
			
			?>
            <div class="left">
                             
				<h2>Username: </h2><?php echo $_SESSION['usr'];?>
				<h2>Name: </h2><?php echo $_SESSION['name'];?>
				<h2>E-mail: </h2><?php echo $_SESSION['email'];?>
				<h2>Phone: </h2><?php echo $_SESSION['phone'];?>
				<h2>Address: </h2><?php echo $_SESSION['address'];?>
				<h2>Last Login: </h2><?php echo $_SESSION['last_login'];?>
				<h2>Registered Date: </h2><?php echo $_SESSION['reg_date'];?>
				<h2>User access level: </h2><?php if($_SESSION['access_level']==1) echo 'Moderator'; else echo "User";?>
				
			</div>
            <div class="left">
            
            <h1>Members panel</h1>
            
            <p><a href="index.php?action=newArticle">Add a New Article</a></p>
            <p>- or -</p>
            <a href="?logoff">Log off</a>
            
            </div>
            
            <div class="left right">
            </div>
            
            <?php
			endif;
			?>
		</div>
	</div> <!-- /login -->	

    <!-- The tab on top -->	
	<div class="tab">
		<ul class="login">
	    	<li class="left">&nbsp;</li>
	        <li>Hello <?php echo isset($_SESSION['usr']) ? $_SESSION['usr'] : 'Guest';?>!</li>
			<li class="sep">|</li>
			<li id="toggle">
				<a id="open" class="open" href="#"><?php echo isset($_SESSION['id']) ?'Open Panel':'Log In | Register';?></a>
				<a id="close" style="display: none;" class="close" href="#">Close Panel</a>			
			</li>
	    	<li class="right">&nbsp;</li>
		</ul> 
	</div> <!-- / top -->
	
</div> <!--panel -->
</div>
