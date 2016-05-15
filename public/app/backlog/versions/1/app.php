<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Backlog</title>
    <link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico">
    <link rel="stylesheet" href="/styles/main.css">
    <link rel="import" href="/elements/elements.html">
    <link rel="import" href="elements/elements_backlog.html">
    <style is="custom-style" include="shared-styles"></style>
</head>
<body>
    <paper-material elevation="1">
        <akop-projects id="akop-projects"></akop-projects>
    </paper-material>
    <paper-material elevation="1">
        <akop-projects id="portals"></akop-projects>
    </paper-material>

    <paper-tabs id="groups" selected="0" autoselect autoselect-delay="1000" is="dom-repeat" items="{{items}}">
        <paper-tab>{{item.NAME}}</paper-tab>
        <script>
            Polymer({
                is: 'paper-tabs',
                setItems: function(items) {
                    console.log('setItems', items);
                    this.items = items;
                },
            });
        </script>
    </paper-tabs>

    <iron-ajax id="ajax-portals"
        auto="true"
        url="https://akop.pw/api/v1/portals"
        handle-as="json"
        on-response="afterLoad"
        debounce-duration="300">
    </iron-ajax>
    <script>
        window.addEventListener('WebComponentsReady', function() {
            var ironAjax = document.querySelector('#ajax-portals');
            ironAjax.addEventListener('response', function() {
                console.log("ironAjax", this, ironAjax.lastResponse);
                var portals = document.querySelector('#portals');
                portals.setItems(ironAjax.lastResponse);
            });
            // ironAjax.generateRequest();
        });
    </script>
<script src="/bower_components/webcomponentsjs/webcomponents-lite.js"></script>
<script src="<?=$_GLOBALS['version_path']?>js/api.js?<?=microtime()?>"></script>
<script src="https://api.bitrix24.com/api/v1/"></script>
<script src="<?=$_GLOBALS['version_path']?>js/app.js?<?=microtime()?>"></script>
</body>
</html>