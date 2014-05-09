# dmanetwork/slack

Small connection to Slack (slack.com) for sending messages to channels via their API.

## Install

Use Composer.

Currently depends on the development branch of Nether\Object and Nether\Option, so if you are installing this via Composer you will need to add the min-stability set to "dev" - that will soon change so that will not be needed. This package IS registered via Packagist.org.

	{
		"min-stability": "dev",
		"require": { "dmanetwork/slack": "dev-master" }
	}

## Simple Usage

Define your Slack options early on with the rest of your application configuration. The only option you MUST specify is the API access token, which you can generate from http://api.slack.com while you are logged in there.

	Nether\Option::Set('slack-token','YOUR-SLACK-TOKEN');

Create an instance of the client to interact with the Slack API.

	$slack = new DMA\Slack\Client;
	
Then you can send messages.

	$slack->SendToChannel('Hello from our Slack enabled app.');
	
If you do not change any of the other options you should see a message from Optimus Prime in your #general channel.
	
## Advanced Usage

### More options at app config time...

	Nether\Option::Set([
		'slack-token' => "YOUR-SLACK-TOKEN",
		'slack-default-channel' => '#channel',
		'slack-default-name' => 'bot name of choice',
		'slack-default-icon' => 'url to public accessable image for chat icon'
	]);
	
### More options at instance time (overwrites prev options)...

	$slack = new DMA\Slack\Client([
		'DefaultChannel' => '#channel',
		'DefaultName' => 'bot name of choice',
		'DefaultIcon' => 'url to icon',
		'Token' => 'YOUR-SLACK-TOKEN'
	]);

### Sending an API request we have not wrapped...

	$slack->SendRequest(string method, array args);

The method name is the final part of the API url specified in the Slack documentation. The argument array then would be an associative array where the keys are the names of the parameters as Slack says, with the values you want to send.

