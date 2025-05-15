<link href="<?php echo Doo::conf()->APP_URL ?>global/prism.css" rel="stylesheet" media="all">
<script src="<?php echo Doo::conf()->APP_URL ?>global/prism.js"></script>
<style>
    @media print {
        @page {
            size: portrait;
            /* Force portrait mode */
        }

        /* Ensure all tab content is visible during print */
        .card-title {
            display: inline-block !important;
        }

        .btn {
            visibility: hidden !important;
        }

        .code-box {
            height: auto !important;
            max-height: none !important;
            overflow: visible !important;
        }

        .sidebar {
            display: none !important;
        }

        /* Remove margin/padding that was used to account for the sidebar */
        .content,
        .main-content {
            margin-left: 0 !important;
            padding-left: 0 !important;
        }

        /* Center the content */

        #app-main {
            width: 100% !important;
        }

        .tab-pane {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* Optionally remove navigation elements if you don't want to print the tabs themselves */
        .nav-tabs {
            display: none;
        }

        #breadcrumbs-one {
            display: none;
        }

        #app-navbar {
            display: none;
        }

        #menubar {
            display: none;
        }

        pre[class*="language-"],
        code[class*="language-"] {
            background: #f5f2f0;
            color: #000;
        }

        h4 {
            margin-top: 60px;
            margin-bottom: 24px;
        }
    }

    .code-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 1rem;
        margin-bottom: 1rem;
        overflow-x: auto;
    }

    .card-header {
        background-color: #f7f7f7;
        border-bottom: 1px solid #ddd;
    }

    pre {
        margin-bottom: 0;
    }

    code {
        border: none !important;
    }
</style>

<main id="app-main" class="app-main">
    <?php include('breadcrums.php') ?>
    <div class="wrap">
        <section class="app-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget p-lg">
                        <div class="row no-gutter">
                            <h3 class="page-title-sc clearfix">OTP API<small><?php echo SCTEXT('easy APIs for One-time Password integration') ?></small><button type="button" class="btn btn-sm btn-primary pull-right" id="printButton" onclick="window.print()"><i class="fa fa-print"></i></button></h3>
                            <hr>
                            <?php include('notification.php') ?>
                            <div class="col-md-12">
                                <!-- start content -->

                                <div class="tabbable">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tab1" data-toggle="tab">Send OTP</a></li>
                                        <li><a href="#tab2" data-toggle="tab">Verify OTP</a></li>
                                    </ul>

                                    <div id="apitabctr" class="tab-content p-v-lg">


                                        <div class="tab-pane active fade in" id="tab1"><br /><br />
                                            <div class="clearfix">
                                                <div class="col-md-6">
                                                    <div class="formSep"><span class="label label-info">API Endpoint (POST)</span> <span class="label label-success">Send OTP API</span>
                                                        <h4 class="m-t-sm page-header"><?php echo $data['baseurl'] ?>api/v2/otp</h4>

                                                    </div>
                                                </div>
                                                <div style="text-align:right" class="col-md-6 bg-warning text-dark"><?php echo SCTEXT('Your API Key is') ?>: &nbsp;<strong style="font-family: monospace;"><?php echo $data['apikey'] ?> </strong>&nbsp;<button class="btn-primary btn m-l-lg regeneratekey"><?php echo SCTEXT('Regenerate Key') ?></button>
                                                </div>

                                            </div>
                                            <!-----PARAMETERS --->
                                            <div class="m-t-xs clearfix">
                                                <div class="col-md-6 rescroll">
                                                    <h4>Request Header</h4>
                                                    <hr>
                                                    <div class="card">
                                                        <div class="card-header p-sm">
                                                            Supply API Key or Token in the Header
                                                        </div>
                                                        <div class="card-body">
                                                            <pre>
                                                                    <code class="language-http">
