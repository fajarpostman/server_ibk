{{-- <li class="header">MAIN NAVIGATION</li> --}}
<li>
    <a href="{{ route('index') }}">
		<i class="fa fa-home"></i> <span>@lang('backend.home')</span>
	</a>
</li>
<li class="treeview">
	<a href="#">
		<i class="fa fa-desktop"></i><span> @lang('backend.signage')</span>
		<span class="pull-right-container">
			<i class="fa fa-angle-left pull-right"></i>
		</span>
	</a>
	<ul class="treeview-menu">
		<li class="{{ \Route::currentRouteName() ? 'active' : '' }}"><a href="{{ route('signage.running-text.index') }}"><i class="fa fa-text-width"></i> @lang('backend.running_text')</a></li>
		<li class="{{ \Route::currentRouteName() ? 'active' : '' }}"><a href="{{ route('signage.video.index') }}"><i class="fa fa-video-camera"></i> @lang('backend.video')</a></li>
		<li class="{{ \Route::currentRouteName() ? 'active' : '' }}"><a href="{{ route('signage.banner.index') }}"><i class="fa fa-picture-o"></i> @lang('backend.banner')</a></li>
		<li class="{{ \Route::currentRouteName() ? 'active' : '' }}"><a href="{{ route('signage.exchange-rate.index') }}"><i class="fa fa-money"></i> @lang('backend.exchange_rate')</a></li>
		<li class="{{ \Route::currentRouteName() ? 'active' : '' }}"><a href="{{ route('signage.deposito.index') }}"><i class="fa fa-bar-chart"></i> @lang('backend.deposito')</a></li>
	</ul>
</li>
<li class="treeview">
	<a href="#">
		<i class="fa fa-reorder"></i><span> @lang('backend.master')</span>
		<span class="pull-right-container">
			<i class="fa fa-angle-left pull-right"></i>
		</span>
	</a>
	<ul class="treeview-menu">
		<li class="{{ \Route::currentRouteName() ? 'active' : '' }}"><a href="{{ route('master.location.index') }}"><i class="fa fa-map"></i> @lang('backend.location')</a></li>
		<li class="{{ \Route::currentRouteName() ? 'active' : '' }}"><a href="{{ route('master.device.index') }}"><i class="fa fa-ticket"></i> @lang('backend.device')</a></li>
	</ul>
</li>
<li class="treeview">
	<a href="#">
		<i class="fa fa-gear"></i> <span>@lang('backend.setting')</span>
		<span class="pull-right-container">
			<i class="fa fa-angle-left pull-right"></i>
		</span>
	</a>
	<ul class="treeview-menu">
		{{-- <li><a href="invoice.html"><i class="fa fa-circle-o"></i> Invoice</a></li> --}}
		<li><a href="{{ route('setting.account') }}"><i class="fa fa-user"></i> Account Setting</a></li>
		{{-- <li><a href="{{ route('setting.language') }}"><i class="fa fa-circle-o"></i> Language</a></li> --}}
	</ul>
</li>
