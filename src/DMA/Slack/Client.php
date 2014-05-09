<?php

/*//
@package dmanetwork/slack
@licence BSD-2-Clause (https://github.com/dmanetwork/slack/blob/master/LICENSE)
@url https://github.com/dmanetwork/slack
@version 20140516.1

This provides some basic functionality to easily automate sending messages to
Slack (slack.com) channels via the Slack API.
//*/

namespace DMA\Slack;

use \DMA;
use \Nether;

////////////////
////////////////

Nether\Option::Define([
	'slack-token' => null,
	/*//option//
	@name slack-token
	@type string
	@default null
	the default api access token for slack communications. you can generate one
	by visiting	https://api.slack.com while logged into your account.
	//*/

	'slack-default-channel' => '#general',
	/*//option//
	@name slack-default-channel
	@type string
	@default "#general"
	the default channel to send messages to if none are specified. we default to
	#general because slack makes that one for you when the account is first
	created.
	//*/

	'slack-default-name' => 'Optimus Prime',
	/*//option//
	@name slack-default-name
	@type string
	@default "Optimus Prime"
	this is the username that will be used when sending messages to slack. it
	does not have to be a registered name or anything. whatever you put here
	will be printed in the app.
	//*/

	'slack-default-icon' => null
	/*//
	@name slack-default-icon
	@type string
	@default null
	if specified this should be the URL to a publically accessable image to use
	as the user icon when sending messages to slack.
	//*/

]);

////////////////
////////////////

class Client {

	protected $DefaultChannel;
	/*//
	@type string
	the default channel to send messages to.
	//*/

	protected $DefaultName;
	/*//
	@type string
	the name used to chat messages.
	//*/

	protected $DefaultIcon;
	/*//
	@type string
	the url to the icon used for chat messages.
	//*/

	protected $Token;
	/*//
	@type string
	the API token used to access slack.
	//*/

	////////////////
	////////////////

	public function __construct($opt=null) {
	/*//
	@argv array/object Options

	to override any of the options that have been previously set in the nether
	options system, you can provide an object or array with the following
	properties:

	* Token => string
	* DefaultChannel => string
	* DefaultName => string
	* DefaultIcon => string
	//*/

		$opt = new Nether\Object($opt,[
			'Token' => Nether\Option::Get('slack-token'),
			'DefaultChannel' => Nether\Option::Get('slack-default-channel'),
			'DefaultName' => Nether\Option::Get('slack-default-name'),
			'DefaultIcon' => Nether\Option::Get('slack-default-icon')
		]);

		$this
		->SetToken($opt->Token)
		->SetDefaultChannel($opt->DefaultChannel)
		->SetDefaultName($opt->DefaultName)
		->SetDefaultIcon($opt->DefaultIcon);

		return;
	}

	////////////////
	////////////////

	public function SetDefaultChannel($channel) {
	/*//
	@argv string Channel
	@return self
	sets the channel messages will be sent to by default. e.g. #general.
	//*/

		$this->DefaultChannel = $channel;
		return $this;
	}

	public function SetDefaultName($name) {
	/*//
	@argv string Name
	@return self
	sets the user name messages will come from by default.
	//*/

		$this->DefaultName = $name;
		return $this;
	}

	public function SetDefaultIcon($icon) {
	/*//
	@argv string Icon
	@return self
	sets the URL to be used for the icon associated with the user name messages
	are coming from.
	//*/

		$this->DefaultIcon = $icon;
		return $this;
	}

	public function SetToken($token) {
	/*//
	@argv string Token
	@return self
	sets the API token key thing required to access the Slack API.
	//*/

		$this->Token = $token;
		return $this;
	}

	////////////////
	////////////////

	public function SendRequest($method,$query) {
	/*//
	@argv string SlackMethod, object/array QueryInput

	the method property is the name slack uses for the API endpoint. the query
	input is an array of all the data that api method expects. this will let you
	send requests for things we may not have already wrapped fairly easily. if
	we have wrapped it though (like chat.postMessage) using that method should
	make life nice.

	* method: chat.postMessage
	* query: [ 'channel' => '#general', 'text' => 'message to send' ]

	slack is accepting GETs for these things, even when the API docs suggest
	POST. until that breaks, gonna do this the really nice easy lolly way rather
	than dicking about with curl handles.
	//*/

		$query = new Nether\Object($query,[
			'token' => $this->Token
		]);

		$request = sprintf(
			'https://slack.com/api/%s?%s',
			$method,
			http_build_query($query)
		);

		if(!ini_get('allow_url_fopen'))
		throw new \Exception('allow_url_fopen is disabled in your PHP.INI.');

		$result = json_decode(file_get_contents($request));
		if(!$result || $result->ok === 'false') return false;

		return true;
	}

	////////////////
	////////////////

	public function SendToChannel($message,$opt=null) {
	/*//
	@argv string Message
	@argv string Message, object/array Options

	send a message to the channel. if options are not specified then all the
	defaults that have been configured will be used. valid options to overwrite
	the defaults for this request are:

	* Channel => string
	* Name => string
	* Icon => string
	//*/

		$opt = new Nether\Object($opt,[
			'Channel' => $this->DefaultChannel,
			'Name'    => $this->DefaultName,
			'Icon'    => $this->DefaultIcon
		]);

		return $this->SendRequest('chat.postMessage',[
			'username' => $opt->Name,
			'icon_url' => $opt->Icon,
			'channel'  => $opt->Channel,
			'text'     => $message
		]);
	}
}
