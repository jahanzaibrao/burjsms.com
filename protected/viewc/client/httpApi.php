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
                            <h3 class="page-title-sc clearfix">HTTP API<small><?php echo SCTEXT('developer API with sample codes') ?></small><button type="button" class="btn btn-sm btn-primary pull-right" id="printButton" onclick="Prism.highlightAll();window.print();"><i class="fa fa-print"></i></button></h3>
                            <hr>
                            <?php if ($data['permission'] == 0) { ?>
                                <?php include('notification.php') ?>
                            <?php } else { ?>
                                <?php include('notification.php') ?>
                                <div class="col-md-12">
                                    <!-- start content -->

                                    <div class="tabbable">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab1" data-toggle="tab">SMS API</a></li>
                                            <li><a href="#tab7" data-toggle="tab"><?php echo SCTEXT('Delivery Reports') ?> API</a></li>
                                            <li><a href="#tab6" data-toggle="tab"><?php echo SCTEXT('Credit Balance') ?> API</a></li>
                                        </ul>

                                        <div id="apitabctr" class="tab-content p-v-lg">


                                            <div class="tab-pane active fade in" id="tab1"><br /><br />

                                                <div class="clearfix">
                                                    <div class="col-md-6">
                                                        <div class="formSep"><span class="label label-info">API Endpoint (POST)</span> <span class="label label-success">TEXT SMS API</span>
                                                            <h4 class="m-t-sm page-header"><?php echo $data['baseurl'] ?>api/v2/sms</h4>

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
                                                                    <td><kbd class="bg-primary"><b>campaignId</b></kbd></td>
                                                                    <td>Integer</td>
                                                                    <td>No</td>
                                                                    <td>Campaign ID. Uses default campaign ID if not supplied.
                                                                        <div style="max-width: 200px;">
                                                                            <?php foreach ($data['camps'] as $cmp) { ?>
                                                                                <div class="clearfix">
                                                                                    <div class="p-l-0 col-md-6 col-sm-6 col-xs-6">
                                                                                        <span class="label label-info"><?php echo $cmp->campaign_name ?></span>
                                                                                    </div>

                                                                                    <div class="p-r-0 text-right col-md-6 col-sm-6 col-xs-6">
                                                                                        <h5 class="m-t-xs text-dark"><?php echo 'ID = ' . $cmp->id ?></h5>
                                                                                    </div>
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php if ($_SESSION['user']['account_type'] != 1) { ?>
                                                                    <?php if (count($_SESSION['credits']['routes']) > 1) { ?>
                                                                        <tr>
                                                                            <td><kbd class="bg-primary"><b>routeId</b></kbd></td>
                                                                            <td>Integer</td>
                                                                            <td>No</td>
                                                                            <td>Route ID. Uses default route ID if not supplied.
                                                                                <div style="max-width: 200px;">
                                                                                    <?php foreach ($_SESSION['credits']['routes'] as $rt) { ?>
                                                                                        <div class="clearfix">
                                                                                            <div class="p-l-0 col-md-6 col-sm-6 col-xs-6">
                                                                                                <span class="label label-info"><?php echo $rt['name'] ?></span>
                                                                                            </div>

                                                                                            <div class="p-r-0 text-right col-md-6 col-sm-6 col-xs-6">
                                                                                                <h5 class="m-t-xs text-dark"><?php echo 'ID = ' . $rt['id'] ?></h5>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php } ?>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                <?php }
                                                                } ?>
                                                                <tr>
                                                                    <td><kbd class="bg-primary"><b>sender</b></kbd></td>
                                                                    <td>String</td>
                                                                    <td>No</td>
                                                                    <td>Sender ID. Mandatory for approval-based routes.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><kbd class="bg-primary"><b>mode</b></kbd></td>
                                                                    <td>String</td>
                                                                    <td>No</td>
                                                                    <td>Message mode. Supported values are <code>text</code>, <code>flash</code>, <code>unicode</code>. Defaults to <code>text</code>. Auto-converted to Unicode if detected.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><kbd class="bg-primary"><b>message</b></kbd></td>
                                                                    <td>String</td>
                                                                    <td>No</td>
                                                                    <td>Message content. Optional if <code>templateId</code> is supplied.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><kbd class="bg-primary"><b>templateId</b></kbd></td>
                                                                    <td>Integer</td>
                                                                    <td>No</td>
                                                                    <td>Template ID. Optional if <code>message</code> is supplied.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><kbd class="bg-primary"><b>contacts</b></kbd></td>
                                                                    <td>Array</td>
                                                                    <td>Yes*</td>
                                                                    <td>Array of contact objects. Required unless <code>contactGroup</code> is supplied.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><kbd class="bg-primary"><b>contactGroup</b></kbd></td>
                                                                    <td>Integer</td>
                                                                    <td>No</td>
                                                                    <td>Contact group ID. Skips the <code>contacts</code> array and uses this ID instead.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><kbd class="bg-primary"><b>schedule</b></kbd></td>
                                                                    <td>String</td>
                                                                    <td>No</td>
                                                                    <td>Schedule time in the format <code>YYYY-MM-DD HH:MM:SS</code>. In system timezone.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><kbd class="bg-primary"><b>notifyUrl</b></kbd></td>
                                                                    <td>String</td>
                                                                    <td>No</td>
                                                                    <td>URL to which delivery receipts (DLR) will be posted.</td>
                                                                </tr>
                                                                <tr>
                                                                    <td><?php echo strtolower($data['tlvList']) ?></td>
                                                                    <td colspan="2"><?php echo SCTEXT('TLV parameters List.') ?> <br><span class="text-primary code">(optional)</span></td>
                                                                </tr>

                                                            </tbody>
                                                        </table>

                                                        <h4 class="m-t-sm page-header">Contacts Options</h4>
                                                        <div class="card">
                                                            <div class="card-header p-sm">
                                                                Regular Message
                                                            </div>
                                                            <div class="card-body">
                                                                <pre>
                                                                    <code class="language-json">
[
    { "mobile": "1234567890" },
    { "mobile": "1234567890" }
]
                                                                    </code>
                                                                </pre>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header p-sm">
                                                                Personalized with Parameters
                                                            </div>
                                                            <div class="card-body">
                                                                <pre>
                                                                    <code class="language-javascript">
let text = "Hello #name#, your OTP is #otp#";
let contacts = 
[
    { "mobile": "91234567890", "parameters": { "name": "sam", "otp": "123456" } },
    { "mobile": "81234567890", "parameters": { "name": "nick", "otp": "987654" } }
]
                                                                    </code>
                                                                </pre>
                                                            </div>
                                                        </div>
                                                        <div class="card">
                                                            <div class="card-header p-sm">
                                                                Personalized with Message
                                                            </div>
                                                            <div class="card-body">
                                                                <pre>
                                                                    <code class="language-javascript">
let text = "";
let contacts = 
[
    { "mobile": "91234567890", "message": "Hello John, your otp is 8998" },
    { "mobile": "81234567890", "message":  "Hello Niya, you are not eligible for this service" }
]
                                                                    </code>
                                                                </pre>
                                                            </div>
                                                        </div>

                                                        <hr>

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
    "message": "SMS Submitted successfully",
    "batch_id": "samsms_eink1rilz6ucphk",
    "total_contacts": 1,
    "message_id": [
        {
            "mobile": "919887111111",
            "sms_id": "1ef4d949-7639-6880-bf73-ae79481385ed"
        }
    ]
}
                                                                                    </code>
                                                                                </pre>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><?php echo SCTEXT('This means SMS was submitted successfully, and it returns the shoot-id of the submission. You could use this ID to pull out delivery reports. The ID starts after the slash (/) mark.') ?></td>
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
                                                                <a class="nav-link " id="curl-tab" data-toggle="tab" href="#curl" role="tab" aria-controls="curl" aria-selected="true">cURL</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="nodejs-tab" data-toggle="tab" href="#nodejs" role="tab" aria-controls="nodejs" aria-selected="false">Node.js</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="php-tab" data-toggle="tab" href="#php" role="tab" aria-controls="php" aria-selected="false">PHP</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="go-tab" data-toggle="tab" href="#go" role="tab" aria-controls="go" aria-selected="false">Go</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="csharp-tab" data-toggle="tab" href="#csharp" role="tab" aria-controls="csharp" aria-selected="false">C#</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="java-tab" data-toggle="tab" href="#java" role="tab" aria-controls="java" aria-selected="false">Java</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="python-tab" data-toggle="tab" href="#python" role="tab" aria-controls="python" aria-selected="false">Python</a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content" id="codeTabsContent">
                                                            <div class="tab-pane fade active in" id="curl" role="tabpanel" aria-labelledby="curl-tab">
                                                                <h5 class="card-title" style="display: none;">cURL Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-sh">curl -X POST <?php echo Doo::conf()->APP_URL ?>api/v2/sms \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "campaignId": 1,
    "routeId": 1,
    "sender": "HNODE",
    "mode": "text",
    "message": "Hello #name#, your otp is #otp#",
    "contacts": [
        { "mobile": "91234567890", "parameters": { "otp": "123456" } },
        { "mobile": "81234567890", "parameters": { "otp": "123456" } }
    ],
    "notifyUrl": "https://example.com",
    "dlt_entity_id": "1234567890",
    "dlt_template_id": "1234567890"
  }'</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="nodejs" role="tabpanel" aria-labelledby="nodejs-tab">
                                                                <h5 class="card-title" style="display: none;">NodeJS Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-javascript">const fetch = require('node-fetch');

