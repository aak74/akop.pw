<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Сервисы и приложения для соцсетей</title>

    <link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico">

    <link rel="stylesheet" href="styles/main.css">
    <script src="bower_components/webcomponentsjs/webcomponents-lite.js"></script>
    <link rel="import" href="elements/elements.html">
    <style is="custom-style" include="shared-styles"></style>
</head>

<body unresolved>
    <!-- build:remove -->
    <span id="browser-sync-binding"></span>
    <!-- endbuild -->

    <template is="dom-bind" id="app">

        <paper-drawer-panel id="paperDrawerPanel">
            <!-- Drawer Scroll Header Panel -->
            <paper-scroll-header-panel drawer fixed>

                <!-- Drawer Toolbar -->
                <paper-toolbar id="drawerToolbar">
                    <span class="menu-name">Menu</span>
                </paper-toolbar>

                <dom-module id="simple-menu">

                    <template>
                        <paper-menu class="app-menu" attr-for-selected="data-route" selected="[[route]]">
                            <template is="dom-repeat" id="menu" items="@{{menuItems}}">
                                <a data-route="[[item.route]]" href="@{{baseUrl}}[[item.url]]">
                                    <iron-icon icon="[[item.icon]]"></iron-icon>
                                    <span>[[item.name]]</span>
                                </a>
                            </template>
                        </paper-menu>
                    </template>

                    <script>
                        Polymer({
                            is: 'simple-menu',
                            ready: function() {
                                this.menuItems = [
                                    { name: "Home", route: "home", url: "", icon: "home" },
                                    { name: "Приложения", route: "apps", url: "apps", icon: "apps" },
                                    { name: "Пользователи", route: "users", url: "users", icon: "info" },
                                    { name: "Контакты", route: "contact", url: "contact", icon: "mail" },
                                ];
                            },
                        });
                    </script>

                </dom-module>
                <simple-menu></simple-menu>
            </paper-scroll-header-panel>

            <paper-scroll-header-panel main id="headerPanelMain" condenses keep-condensed-header>
                <paper-toolbar id="mainToolbar" class="tall">
                    <paper-icon-button id="paperToggle" icon="menu" paper-drawer-toggle></paper-icon-button>

                    <span class="space"></span>
                    <paper-icon-button icon="refresh"></paper-icon-button>

                    <div class="middle middle-container">
                        <div class="app-name">akop.pw</div>
                    </div>

                    <div class="bottom bottom-container">
                        <div class="bottom-title">Сервисы и приложения для соцсетей</div>
                    </div>
                </paper-toolbar>

                <div class="content">
                    <iron-pages attr-for-selected="data-route" selected="@{{route}}">
                        <section data-route="home" tabindex="-1">
                            <paper-material elevation="1">
                                <h1 class="page-title" tabindex="-1">Welcome</h1>
                                <h2><?=getenv('APP_ENV');?></h2>

                                <p class="subhead">You now have:</p>
                                <my-list></my-list>

                                <p>Looking for more Web App layouts? Check out our <a href="https://github.com/PolymerElements/app-layout-templates">layouts</a> collection. You can also <a href="http://polymerelements.github.io/app-layout-templates/">preview</a> them live.</p>
                            </paper-material>

                            <paper-material elevation="1">
                                <iron-ajax
                                    auto
                                    url="http://akop.pw/"
                                    params='{}'
                                    handle-as="text"
                                    on-response="handleResponse"
                                    debounce-duration="300"></iron-ajax>
                            </paper-material>

                        </section>

                        <section data-route="users" tabindex="-1">
                            <paper-material elevation="1">
                                <h1 class="page-title" tabindex="-1">Users</h1>
                                <p>This is the users section</p>
                                <a href$="@{{baseUrl}}users/Addy">Addy</a><br>
                                <a href$="@{{baseUrl}}users/Rob">Rob</a><br>
                                <a href$="@{{baseUrl}}users/Chuck">Chuck</a><br>
                                <a href$="@{{baseUrl}}users/Sam">Sam</a>
                            </paper-material>
                        </section>

                        <section data-route="user-info" tabindex="-1">
                            <paper-material elevation="-1">
                                <h1 class="page-title" tabindex="-1">User: @{{params.name}}</h1>
                                <div>This is @{{params.name}}'s section</div>
                            </paper-material>
                        </section>

                        <section data-route="apps" tabindex="-1">
                            <paper-material elevation="1">
                                <h1 class="page-title" tabindex="-1">Приложения</h1>
                                <p>This is the section of apps</p>
                            </paper-material>
                        </section>

                        <section data-route="contact" tabindex="-1">
                            <paper-material elevation="1">
                                <h1 class="page-title" tabindex="-1">Contact</h1>
                                <p>This is the contact section</p>
                            </paper-material>
                        </section>
                    </iron-pages>
                </div>
            </paper-scroll-header-panel>
        </paper-drawer-panel>

        <paper-toast id="toast">
            <span class="toast-hide-button" role="button" tabindex="0" onclick="app.$.toast.hide()">Ok</span>
        </paper-toast>

    </template>

    <!-- build:js scripts/app.js -->
    <script src="scripts/app.js"></script>
    <!-- endbuild-->

</body>

</html>

