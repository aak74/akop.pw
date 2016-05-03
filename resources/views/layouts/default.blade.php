<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laravel + Polymer</title>
<link rel="import" href="../bower_components/polymer/polymer.html">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="../bower_components/webcomponentsjs/webcomponents.min.js"></script>
</head>
<body unresolved>
    @yield('content')
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

                <!-- Drawer Content -->
                <paper-menu class="app-menu" attr-for-selected="data-route" selected="[[route]]">
                    <a data-route="home" href="@{{baseUrl}}">
                        <iron-icon icon="home"></iron-icon>
                        <span>Home</span>
                    </a>

                    <a data-route="users" href="@{{baseUrl}}users">
                        <iron-icon icon="info"></iron-icon>
                        <span>Users</span>
                    </a>

                    <a data-route="contact" href="@{{baseUrl}}contact">
                        <iron-icon icon="mail"></iron-icon>
                        <span>Contact</span>
                    </a>
                </paper-menu>
            </paper-scroll-header-panel>

            <!-- Main Area -->
            <paper-scroll-header-panel main id="headerPanelMain" condenses keep-condensed-header>
                <!-- Main Toolbar -->
                <paper-toolbar id="mainToolbar" class="tall">
                    <paper-icon-button id="paperToggle" icon="menu" paper-drawer-toggle></paper-icon-button>

                    <span class="space"></span>

                    <!-- Toolbar icons -->
                    <paper-icon-button icon="refresh"></paper-icon-button>
                    <paper-icon-button icon="search"></paper-icon-button>

                    <!-- Application name -->
                    <div class="middle middle-container">
                        <div class="app-name">Polymer Starter Kit</div>
                    </div>

                    <!-- Application sub title -->
                    <div class="bottom bottom-container">
                        <div class="bottom-title">The future of the web today</div>
                    </div>
                </paper-toolbar>

                <!-- Main Content -->
                <div class="content">
                    <iron-pages attr-for-selected="data-route" selected="@{{route}}">
                        <section data-route="home" tabindex="-1">
                            <paper-material elevation="1">
                                <my-greeting></my-greeting>

                                <p class="subhead">You now have:</p>
                                <my-list></my-list>

                                <p>Looking for more Web App layouts? Check out our <a href="https://github.com/PolymerElements/app-layout-templates">layouts</a> collection. You can also <a href="http://polymerelements.github.io/app-layout-templates/">preview</a> them live.</p>
                            </paper-material>

                            <paper-material elevation="1">
                                <iron-ajax
                                    auto
                                    url="//akop.pw/"
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

    </template>

    <div class="container">
        <h1 class="title">Lareavel + Polymer</h1>
    </div>
</body>
</html>
