<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-ca">
<head>
   <?php $this->RenderAsset('Head'); ?>
   <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700' rel='stylesheet' type='text/css' />
<?php $HeadString .='
              <!--[if lte IE 8]><link rel="stylesheet" href="'.$this->Context->
StyleUrl.'ie8.css" type="text/css" media="screen" /><![endif]-->';

$HeadString .= ' <link rel="stylesheet" type="text/css" href="'.$StyleSheet['Sheet'].'"'.($StyleSheet['Media'] == ''?'':' media="'.$StyleSheet['Media'].'"').' /> <!--[if IE]> <link rel="stylesheet" href="ie.css" type="text/css" media="screen" /> <![endif]-->';
echo $HeadString;
	  ?>
      

</head>
<body id="<?php echo $BodyIdentifier; ?>" class="<?php echo $this->CssClass; ?>">
<div class="Wrapper">
  <div class="MainContainer">
    <div id="Frame" class="MainFrame">

       <div id="Head">
         <div class="Menu">
           <div class="TopControl">
            <!--<h1><a class="Title" href="<?php echo Url('/'); ?>"><span><?php echo Gdn_Theme::Logo(); ?></span></a></h1>--> 
            <?php
			      $Session = Gdn::Session();
					if ($this->Menu) {
						$this->Menu->AddLink('Dashboard', T('Dashboard'), '/dashboard/settings', array('Garden.Settings.Manage'));
						// $this->Menu->AddLink('Dashboard', T('Users'), '/user/browse', array('Garden.Users.Add', 'Garden.Users.Edit', 'Garden.Users.Delete'));
						$this->Menu->AddLink('Activity', T('Activity'), '/activity');
						if ($Session->IsValid()) {
							$Name = $Session->User->Name;
							$CountNotifications = $Session->User->CountNotifications;
							if (is_numeric($CountNotifications) && $CountNotifications > 0)
								$Name .= ' <span class="Alert">'.$CountNotifications.'</span>';

                     if (urlencode($Session->User->Name) == $Session->User->Name)
                        $ProfileSlug = $Session->User->Name;
                     else
                        $ProfileSlug = $Session->UserID.'/'.urlencode($Session->User->Name);
							$this->Menu->AddLink('User', $Name, '/profile/'.$ProfileSlug, array('Garden.SignIn.Allow'), array('class' => 'UserNotifications'));
							$this->Menu->AddLink('SignOut', T('Sign Out'), SignOutUrl(), FALSE, array('class' => 'NonTab signout_icon SignOut'));
						} else {
							$Attribs = array();
							if (SignInPopup() && strpos(Gdn::Request()->Url(), 'entry') === FALSE)
								$Attribs['class'] = 'SignInPopup';
								
							$this->Menu->AddLink('Entry', T('Sign In'), SignInUrl($this->SelfUrl), FALSE, array('class' => 'NonTab'), $Attribs);
						}
						echo $this->Menu->ToString();
					}
				?>
            <div class="Search"><?php
					$Form = Gdn::Factory('Form');
					$Form->InputPrefix = '';
					echo 
						$Form->Open(array('action' => Url('/search'), 'method' => 'get')),
						$Form->TextBox('Search'),
						$Form->Button('Search Forum', array('Name' => '')),
						$Form->Close();
				?></div>
           </div>
         </div>
      </div>
       <div id="Body">
         <div id="Content"><?php $this->RenderAsset('Content'); ?></div>
         <div id="Panel"><?php $this->RenderAsset('Panel'); ?></div>
      </div>
       <div id="Foot">
			<?php
				//$this->RenderAsset('Foot');
				//echo Wrap(Anchor(T('Powered by MadebyAtheists'), C('Garden.VanillaUrl')), 'div');
				
				//echo '<div><a href="#" >Powered by MadebyAtheists</a></div>'; // This line is added by Shakti and Skant on 09-04-2012
			?>
		</div>
    </div>   
   </div>
	<?php $this->FireEvent('AfterBody'); ?>
</div>    
</body>
</html>