Authorization: Bearer &lt;YOUR_API_KEY&gt;
Content-Type: application/json
                                                                    </code>
                                                                </pre>
                                                        </div>
                                                    </div>

                                                    <h4>Request Body</h4>
                                                    <hr>
                                                    <table class=" table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Parameter</th>
                                                                <th>Type</th>
                                                                <th>Required</th>
                                                                <th>Description</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><kbd class="bg-primary"><b>mobile</b></kbd></td>
                                                                <td>String</td>
                                                                <td>Yes</td>
                                                                <td>The mobile number which needs verification. We will sent the OTP on this mobile number.
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td><kbd class="bg-primary"><b>channel</b></kbd></td>
                                                                <td>Integer</td>
                                                                <td>Yes</td>
                                                                <td>Supply the OTP channel ID here. Channels are created to configure the route, sender and message template of the OTP</td>
                                                            </tr>

                                                        </tbody>
                                                    </table>


                                                    <!----- RESPONSES -------->
                                                    <h4 style="margin-top:15px;" class="page-header"><?php echo SCTEXT('Responses') ?></h4>
                                                    <table class=" table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th><?php echo SCTEXT('API Response') ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="card">
                                                                        <div class="card-header p-sm">
                                                                            Error Response
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <pre>
                                                                                    <code class="language-json">
{
    "success": false,
    "status": 403,
    "message": "API Key Missing"
}
                                                                                    </code>
                                                                                </pre>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td><?php echo SCTEXT('An error occurred while submitting the API call request. The source of error would be explained in the braces {}, for instance, "ERR: INVALID API KEY" means the API key entered is expired or does not belong to any user. The error messages are not ciphered and pretty much intuitive.') ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="card">
                                                                        <div class="card-header p-sm">
                                                                            Success Response
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <pre>
                                                                                    <code class="language-json">
{
    "status": "success",
    "reference": "036BZDUHIX"
}
                                                                                    </code>
                                                                                </pre>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?php echo SCTEXT('This confirms that API call was successdul and the reference is returned. You need to supply this reference during verification') ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>



                                                <!----- URL & SAMPLE CODE ---->

                                                <div class="p-l-xs col-md-6 rescroll">
                                                    <h4>Integration examples with Sample Code</h4>
                                                    <hr>
                                                    <ul class="nav nav-tabs" id="codeTabs" role="tablist">
                                                        <li class="nav-item active">
                                                            <a class="nav-link " id="curl-otp-tab" data-toggle="tab" href="#curl-otp" role="tab" aria-controls="curl" aria-selected="true">cURL</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="nodejs-otp-tab" data-toggle="tab" href="#nodejs-otp" role="tab" aria-controls="nodejs" aria-selected="false">Node.js</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="php-otp-tab" data-toggle="tab" href="#php-otp" role="tab" aria-controls="php" aria-selected="false">PHP</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="go-otp-tab" data-toggle="tab" href="#go-otp" role="tab" aria-controls="go" aria-selected="false">Go</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="csharp-otp-tab" data-toggle="tab" href="#csharp-otp" role="tab" aria-controls="csharp" aria-selected="false">C#</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="java-otp-tab" data-toggle="tab" href="#java-otp" role="tab" aria-controls="java" aria-selected="false">Java</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="python-otp-tab" data-toggle="tab" href="#python-otp" role="tab" aria-controls="python" aria-selected="false">Python</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content" id="codeTabsContent">
                                                        <div class="tab-pane fade active in" id="curl-otp" role="tabpanel" aria-labelledby="curl-tab">
                                                            <h5 class="card-title" style="display: none;">cURL Example</h5>
                                                            <div class="code-box">
                                                                <pre><code class="language-sh">curl -X POST <?php echo $data['baseurl'] ?>api/v2/otp \
     -H "Authorization: Bearer <YOUR_API_KEY>" \
     -H "Content-Type: application/json" \
     -d '{"channel": 27, "mobile": "919887xxxxxx"}'</code></pre>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="nodejs-otp" role="tabpanel" aria-labelledby="nodejs-tab">
                                                            <h5 class="card-title" style="display: none;">NodeJS Example</h5>
                                                            <div class="code-box">
                                                                <pre><code class="language-javascript">const https = require('https');