const url = '<?php echo Doo::conf()->APP_URL ?>api/v2/sms';
const token = 'YOUR_API_TOKEN';
const data = {
    campaignId: 1,
    routeId: 1,
    sender: "HNODE",
    mode: "text",
    message: "Hello #name#, your otp is #otp#",
    contacts: [
        { mobile: "91234567890", parameters: { otp: "123456" } },
        { mobile: "81234567890", parameters: { otp: "123456" } }
    ],
    notifyUrl: "https://example.com",
    dlt_entity_id: "1234567890",
    dlt_template_id: "1234567890"
};

fetch(url, {
    method: 'POST',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
})
.then(response => response.json())
.then(response => console.log(response))
.catch(error => console.error('Error:', error));</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="php" role="tabpanel" aria-labelledby="php-tab">
                                                                <h5 class="card-title" style="display: none;">PHP Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-php">&lt;?php
$curl = curl_init();

$data = [
    "campaignId" => 1,
    "routeId" => 1,
    "sender" => "HNODE",
    "mode" => "text",
    "message" => "Hello #name#, your otp is #otp#",
    "contacts" => [
        ["mobile" => "91234567890", "parameters" => ["otp" => "123456"]],
        ["mobile" => "81234567890", "parameters" => ["otp" => "123456"]]
    ],
    "notifyUrl" => "https://example.com",
    "dlt_entity_id" => "1234567890",
    "dlt_template_id" => "1234567890"
];

