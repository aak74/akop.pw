<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Приложения для соцсетей</title>

    <link rel="icon" type="image/vnd.microsoft.icon" href="/favicon.ico">

    <link rel="stylesheet" href="/styles/main.css">
    <script src="/bower_components/webcomponentsjs/webcomponents-lite.js"></script>
    <link rel="import" href="/elements/elements.html">
    <style is="custom-style" include="shared-styles"></style>
</head>

<body unresolved>
    <!-- build:remove -->
    <span id="browser-sync-binding"></span>
    <!-- endbuild -->

    <template is="dom-bind" id="app">

        <paper-drawer-panel id="paperDrawerPanel">
            <!-- Drawer Scroll Header Panel -->
            @include('pages.menu')

            <paper-scroll-header-panel main id="headerPanelMain" condenses keep-condensed-header>
                <paper-toolbar id="mainToolbar" class="tall">
                    <paper-icon-button id="paperToggle" icon="menu" paper-drawer-toggle></paper-icon-button>

                    <div class="middle middle-container">
                        <div class="app-name">Приложения для соцсетей</div>
                    </div>

                    <div class="bottom bottom-container">
                        <div class="bottom-title">Добавь новый функционал в свою любимую соцсеть</div>
                    </div>
                </paper-toolbar>

                <div class="content">
                    <paper-material elevation="1">
                        @yield('content')
                    </paper-material>
                </div>
            </paper-scroll-header-panel>
        </paper-drawer-panel>

        <paper-toast id="toast">
            <span class="toast-hide-button" role="button" tabindex="0" onclick="app.$.toast.hide()">Ok</span>
        </paper-toast>

    </template>

    <script src="/scripts/app.js"></script>
    @include('pages.counters')
</body>

</html>