const data = JSON.stringify({
  channel: 27,
  mobile: "919887xxxxxx"
});

const options = {
  hostname: '<?php echo $_SERVER['HTTP_HOST'] ?>',
  port: 443,
  path: '/api/v2/otp',
  method: 'POST',
  headers: {
    'Authorization': 'Bearer <YOUR_API_KEY>',
    'Content-Type': 'application/json',
    'Content-Length': data.length
  }
};

const req = https.request(options, res => {
  let body = '';

  res.on('data', chunk => {
    body += chunk;
  });

  res.on('end', () => {
    console.log('Response:', body);
  });
});

req.on('error', error => {
  console.error(error);
});

req.write(data);
req.end();
</code></pre>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="php-otp" role="tabpanel" aria-labelledby="php-tab">
                                                            <h5 class="card-title" style="display: none;">PHP Example</h5>
                                                            <div class="code-box">
                                                                <pre><code class="language-php">&lt;?php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "<?php echo $data['baseurl'] ?>api/v2/otp");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer <YOUR_API_KEY>",
    "Content-Type: application/json"
));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "channel" => 27,
    "mobile" => "919887xxxxxx"
]));

$response = curl_exec($ch);
curl_close($ch);

echo $response;

?></code></pre>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="go-otp" role="tabpanel" aria-labelledby="go-tab">
                                                            <h5 class="card-title" style="display: none;">Go Example</h5>
                                                            <div class="code-box">
                                                                <pre><code class="language-go">package main

import (
	"bytes"
	"fmt"
	"net/http"
)

func main() {
	url := "<?php echo $data['baseurl'] ?>api/v2/otp"
	method := "POST"

	payload := []byte(`{
		"channel": 27,
		"mobile": "919887xxxxxx"
	}`)

	req, err := http.NewRequest(method, url, bytes.NewBuffer(payload))
	if err != nil {
		fmt.Println(err)
		return
	}
	req.Header.Add("Authorization", "Bearer <YOUR_API_KEY>")
	req.Header.Add("Content-Type", "application/json")

	client := &http.Client{}
	res, err := client.Do(req)
	if err != nil {
		fmt.Println(err)
		return
	}
	defer res.Body.Close()

	fmt.Println("Response Status:", res.Status)
}
</code></pre>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="csharp-otp" role="tabpanel" aria-labelledby="csharp-tab">
                                                            <h5 class="card-title" style="display: none;">C# Example</h5>
                                                            <div class="code-box">
                                                                <pre><code class="language-csharp">using System;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;

class Program
{
    static async Task Main(string[] args)
    {
        using (HttpClient client = new HttpClient())
        {
            var requestData = new
            {
                channel = 27,
                mobile = "919887xxxxxx"
            };

            var content = new StringContent(
                Newtonsoft.Json.JsonConvert.SerializeObject(requestData),
                Encoding.UTF8, 
                "application/json");

            client.DefaultRequestHeaders.Add("Authorization", "Bearer <YOUR_API_KEY>");

            HttpResponseMessage response = await client.PostAsync("<?php echo $data['baseurl'] ?>api/v2/otp", content);

            string result = await response.Content.ReadAsStringAsync();
            Console.WriteLine(result);
        }
    }
}
</code></pre>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="java-otp" role="tabpanel" aria-labelledby="java-tab">
                                                            <h5 class="card-title" style="display: none;">Java Example</h5>
                                                            <div class="code-box">
                                                                <pre><code class="language-java">import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;

