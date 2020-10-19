<h1>zoox-challege</h1>

<h2>How do use and test this app</h2>

<h3>Requirements</h3>

- PHP 7.4<br />
- XAMPP v3.2.4<br />
- MongoDB 4.4.1<br />
- Compass 1.22.1<br />
- Composer dependencies<br />
- JSON Encodding for Requests<br />
- Application Register in the database<br />

> NOTE: This project and app was tested in Windows environment

> NOTE: Use URL-Encode or JSON-Encode to send a request for api zoox-api: urlencode (PHP)

> NOTE: This project is part of the zoox project and must be used together with the <h1>zoox-test</h1>
> https://github.com/jereelton-devel/zoox-test/tree/master 

<h3>Apache Server Configuration - XAMPP</h3>

<p>C:\webserver\xampp-php7.4.8\apache\conf\extra\httpd-vhosts.conf</p>

- API
<pre>
<VirtualHost *:80>
    ServerAdmin webmaster@email.com.br
    DocumentRoot "C:/webserver/xampp-php7.4.8/htdocs/zoox-api"
    ServerName zoox.api.local
    ErrorLog "logs/zoox.api.local.local-error.log"
    CustomLog "logs/zoox.api.local.local-access.log" common
    <Directory "C:/webserver/xampp-php7.4.8/htdocs/zoox-api">
        Require all granted

        RewriteEngine On

        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ index.php [QSA,L]
		
		Header set Access-Control-Allow-Origin '*'
		
    </Directory>
</VirtualHost>
</pre>

- APP with permission
<pre>
<VirtualHost *:80>
    ServerAdmin webmaster@email.com.br
    DocumentRoot "C:/webserver/xampp-php7.4.8/htdocs/zoox-test"
    ServerName zoox.test.local
    ErrorLog "logs/zoox.test.local.local-error.log"
    CustomLog "logs/zoox.test.local.local-access.log" common
    <Directory "C:/webserver/xampp-php7.4.8/htdocs/zoox-test">
        Require all granted

        RewriteEngine On

        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ index.php [QSA,L]
		
		Header set Access-Control-Allow-Origin '*'
		
    </Directory>
</VirtualHost>
</pre>

- APP without permission
<pre>
<VirtualHost *:80>
    ServerAdmin webmaster@email.com.br
    DocumentRoot "C:/webserver/xampp-php7.4.8/htdocs/zoox-test"
    ServerName zoox.denied.local
    ErrorLog "logs/zoox.denied.local.local-error.log"
    CustomLog "logs/zoox.denied.local.local-access.log" common
    <Directory "C:/webserver/xampp-php7.4.8/htdocs/zoox-test">
        Require all granted

        RewriteEngine On

        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^ index.php [QSA,L]
		
		Header set Access-Control-Allow-Origin '*'
		
    </Directory>
</VirtualHost>
</pre>

<h3>Hosts File Configuration</h3>

<p>C:\Windows\System32\drivers\etc\hosts</p>

<pre>
# Copyright (c) 1993-2009 Microsoft Corp.
#
# This is a sample HOSTS file used by Microsoft TCP/IP for Windows.
#
# This file contains the mappings of IP addresses to host names. Each
# entry should be kept on an individual line. The IP address should
# be placed in the first column followed by the corresponding host name.
# The IP address and the host name should be separated by at least one
# space.
#
# Additionally, comments (such as these) may be inserted on individual
# lines or following the machine name denoted by a '#' symbol.
#
# For example:
#
#      102.54.94.97     rhino.acme.com          # source server
#       38.25.63.10     x.acme.com              # x client host

# localhost name resolution is handled within DNS itself.
#	127.0.0.1       localhost
#	::1             localhost
	127.0.0.1       localhost
	127.0.0.1       zoox.api.local
	127.0.0.1       zoox.test.local
	127.0.0.1       zoox.denied.local
</pre>

<h3>Restart your webserver</h3>

- XAMPP::Controll (by example)

<h3>Import the MongoDB databases</h3>

<p>These files are placed in zoox-api project in the db/ folder</p>

