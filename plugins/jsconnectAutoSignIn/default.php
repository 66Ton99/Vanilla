<?php if (!defined('APPLICATION')) exit();
/**
* # Vanilla jsConnect Auto SignIn #
* 
* ### About ###
* Forces sign in with the first available provider
* 
* ### Sponsor ###
* Special thanks to KyleIrving (www.kyle-irving.co.uk) for making this happen.
*/
$PluginInfo['jsconnectAutoSignIn'] = array(
   'Name' => 'Vanilla jsConnect Auto SignIn',
   'Description' => 'Forces sign in with the first available provider',
   'Version' => '0.1b',
   'RequiredPlugins' => array('jsconnect' => '>=1.0.3b'),
   'Author' => 'Paul Thomas',
   'AuthorEmail' => 'dt01pqt_pt@yahoo.com ',
   'AuthorUrl' => 'http://www.vanillaforums.org/profile/x00'
);

class JsConnectAutoSignInPlugin extends Gdn_Plugin {
	public function Base_Render_Before($Sender, $Args) {
		if (!Gdn::Session()->UserID) {
			$Sender->AddCssFile('jsconnectAuto.css', 'plugins/jsconnectAutoSignIn');
			$Sender->AddJSFile('jsconnectAuto.js', 'plugins/jsconnectAutoSignIn');
			$Sender->AddDefinition('Connecting', T('Connecting','Connecting...'));
			$Sender->AddDefinition('ConnectingUser', T('ConnectingUser','Hi % just connecting you to forum...'));
		}
	}
	
	public function EntryController_JsConnectAuto_Create($Sender, $Args) {
		$client_id = $Sender->SetData('client_id', $Sender->Request->Get('client_id', 0));
		$Provider = JsConnectPlugin::GetProvider($client_id);

		if (empty($Provider))
			throw NotFoundException('Provider');

		$Get = ArrayTranslate($Sender->Request->Get(), array('client_id', 'display'));

		$Sender->SetData('JsAuthenticateUrl', JsConnectPlugin::ConnectUrl($Provider, TRUE));
		$Sender->Render('JsConnect', '', 'plugins/jsconnect');
	}
}
