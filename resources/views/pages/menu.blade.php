<?
$menu = array(
    array("name" => "Главная", "url" => "", "icon" => "home"),
    array("name" => "Приложения", "url" => "apps", "icon" => "apps"),
    // array("name" => "Пользователи", "url" => "users", "icon" => "info"),
    // array("name" => "Контакты", "url" => "contact", "icon" => "mail"),
);

?>

<paper-scroll-header-panel drawer fixed>
	<paper-toolbar id="drawerToolbar">
	    <span class="menu-name">akop.pw</span>
	</paper-toolbar>
	<paper-menu class="app-menu">
		@foreach ($menu as $item)
	        <a class="{{ ($menuActive == $item['url']) ? 'iron-selected' : '' }}" href="/{{ $item['url'] }}">
	            <iron-icon icon="{{ $item['icon'] }}"></iron-icon>
	            <span>{{ $item['name'] }}</span>
	        </a>
		@endforeach
	</paper-menu>
</paper-scroll-header-panel>
