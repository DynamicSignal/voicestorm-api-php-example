#Introduction

VoiceStorm is a platform that allows you to create advocacy communities for a brand, organization, or cause. Community managers source and distribute approved content to members. Members share the content on their social channels, thereby amplifying the brand message. Learn more at http://www.dynamicsignal.com.

##Purpose of this test package:
While you can use the stock VoiceStorm member hub with any VoiceStorm community, that allows Member sign-in and profile creation many organizations want to use the VoiceStorm APIs (documented at [dev.voicestorm.com](http://dev.voicestorm.com)) to sync existing user data from their own server to VoiceStorm community. This test package is intended to help you do just that.

This test package includes server to server calls to create/manage users, divisions and affiliations (profile questions) as below:

<ol>
<li>Authenticate server side.</li><li>Look up user in VoiceStorm by email address to modify divisions/affiliations (Profile Questions).</li><li>If the user does not exist, create with First Name, Last Name, Password and email address.</li><li>Remove any divisions assigned to the user.</li><li>Set to the first existing division (You can extend it to custom divisions).</li><li>Remove all the answers for profile questions.</li><li>Set all the answers (You can extend this to include custom answers).</li>
</ol>

You can use it as an example/guide when syncing user data to VoiceStorm hub and can extend it to the way you actually want it.

##Download, install, and run to sync user data:

To sync user data to your own VoiceStorm instance, you will need to download the sample code and modify it to point at your own VoiceStorm instance. The guide below will walk you through this process in 4 steps:

1.    **Set up your webserver** to run and install the test package. Once installed properly, the test package will run against your VS instance.
2.	Contact Dynamic Signal and **get your own VS instance** and API credentials.
3.	Modify the test package in order to **point it at your new VS instance.**
4.	**Test it!**

The sample site uses REST APIs to make authentic calls to the server. Further reference docs and required documentation are available at http://dev.voicestorm.com/. Once you go through this guide, you should have enough background to make server to server calls to modify Users.

###Step 1: Set up webserver/environment

<ol>
<li>Install WAMP, or comparable webserver.</li>
<ol>
<li>Actual requirements are:</li>
<ol>
<li>Webserver</li>
<li>PHP</li>
<li>CURL</li>
<li>HTTPS cert</li>
</ol><li>No database is required</li>
</ol><li>Test the environment:</li>
<ol>
<li>Ensure CURL is installed.</li>
<li>Ensure PHP is installed.</li>
</ol><li>Download the code to the desired directory within the WAMP file structure.</li>
</ol>

You should now be able to run the code. Try it by opening up your install location in a browser. Next, let’s get you set up on your own VoiceStorm instance.

###Step 2: Obtain your VoiceStorm instance

You will need to contact DS and request an instance of VoiceStorm with API access.  Be sure you get the following from the DS rep:

<ol>
<li>URL for the new community ([example].voicestorm.com)</li>
<li>The Admin -> API should be visible in the manager application ([example].voicestorm.com/manage/api), and this information should be available in that tab:</li>
<ol>
<li>Access Token</li>
<li>Token Secret</li>
<li>REST API Base URL</li>
</ol>
</ol>

###Step 3: Modify test package to point at your own VoiceStorm instance

Download the code to your machine, and make the following changes, using tokens and URLs found at Admin -> API ([example]voicestorm.com/manage/api).

<table>
<tr>
<th>File</th>
<th>Code Line</th>
<th>From API Page</th>
</tr>
<tr>
<td rowspan="3">config.php</td>
<td>$voicestormAccessToken</td>
<td>Access Token</td>
<tr>
<td>$voicestormTokenSecret</td>
<td>Token Secret
</tr>
<tr>
<td>$voicestormBaseUrl</td>
<td>REST API Base URL</td>
</tr>
</table>

**Examples**

```php
$voicestormAccessToken="XXXXXXXXXXXXXXXXXXXX";
$voicestormTokenSecret= "XXXXXXXXXXXXXXXXXXXX";
$voicestormBaseUrl="https://[example].voicestorm.com/v1";
```

###Step 4: Testing

Please test the following:

Enter non-existing email address and you should be able to see the new user with divisions and affiliations created. 
Now open manager app -> Members to see the newly created user with custom divisions and affiliations (Profile Questions).

**Have any questions?**

We are eager to hear them. Email us at [info@dynamicsignal.com](mailto:info@dynamicsignal.com)
