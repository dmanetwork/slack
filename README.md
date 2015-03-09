# dmanetwork/slack

Small connection to Slack (slack.com) for sending messages to channels via their API.

## Install

Use Composer.

## Simple Usage

Define your Slack options early on with the rest of your application configuration. The only option you MUST specify is the API access token, which you can generate from http://api.slack.com while you are logged in there.

	Nether\Option::Set('slack-token','YOUR-SLACK-TOKEN');

Create an instance of the client to interact with the Slack API.

	$slack = new DMA\Slack\Client;
	
Then you can send messages.

	$slack->Send('Hello from our Slack enabled app.');
	
If you do not change any of the other options you should see a message from Optimus Prime in your #general channel.
	
## Advanced Usage

### More options at app config time...

These are all the options available to set at application config time.

	Nether\Option::Set([
		'slack-token' => 'YOUR-SLACK-TOKEN',
		'slack-default-channel' => '#channel',
		'slack-default-name' => 'bot name of choice',
		'slack-default-icon' => 'url to public accessable image for chat icon',
		'slack-channels' => [ 'action-name' => 'channel', ... ]
	]);
	
### More options at instance time (overwrites prev options)...

These are all the options available to set at instance create time.

	$slack = new DMA\Slack\Client([
		'DefaultChannel' => '#channel',
		'DefaultName' => 'bot name of choice',
		'DefaultIcon' => 'url to icon',
		'Token' => 'YOUR-SLACK-TOKEN'
	]);
	
### More options at message send time...

And these are all the options available to set at message send time.

	$slack->Send('message here',[
		'Channel' => '#DifferentChannel',
		'Name' => 'Different Bot Name',
		'Icon' => 'url to different icon'
	]);

If all you want to do is send to a different channel or user than the default.

	$slack->SendToChannel($chan,$msg);
	
Or send to a different channel as specified by any actions configured.

	// send user-add action notifications to the team.
	$slack->SendToChannel('--user-add',$msg);

### Sending an API request we have not wrapped...

	$slack->SendRequest(string method, array args);

The method name is the final part of the API url specified in the Slack documentation. The argument array then would be an associative array where the keys are the names of the parameters as Slack says, with the values you want to send.

# Copyright

This software is Copyright (c) 2014 Dream Machine Association, LLC. See the bundled LICENSE file (BSD-2-Clause) for more information.