public class Main {
    public static void main(String[] args) {
        try {
            URL url = new URL("<?php echo $data['baseurl'] ?>api/v2/otp");
            HttpURLConnection con = (HttpURLConnection) url.openConnection();
            con.setRequestMethod("POST");
            con.setRequestProperty("Authorization", "Bearer <YOUR_API_KEY>");
            con.setRequestProperty("Content-Type", "application/json");
            con.setDoOutput(true);

            String jsonInputString = "{\"channel\": 27, \"mobile\": \"919887xxxxxx\"}";
            try (OutputStream os = con.getOutputStream()) {
                byte[] input = jsonInputString.getBytes(StandardCharsets.UTF_8);
                os.write(input, 0, input.length);
            }

            int code = con.getResponseCode();
            System.out.println("Response Code: " + code);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
</code></pre>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="python-otp" role="tabpanel" aria-labelledby="python-tab">
                                                            <h5 class="card-title" style="display: none;">Python Example</h5>
                                                            <div class="code-box">
                                                                <pre><code class="language-python">import http.client
import json

conn = http.client.HTTPSConnection("<?php echo $_SERVER['HTTP_HOST'] ?>")

payload = json.dumps({
    "channel": 27,
    "mobile": "919887xxxxxx"
})

headers = {
    'Authorization': 'Bearer <YOUR_API_KEY>',
    'Content-Type': 'application/json'
}

conn.request("POST", "/api/v2/otp", payload, headers)

res = conn.getresponse()
data = res.read()

print(data.decode("utf-8"))
</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="tab2"><br /><br />

                                            <div class="clearfix">
                                                <div class="col-md-6">
                                                    <div class="formSep"><span class="label label-info">API Endpoint (GET)</span> <span class="label label-success">Verify OTP API</span>
                                                        <h4 class="m-t-sm page-header"><?php echo $data['baseurl'] ?>api/v2/otp/verify</h4>

                                                    </div>
                                                </div>




                                                <div style="text-align:right" class="col-md-6 bg-warning text-dark"><?php echo SCTEXT('Your API Key is') ?>: <strong><?php echo $data['apikey'] ?> </strong>&nbsp;<button class="btn-primary btn"><?php echo SCTEXT('Regenerate Key') ?></button>
                                                </div>



                                            </div>

                                            <!-----PARAMETERS --->
                                            <div class="m-t-xs clearfix">
                                                <div class="col-md-6 rescroll">
                                                    <h4>Request Header</h4>
                                                    <hr>
                                                    <div class="card">
                                                        <div class="card-header p-sm">
                                                            Supply API Key or Token in the Header
                                                        </div>
                                                        <div class="card-body">
                                                            <pre>
                                                                    <code class="language-http">
Authorization: Bearer &lt;YOUR_API_KEY&gt;
Content-Type: application/json
                                                                    </code>
                                                                </pre>
                                                        </div>
                                                    </div>

                                                    <h4>Request Body</h4>
                                                    <hr>
                                                    <table class=" table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Parameter</th>
                                                                <th>Type</th>
                                                                <th>Required</th>
                                                                <th>Description</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><kbd class="bg-primary"><b>reference</b></kbd></td>
                                                                <td>String</td>
                                                                <td>Yes</td>
                                                                <td>The reference returned during the send OTP API call.
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td><kbd class="bg-primary"><b>otp</b></kbd></td>
                                                                <td>Integer</td>
                                                                <td>Yes</td>
                                                                <td>The one-time password that needs to be verified.</td>
                                                            </tr>

                                                        </tbody>
                                                    </table>


                                                    <!----- RESPONSES -------->
                                                    <h4 style="margin-top:15px;" class="page-header"><?php echo SCTEXT('Responses') ?></h4>
                                                    <table class=" table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th><?php echo SCTEXT('API Response') ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="card">
                                                                        <div class="card-header p-sm">
                                                                            Error Response
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <pre>
                                                                                    <code class="language-json">
{
    "success": false,
    "status": 403,
    "message": "API Key Missing"
}
                                                                                    </code>
                                                                                </pre>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td><?php echo SCTEXT('An error occurred while submitting the API call request. The source of error would be explained in the braces {}, for instance, "ERR: INVALID API KEY" means the API key entered is expired or does not belong to any user. The error messages are not ciphered and pretty much intuitive.') ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="card">
                                                                        <div class="card-header p-sm">
                                                                            Success Response
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <pre>
                                                                                    <code class="language-json">
{
    "status": "success",
    "matched": "yes"
}
                                                                                    </code>
                                                                                </pre>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><?php echo SCTEXT('This confirms that API call was successful and the OTP matched. The responses will have friendly messages explaining the status.') ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!----- URL & SAMPLE CODE ---->

                                                <div class="p-l-xs col-md-6 rescroll">
                                                    <h4>Integration examples with Sample Code</h4>
                                                    <hr>
                                                    <ul class="nav nav-tabs" id="codeTabs" role="tablist">
                                                        <li class="nav-item active">
                                                            <a class="nav-link " id="curl-votp-tab" data-toggle="tab" href="#curl-votp" role="tab" aria-controls="curl" aria-selected="true">cURL</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="nodejs-votp-tab" data-toggle="tab" href="#nodejs-votp" role="tab" aria-controls="nodejs" aria-selected="false">Node.js</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="php-votp-tab" data-toggle="tab" href="#php-votp" role="tab" aria-controls="php" aria-selected="false">PHP</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="go-votp-tab" data-toggle="tab" href="#go-votp" role="tab" aria-controls="go" aria-selected="false">Go</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="csharp-votp-tab" data-toggle="tab" href="#csharp-votp" role="tab" aria-controls="csharp" aria-selected="false">C#</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="java-votp-tab" data-toggle="tab" href="#java-votp" role="tab" aria-controls="java" aria-selected="false">Java</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="python-votp-tab" data-toggle="tab" href="#python-votp" role="tab" aria-controls="python" aria-selected="false">Python</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content" id="codeTabsContent">
                                                        <div class="tab-pane fade active in" id="curl-votp" role="tabpanel" aria-labelledby="curl-tab">
                                                            <h5 class="card-title" style="display: none;">cURL Example</h5>
                                                            <div class="code-box">
                                                                <pre><code class="language-sh">curl -G <?php echo $data['baseurl'] ?>api/v2/otp/verify \
     -H "Authorization: Bearer <YOUR_API_KEY>" \
     -H "Content-Type: application/json" \
     --data-urlencode "reference=036BZDUHIX" \
     --data-urlencode "otp=321456"
</code></pre>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="nodejs-votp" role="tabpanel" aria-labelledby="nodejs-tab">
                                                            <h5 class="card-title" style="display: none;">NodeJS Example</h5>
                                                            <div class="code-box">
                                                                <pre><code class="language-javascript">const https = require('https');
const querystring = require('querystring');

const data = querystring.stringify({
  reference: '036BZDUHIX',
  otp: 321456
});

const options = {
  hostname: '<?php echo $_SERVER['HTTP_HOST'] ?>',
  port: 443,
  path: `/api/v2/otp/verify?${data}`,
  method: 'GET',
  headers: {
    'Authorization': 'Bearer <YOUR_API_KEY>',
    'Content-Type': 'application/json'
  }
};

const req = https.request(options, res => {
  let body = '';

  res.on('data', chunk => {
    body += chunk;
  });

  res.on('end', () => {
    console.log('Response:', body);
  });
});

req.on('error', error => {
  console.error(error);
});

req.end();
</code></pre>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="php-votp" role="tabpanel" aria-labelledby="php-tab">
                                                            <h5 class="card-title" style="display: none;">PHP Example</h5>
                                                            <div class="code-box">
                                                                <pre><code class="language-php">&lt;?php
$ch = curl_init();

$queryData = http_build_query([
    'reference' => '036BZDUHIX',
    'otp' => 321456
]);

curl_setopt($ch, CURLOPT_URL, "<?php echo $data['baseurl'] ?>api/v2/otp/verify?" . $queryData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer <YOUR_API_KEY>",
    "Content-Type: application/json"
));

$response = curl_exec($ch);
curl_close($ch);

echo $response;

?></code></pre>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="go-votp" role="tabpanel" aria-labelledby="go-tab">
                                                            <h5 class="card-title" style="display: none;">Go Example</h5>
                                                            <div class="code-box">
                                                                <pre><code class="language-go">package main

import (
	"fmt"
	"net/http"
	"net/url"
)

func main() {
	baseUrl := "<?php echo $data['baseurl'] ?>api/v2/otp/verify"
	params := url.Values{}
	params.Add("reference", "036BZDUHIX")
	params.Add("otp", "321456")

	reqUrl := fmt.Sprintf("%s?%s", baseUrl, params.Encode())

	req, err := http.NewRequest("GET", reqUrl, nil)
	if err != nil {
		fmt.Println(err)
		return
	}
	req.Header.Add("Authorization", "Bearer <YOUR_API_KEY>")
	req.Header.Add("Content-Type", "application/json")

	client := &http.Client{}
	res, err := client.Do(req)
	if err != nil {
		fmt.Println(err)
		return
	}
	defer res.Body.Close()

	fmt.Println("Response Status:", res.Status)
}
</code></pre>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="csharp-votp" role="tabpanel" aria-labelledby="csharp-tab">
                                                            <h5 class="card-title" style="display: none;">C# Example</h5>
                                                            <div class="code-box">
                                                                <pre><code class="language-csharp">using System;
using System.Net.Http;
using System.Threading.Tasks;

class Program
{
    static async Task Main(string[] args)
    {
        using (HttpClient client = new HttpClient())
        {
            var uri = "<?php echo $data['baseurl'] ?>api/v2/otp/verify?reference=036BZDUHIX&otp=321456";
            client.DefaultRequestHeaders.Add("Authorization", "Bearer <YOUR_API_KEY>");
            client.DefaultRequestHeaders.Add("Content-Type", "application/json");

            HttpResponseMessage response = await client.GetAsync(uri);

            string result = await response.Content.ReadAsStringAsync();
            Console.WriteLine(result);
        }
    }
}
</code></pre>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="java-votp" role="tabpanel" aria-labelledby="java-tab">
                                                            <h5 class="card-title" style="display: none;">Java Example</h5>
                                                            <div class="code-box">
                                                                <pre><code class="language-java">import java.net.HttpURLConnection;
import java.net.URL;

public class Main {
    public static void main(String[] args) {
        try {
            String params = "reference=036BZDUHIX&otp=321456";
            URL url = new URL("<?php echo $data['baseurl'] ?>api/v2/otp/verify?" + params);
            HttpURLConnection con = (HttpURLConnection) url.openConnection();
            con.setRequestMethod("GET");
            con.setRequestProperty("Authorization", "Bearer <YOUR_API_KEY>");
            con.setRequestProperty("Content-Type", "application/json");

            int code = con.getResponseCode();
            System.out.println("Response Code: " + code);
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
</code></pre>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="python-votp" role="tabpanel" aria-labelledby="python-tab">
                                                            <h5 class="card-title" style="display: none;">Python Example</h5>
                                                            <div class="code-box">
                                                                <pre><code class="language-python">import http.client
import urllib.parse

params = urllib.parse.urlencode({
    'reference': '036BZDUHIX',
    'otp': 321456
})

conn = http.client.HTTPSConnection("<?php echo $_SERVER['HTTP_HOST'] ?>")

headers = {
    'Authorization': 'Bearer <YOUR_API_KEY>',
    'Content-Type': 'application/json'
}

conn.request("GET", f"/api/v2/otp/verify?{params}", headers=headers)

res = conn.getresponse()
data = res.read()

print(data.decode("utf-8"))
</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>




                                            </div>

                                        </div><!-- tab2 pane end -->






                                    </div>
                                </div>

                                <!-- end content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>