curl_setopt_array($curl, [
    CURLOPT_URL => "<?php echo Doo::conf()->APP_URL ?>api/v2/sms",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer YOUR_API_TOKEN",
        "Content-Type: application/json"
    ],
]);

$response = curl_exec($curl);
curl_close($curl);

echo $response;
?></code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="go" role="tabpanel" aria-labelledby="go-tab">
                                                                <h5 class="card-title" style="display: none;">Go Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-go">package main

import (
    "bytes"
    "encoding/json"
    "fmt"
    "net/http"
)

type Contact struct {
    Mobile     string            `json:"mobile"`
    Parameters map[string]string `json:"parameters,omitempty"`
}

type SmsRequest struct {
    CampaignId     int       `json:"campaignId"`
    RouteId        int       `json:"routeId"`
    Sender         string    `json:"sender"`
    Mode           string    `json:"mode"`
    Message        string    `json:"message"`
    Contacts       []Contact `json:"contacts"`
    NotifyUrl      string    `json:"notifyUrl"`
    DltEntityId    string    `json:"dlt_entity_id"`
    DltTemplateId  string    `json:"dlt_template_id"`
}

func main() {
    url := "<?php echo Doo::conf()->APP_URL ?>api/v2/sms"
    token := "YOUR_API_TOKEN"

    data := SmsRequest{
        CampaignId: 1,
        RouteId:    1,
        Sender:     "HNODE",
        Mode:       "text",
        Message:    "Hello #name#, your otp is #otp#",
        Contacts: []Contact{
            {Mobile: "91234567890", Parameters: map[string]string{"otp": "123456"}},
            {Mobile: "81234567890", Parameters: map[string]string{"otp": "123456"}},
        },
        NotifyUrl:     "https://example.com",
        DltEntityId:   "1234567890",
        DltTemplateId: "1234567890",
    }

    jsonData, err := json.Marshal(data)
    if err != nil {
        fmt.Println(err)
        return
    }

    req, err := http.NewRequest("POST", url, bytes.NewBuffer(jsonData))
    if err != nil {
        fmt.Println(err)
        return
    }

    req.Header.Set("Authorization", "Bearer "+token)
    req.Header.Set("Content-Type", "application/json")

    client := &http.Client{}
    resp, err := client.Do(req)
    if err != nil {
        fmt.Println(err)
        return
    }
    defer resp.Body.Close()

    fmt.Println("Response status:", resp.Status)
}</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="csharp" role="tabpanel" aria-labelledby="csharp-tab">
                                                                <h5 class="card-title" style="display: none;">C# Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-csharp">using System;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;
