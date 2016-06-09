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
    <link rel="import" href="<?=$_GLOBALS['version_path']?>elements/elements_backlog.html">
</head>
<body>
    <paper-material elevation="1">
        <akop-projects id="akop-projects"></akop-projects>
    </paper-material>
    <paper-material elevation="1">
        <akop-projects id="portals"></akop-projects>
    </paper-material>
<!--
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
 -->

    <div id="notes">
        <paper-card heading="Call Jennifer" class="cyan">
            <div class="card-actions">
                <paper-icon-button icon="communication:call" style="color:white;"></paper-icon-button>
                <span>March 19, 2017</span>
            </div>
        </paper-card>
        <paper-card class="dark">
            <div class="card-content">
                <p>Groceries:</p>
                <paper-checkbox>almond milk</paper-checkbox>
                <paper-checkbox>coconut water</paper-checkbox>
                <paper-checkbox>cheese</paper-checkbox>
                <paper-checkbox>green apples</paper-checkbox>
            </div>
            <div class="card-actions">
                <paper-icon-button icon="communication:location-on" style="color:white"></paper-icon-button>
                <span>Campbell</span>
            </div>
        </paper-card>
        <paper-card heading="clean desk" class="lime"></paper-card>
        <paper-card image="./donuts.png" class="amber">
            <div class="card-content">New cafe opened on Valencia St.</div>
        </paper-card>
        <paper-card heading="Yuna tickets on sale 6/24" class="cyan">
        </paper-card>
    </div>
    <style>
      #notes {
        @apply(--layout-vertical);
        @apply(--layout-wrap);
        height: 344px;
        width: 384px;
      }

      #notes > paper-card {
        box-sizing: border-box;
        max-width: 184px;
        margin: 4px;
        flex: 0 0 auto;
      }
    </style>

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