- zoox_mongodb_collection_apikey.json
- zoox_mongodb_collection_auth.json
- zoox_mongodb_collection_cidade.json
- zoox_mongodb_collection_estado.json
- zoox_mongodb_collection_log.json

<h3>IMPORTANT:</h3>

> Put the files files zoox-test on your localhost root path, so access the app by http://zoox.test.local/.

> The API should be defined with the correct local dns name for the correct funcionally this test, by example: http://zoox.api.local

> You can too put the files in the localhost (http://localhost/zoox-test) webserver, but will be needed register the application into database correctly.

<h3>Requests</h3>

> NOTE !<br />
(:col) = collection and (:data) = data

- <h4>Data List: List all documents</h4>
> <strong>GET</strong><br />
> zoox.api.local/action/list/{JSON-DATA}
> <br /><strong>Sample:</strong><br />
> http://zoox.api.local/action/list/[{"collection":"cidade"}]

<strong>PHP Sample URL Request:</strong>
<pre>
$data_json = json_encode([
             "collection" => $target
         ], JSON_UNESCAPED_UNICODE);
 
         $this->url = "http://zoox.api.local/action/list/{$data_json}";
</pre>

<strong>Response:</strong>
<pre>
[
   {
      "id":1,
      "nome":"Amazonas",
      "sigla":"AM",
      "data_criacao":"10\/10\/2020 13:29:34",
      "data_atualizacao":"16\/10\/2020 11:42:23"
   },
   {
      "id":2,
      "nome":"São Paulo",
      "sigla":"SP",
      "data_criacao":"10\/10\/2020 13:29:34",
      "data_atualizacao":"16\/10\/2020 11:15:23"
   }
]
</pre>

- <h4>Data Specific List: List one document</h4>
> <strong>GET</strong><br />
> zoox.api.local/action/listone/{JSON-DATA}
> <br /><strong>Sample:</strong><br />
> http://zoox.api.local/action/listone/[{"collection":"cidade","id":1}]

<strong>PHP Sample URL Request:</strong>
<pre>
$data_json = json_encode([
            "collection" => $target,
            "id" => $id
        ], JSON_UNESCAPED_UNICODE);

        $this->url = "http://zoox.api.local/action/listone/{$data_json}";
</pre>

<strong>Response:</strong>
<pre>
[
   {
      "id":1,
      "nome":"Amazonas",
      "sigla":"AM",
      "data_criacao":"10\/10\/2020 13:29:34",
      "data_atualizacao":"16\/10\/2020 11:42:23"
   }
]
</pre>

- <h4>Data Ordered List: List all documents ordered</h4>
> <strong>GET</strong><br />
> zoox.api.local/action/listorder/{JSON-DATA}
> <br /><strong>Sample:</strong><br />
> http://zoox.api.local/action/listorder/[{"collection":"cidade","order":"nome","type":"asc"}]

<strong>PHP Sample URL Request:</strong>
<pre>
$data_json = json_encode([
            "collection" => $target,
            "order" => $order,
            "type" => $type
        ], JSON_UNESCAPED_UNICODE);

        $this->url = "http://zoox.api.local/action/listorder/{$data_json}";
</pre>

<strong>Response:</strong>
<pre>
[
   {
      "id":8,
      "nome":"Amapa",
      "sigla":"AP",
      "data_criacao":"16\/10\/2020 13:29:34",
      "data_atualizacao":""
   },
   {
      "id":1,
      "nome":"Amazonas",
      "sigla":"AM",
      "data_criacao":"10\/10\/2020 13:29:34",
      "data_atualizacao":"16\/10\/2020 11:42:23"
   }
]
</pre>

- <h4>Data Search: Search one or more documents</h4>
> <strong>GET</strong><br />
> zoox.api.local/action/search/{JSON-DATA}
> <br /><strong>Sample:</strong><br />
> http://zoox.api.local/action/search/[{"collection":"cidade","data":"Santos"}]

<strong>PHP Sample URL Request:</strong>
<pre>
$data_json = json_encode([
            "collection" => $target,
            "data" => $data
        ], JSON_UNESCAPED_UNICODE);

        $this->url = "http://zoox.api.local/action/search/{$data_json}";
</pre>

<strong>Response:</strong>
<pre>
[
   {
      "id":8,
      "nome":"Amapa",
      "sigla":"AP",
      "data_criacao":"16\/10\/2020 13:29:34",
      "data_atualizacao":""
   }
]
</pre>

- <h4>Data Insert: Insert any document</h4>
> <strong>POST</strong><br />
> zoox.api.local/action/insert/{JSON-DATA}
> <br /><strong>Sample:</strong><br />
> http://zoox.api.local/action/insert/cidade/[{"collection":"cidade","name":"Santos","sigla":"SP"}]

<strong>PHP Sample URL Request:</strong>
<pre>
$data_json = json_encode([
            "collection" => $target,
            "name" => $name,
            "sigla" => $sigla
        ], JSON_UNESCAPED_UNICODE);

        $this->url = "http://zoox.api.local/action/insert/{$data_json}";
</pre>

<strong>Response:</strong>
<pre>
{
    "msgSuccess":"Documento inserido com sucesso"
}

{
    "msgError":"Não foi possivel inserir o documento"
}
</pre>

- Data Update: Update a document in database
> <strong>POST</strong><br />
> zoox.api.local/action/update/{JSON-DATA}
> <br /><strong>Sample:</strong><br />
> http://zoox.api.local/action/update/[{"collection":"cidade","id":1,"name":"Santos",sigla":"SP"}]

<strong>PHP Sample URL Request:</strong>
<pre>
$data_json = json_encode([
            "collection" => $target,
            "id" => $id,
            "name" => $data1,
            "sigla" => $data2
        ], JSON_UNESCAPED_UNICODE);

        $this->url = "http://zoox.api.local/action/update/{$data_json}";
</pre>

<strong>Response:</strong>
<pre>
{
    "msgSuccess":"Documento atualizado com sucesso"
}

{
    "msgError":"Não foi possivel atualizar o documento"
}
</pre>

- Data Delete: Delete one document in the database
> <strong>POST</strong><br />
> zoox.api.local/action/delete/{JSON-DATA}
> <br /><strong>Sample:</strong><br />
> http://zoox.api.local/action/delete/[{"collection":"cidade","id":1}]

<strong>PHP Sample URL Request:</strong>
<pre>
$data_json = json_encode([
            "collection" => $target,
            "id" => $id
        ], JSON_UNESCAPED_UNICODE);

        $this->url = "http://zoox.api.local/action/delete/{$data_json}";
</pre>

<strong>Response:</strong>
<pre>
{
    "msgSuccess":"Documento apagado com sucesso"
}

{
    "msgError":"Não foi possivel apagar o documento"
}
</pre>

<h3>Authentication</h3>

The app authentication is make in the collection database zoox_mongodb_collection_auth, so you should be register your app in the collection database, example:

* {id: 1, app: zoox.test.local}
* {id: 2, app: zoox.api.local}

<h3>Authorization</h3>

After app authentication the api request your token to check your identity correctly in the collection zoox_mongodb_collection_apikey, so you need register or create a token of access this api, example:

* {id: 1, app: "zoox.test.local", token: "MTIzNDU2Nzg5MA==", timelife: 1}
* {id: 2, app: "zoox.test.local", token: "MTIzNDU2Nzg5MA==", timelife: 1}

In both cases you should be send the token in the request header with name <strong>x-Api-key</strong> and client name <strong>x-Client-Origin</strong>.

> PHP Sample
<pre>
$this->headers = [
            "x-Client-Origin: zoox.test.local",
            "x-Api-key: {$this->token}",
        ];
</pre>

> Jquery Ajax Sample
<pre>
    $.ajax({
        contentType: 'application/json; charset=UTF-8'
        headers: {
            'x-Client-Origin':'zoox.test.local',
            'x-Api-key':'MTIzNDU2Nzg5MA=='
        }
        type: "GET|POST...",
        url: "http://zoox.api.local/[ACTION]",
        data: "action=[ACTION]",
        dataType: "json",
        async: false,
        success: function(resp) { /*any thing*/ },
        error: function(resp) { /*any thing*/ }
    });
</pre>