using Newtonsoft.Json;

public class SmsRequest
{
    public int CampaignId { get; set; }
    public int RouteId { get; set; }
    public string Sender { get; set; }
    public string Mode { get; set; }
    public string Message { get; set; }
    public Contact[] Contacts { get; set; }
    public string NotifyUrl { get; set; }
    public string DltEntityId { get; set; }
    public string DltTemplateId { get; set; }
}

public class Contact
{
    public string Mobile { get; set; }
    public Parameters Parameters { get; set; }
}

public class Parameters
{
    public string Otp { get; set; }
}

class Program
{
    private static readonly HttpClient client = new HttpClient();

    static async Task Main(string[] args)
    {
        var url = "<?php echo Doo::conf()->APP_URL ?>api/v2/sms";
        var token = "YOUR_API_TOKEN";

        var data = new SmsRequest
        {
            CampaignId = 1,
            RouteId = 1,
            Sender = "HNODE",
            Mode = "text",
            Message = "Hello #name#, your otp is #otp#",
            Contacts = new[]
            {
                new Contact { Mobile = "91234567890", Parameters = new Parameters { Otp = "123456" } },
                new Contact { Mobile = "81234567890", Parameters = new Parameters { Otp = "123456" } }
            },
            NotifyUrl = "https://example.com",
            DltEntityId = "1234567890",
            DltTemplateId = "1234567890"
        };

        var jsonData = JsonConvert.SerializeObject(data);
        var content = new StringContent(jsonData, Encoding.UTF8, "application/json");

        client.DefaultRequestHeaders.Add("Authorization", "Bearer " + token);

        var response = await client.PostAsync(url, content);
        var responseString = await response.Content.ReadAsStringAsync();

        Console.WriteLine(responseString);
    }
}</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="java" role="tabpanel" aria-labelledby="java-tab">
                                                                <h5 class="card-title" style="display: none;">Java Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-java">import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;

public class SendSms {

    public static void main(String[] args) {
        try {
            URL url = new URL("<?php echo Doo::conf()->APP_URL ?>api/v2/sms");
            HttpURLConnection conn = (HttpURLConnection) url.openConnection();
            conn.setDoOutput(true);
            conn.setRequestMethod("POST");
            conn.setRequestProperty("Content-Type", "application/json");
            conn.setRequestProperty("Authorization", "Bearer YOUR_API_TOKEN");

            String input = "{"
                    + "\"campaignId\":1,"
                    + "\"routeId\":1,"
                    + "\"sender\":\"HNODE\","
                    + "\"mode\":\"text\","
                    + "\"message\":\"Hello #name#, your otp is #otp#\","
                    + "\"contacts\":["
                    + "{\"mobile\":\"91234567890\",\"parameters\":{\"otp\":\"123456\"}},"
                    + "{\"mobile\":\"81234567890\",\"parameters\":{\"otp\":\"123456\"}}"
                    + "],"
                    + "\"notifyUrl\":\"https://example.com\","
                    + "\"dlt_entity_id\":\"1234567890\","
                    + "\"dlt_template_id\":\"1234567890\""
                    + "}";

            OutputStream os = conn.getOutputStream();
            os.write(input.getBytes());
            os.flush();

            if (conn.getResponseCode() != HttpURLConnection.HTTP_OK) {
                throw new RuntimeException("Failed : HTTP error code : "
                        + conn.getResponseCode());
            }

            BufferedReader br = new BufferedReader(new InputStreamReader(
                    (conn.getInputStream())));

            String output;
            System.out.println("Output from Server .... \n");
            while ((output = br.readLine()) != null) {
                System.out.println(output);
            }

            conn.disconnect();

        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="python" role="tabpanel" aria-labelledby="python-tab">
                                                                <h5 class="card-title" style="display: none;">Python Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-python">import requests
import json

url = "<?php echo Doo::conf()->APP_URL ?>api/v2/sms"
token = "YOUR_API_TOKEN"

data = {
    "campaignId": 1,
    "routeId": 1,
    "sender": "HNODE",
    "mode": "text",
    "message": "Hello #name#, your otp is #otp#",
    "contacts": [
        {"mobile": "91234567890", "parameters": {"otp": "123456"}},
        {"mobile": "81234567890", "parameters": {"otp": "123456"}}
    ],
    "notifyUrl": "https://example.com",
    "dlt_entity_id": "1234567890",
    "dlt_template_id": "1234567890"
}

headers = {
    "Authorization": f"Bearer {token}",
    "Content-Type": "application/json"
}

response = requests.post(url, data=json.dumps(data), headers=headers)

print(response.json())</code></pre>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>




                                                </div>
                                            </div>



                                            <div class="tab-pane fade" id="tab6"><br /><br />
                                                <div class="clearfix">
                                                    <div class="col-md-6">
                                                        <div class="formSep"><span class="label label-info">API Endpoint (GET)</span> <span class="label label-success">CREDIT API</span>
                                                            <h4 class="m-t-sm page-header"><?php echo $data['baseurl'] ?>api/v2/credits</h4>

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
                                                                    <td colspan="4" class="text-center">No Parameters Required</td>
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
    "billing_mode": "<?php echo $_SESSION['user']['account_type'] == 0 ? "credits" : "currency"; ?>",
    "wallet": {
        "amount": "0.00",
        "currency": "<?php echo Doo::conf()->currency_name ?>"
    },
    <?php if ($_SESSION['user']['account_type'] == 0) { ?>
"credits": [
        {
            "route": "&lt;name of the route>",
            "credits": 3946684
        }
    ]
    <?php } ?>

}
                                                                                    </code>
                                                                                </pre>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><?php echo SCTEXT('This is the success API response, depending on your billing type you will see the available credits in the response.') ?></td>
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
                                                                <a class="nav-link " id="curl-crd-tab" data-toggle="tab" href="#curl-crd" role="tab" aria-controls="curl" aria-selected="true">cURL</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="nodejs-crd-tab" data-toggle="tab" href="#nodejs-crd" role="tab" aria-controls="nodejs" aria-selected="false">Node.js</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="php-crd-tab" data-toggle="tab" href="#php-crd" role="tab" aria-controls="php" aria-selected="false">PHP</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="go-crd-tab" data-toggle="tab" href="#go-crd" role="tab" aria-controls="go" aria-selected="false">Go</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="csharp-crd-tab" data-toggle="tab" href="#csharp-crd" role="tab" aria-controls="csharp" aria-selected="false">C#</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="java-crd-tab" data-toggle="tab" href="#java-crd" role="tab" aria-controls="java" aria-selected="false">Java</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="python-crd-tab" data-toggle="tab" href="#python-crd" role="tab" aria-controls="python" aria-selected="false">Python</a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content" id="codeTabsContent">
                                                            <div class="tab-pane fade active in" id="curl-crd" role="tabpanel" aria-labelledby="curl-tab">
                                                                <h5 class="card-title" style="display: none;">cURL Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-sh">curl -X GET <?php echo Doo::conf()->APP_URL ?>api/v2/credits \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json"
</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="nodejs-crd" role="tabpanel" aria-labelledby="nodejs-tab">
                                                                <h5 class="card-title" style="display: none;">NodeJS Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-javascript">const fetch = require('node-fetch');

const url = '<?php echo Doo::conf()->APP_URL ?>api/v2/credits';
const token = 'YOUR_API_TOKEN';

fetch(url, {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
    },
})
.then(response => response.json())
.then(response => console.log(response))
.catch(error => console.error('Error:', error));</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="php-crd" role="tabpanel" aria-labelledby="php-tab">
                                                                <h5 class="card-title" style="display: none;">PHP Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-php">&lt;?php
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "<?php echo Doo::conf()->APP_URL ?>api/v2/credits",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer YOUR_API_KEY",
        "Content-Type: application/json"
    )
));

$response = curl_exec($curl);
curl_close($curl);

echo $response;
?>
</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="go-crd" role="tabpanel" aria-labelledby="go-tab">
                                                                <h5 class="card-title" style="display: none;">Go Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-go">package main

import (
    "fmt"
    "net/http"
    "io/ioutil"
)

func main() {
    url := "<?php echo Doo::conf()->APP_URL ?>api/v2/credits"

    req, _ := http.NewRequest("GET", url, nil)
    req.Header.Add("Authorization", "Bearer YOUR_API_KEY")
    req.Header.Add("Content-Type", "application/json")

    client := &http.Client{}
    resp, err := client.Do(req)
    if err != nil {
        fmt.Println(err)
        return
    }
    defer resp.Body.Close()

    body, _ := ioutil.ReadAll(resp.Body)
    fmt.Println(string(body))
}
</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="csharp-crd" role="tabpanel" aria-labelledby="csharp-tab">
                                                                <h5 class="card-title" style="display: none;">C# Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-csharp">using System;
using System.Net.Http;
using System.Threading.Tasks;

class Program
{
    private static readonly HttpClient client = new HttpClient();

    static async Task Main()
    {
        var url = "<?php echo Doo::conf()->APP_URL ?>api/v2/credits";

        client.DefaultRequestHeaders.Add("Authorization", "Bearer YOUR_API_KEY");
        client.DefaultRequestHeaders.Add("Content-Type", "application/json");

        var response = await client.GetAsync(url);
        var responseBody = await response.Content.ReadAsStringAsync();
        Console.WriteLine(responseBody);
    }
}
</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="java-crd" role="tabpanel" aria-labelledby="java-tab">
                                                                <h5 class="card-title" style="display: none;">Java Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-java">import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;

public class Main {
    public static void main(String[] args) throws Exception {
        String url = "<?php echo Doo::conf()->APP_URL ?>api/v2/credits";

        HttpClient client = HttpClient.newHttpClient();
        HttpRequest request = HttpRequest.newBuilder()
            .uri(URI.create(url))
            .header("Authorization", "Bearer YOUR_API_KEY")
            .header("Content-Type", "application/json")
            .GET()
            .build();

        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());
        System.out.println(response.body());
    }
}
</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="python-crd" role="tabpanel" aria-labelledby="python-tab">
                                                                <h5 class="card-title" style="display: none;">Python Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-python">import requests

url = "<?php echo Doo::conf()->APP_URL ?>api/v2/credits"
headers = {
    "Authorization": "Bearer YOUR_API_KEY",
    "Content-Type": "application/json"
}

response = requests.get(url, headers=headers)
print(response.json())
</code></pre>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>




                                                </div>


                                            </div>






                                            <div class="tab-pane fade" id="tab7"><br /><br />

                                                <div class="clearfix">
                                                    <div class="col-md-6">
                                                        <div class="formSep"><span class="label label-info">API Endpoint (GET)</span> <span class="label label-success">DLR API</span>
                                                            <h4 class="m-t-sm page-header"><?php echo $data['baseurl'] ?>api/v2/dlr</h4>

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
                                                                    <td><kbd class="bg-primary"><b>batch_id</b></kbd></td>
                                                                    <td>String</td>
                                                                    <td>Yes, if no <kbd class="bg-white text-inverse"><b>sms_id</b></kbd> supplied</td>
                                                                    <td>If you need to retrieve DLR for a campaign submission with multiple mobile numbers, supply the batch_id returned during the campaign submission.
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td><kbd class="bg-primary"><b>sms_id</b></kbd></td>
                                                                    <td>String</td>
                                                                    <td>Yes, if no <kbd class="bg-white text-inverse"><b>batch_id</b></kbd> supplied</td>
                                                                    <td>If you need to fetch DLR details for a single message, supply the sms_id returned during the message submission.</td>
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
    "data": [
        {
            "sms_shoot_id": "user_6q0za64m0ql0vu7",
            "mobile": 918058xxxxxx,
            "sender_id": "WEBSMS",
            "sent": "2022-01-01 00:05:30",
            "dlr": {
                "status": "Handset Delivered",
                "operator_reply": "DELIVRD",
                "delivery_time": "2022-01-01 00:50:31"
            }
        }
    ]
}
                                                                                    </code>
                                                                                </pre>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td><?php echo SCTEXT('This confirms that API call was successful and the DLR details will be supplied in the data array as shown.') ?></td>
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
                                                                <a class="nav-link " id="curl-dlr-tab" data-toggle="tab" href="#curl-dlr" role="tab" aria-controls="curl" aria-selected="true">cURL</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="nodejs-dlr-tab" data-toggle="tab" href="#nodejs-dlr" role="tab" aria-controls="nodejs" aria-selected="false">Node.js</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="php-dlr-tab" data-toggle="tab" href="#php-dlr" role="tab" aria-controls="php" aria-selected="false">PHP</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="go-dlr-tab" data-toggle="tab" href="#go-dlr" role="tab" aria-controls="go" aria-selected="false">Go</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="csharp-dlr-tab" data-toggle="tab" href="#csharp-dlr" role="tab" aria-controls="csharp" aria-selected="false">C#</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="java-dlr-tab" data-toggle="tab" href="#java-dlr" role="tab" aria-controls="java" aria-selected="false">Java</a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" id="python-dlr-tab" data-toggle="tab" href="#python-dlr" role="tab" aria-controls="python" aria-selected="false">Python</a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content" id="codeTabsContent">
                                                            <div class="tab-pane fade active in" id="curl-dlr" role="tabpanel" aria-labelledby="curl-tab">
                                                                <h5 class="card-title" style="display: none;">cURL Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-sh">curl -X GET <?php echo Doo::conf()->APP_URL ?>api/v2/dlr \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "sms_id": "1234567890",
  }'</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="nodejs-dlr" role="tabpanel" aria-labelledby="nodejs-tab">
                                                                <h5 class="card-title" style="display: none;">Node.js Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-javascript">const fetch = require('node-fetch');

const url = '<?php echo Doo::conf()->APP_URL ?>api/v2/dlr';
const token = 'YOUR_API_TOKEN';
const data = {
    "sms_id": "1234567890",
};

fetch(url, {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
})
.then(response => response.json())
.then(response => console.log(response))
.catch(error => console.error('Error:', error));</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="php-dlr" role="tabpanel" aria-labelledby="php-tab">
                                                                <h5 class="card-title" style="display: none;">PHP Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-php">&lt;?php
$curl = curl_init();

$data = [
    "sms_id"=> "1234567890"
];

curl_setopt_array($curl, [
    CURLOPT_URL => "<?php echo Doo::conf()->APP_URL ?>api/v2/dlr",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer YOUR_API_TOKEN",
        "Content-Type: application/json"
    ],
]);

$response = curl_exec($curl);
curl_close($curl);

echo $response;
?></code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="go-dlr" role="tabpanel" aria-labelledby="go-tab">
                                                                <h5 class="card-title" style="display: none;">Go Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-go">package main

import (
    "bytes"
    "fmt"
    "net/http"
    "io/ioutil"
)

func main() {
    url := "<?php echo Doo::conf()->APP_URL ?>api/v2/dlr"
    var jsonData = []byte(`{"sms_id": "1234567890"}`)

    req, err := http.NewRequest("GET", url, bytes.NewBuffer(jsonData))
    if err != nil {
        fmt.Println(err)
        return
    }

    req.Header.Set("Authorization", "Bearer YOUR_API_TOKEN")
    req.Header.Set("Content-Type", "application/json")

    client := &http.Client{}
    resp, err := client.Do(req)
    if err != nil {
        fmt.Println(err)
        return
    }
    defer resp.Body.Close()

    body, _ := ioutil.ReadAll(resp.Body)
    fmt.Println(string(body))
}
</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="csharp-dlr" role="tabpanel" aria-labelledby="csharp-tab">
                                                                <h5 class="card-title" style="display: none;">C# Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-csharp">using System;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;

class Program
{
    private static readonly HttpClient client = new HttpClient();

    static async Task Main()
    {
        var url = "<?php echo Doo::conf()->APP_URL ?>api/v2/dlr";
        var data = new StringContent("{\"sms_id\": \"1234567890\"}", Encoding.UTF8, "application/json");

        client.DefaultRequestHeaders.Add("Authorization", "Bearer YOUR_API_TOKEN");

        var response = await client.GetAsync(url, HttpCompletionOption.ResponseHeadersRead);
        response.EnsureSuccessStatusCode();

        var body = await response.Content.ReadAsStringAsync();
        Console.WriteLine(body);
    }
}
</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="java-dlr" role="tabpanel" aria-labelledby="java-tab">
                                                                <h5 class="card-title" style="display: none;">Java Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-java">import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;

public class Main {
    public static void main(String[] args) throws Exception {
        String url = "<?php echo Doo::conf()->APP_URL ?>api/v2/dlr";
        String json = "{\"sms_id\": \"1234567890\"}";

        HttpClient client = HttpClient.newHttpClient();
        HttpRequest request = HttpRequest.newBuilder()
            .uri(URI.create(url))
            .header("Authorization", "Bearer YOUR_API_TOKEN")
            .header("Content-Type", "application/json")
            .GET(HttpRequest.BodyPublishers.ofString(json))
            .build();

        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());
        System.out.println(response.body());
    }
}
</code></pre>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="python-dlr" role="tabpanel" aria-labelledby="python-tab">
                                                                <h5 class="card-title" style="display: none;">Python Example</h5>
                                                                <div class="code-box">
                                                                    <pre><code class="language-python">import requests

url = "<?php echo Doo::conf()->APP_URL ?>api/v2/dlr"
headers = {
    "Authorization": "Bearer YOUR_API_TOKEN",
    "Content-Type": "application/json"
}
data = {
    "sms_id": "1234567890"
}

response = requests.get(url, headers=headers, json=data)
print(response.text)
</code></pre>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>




                                                </div>


                                            </div>



                                        </div>
                                    <?php } ?>
                                    </div>

                                    <!-- end content -->
                                </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>