<label for="myplugin_new_field">
	<p>
		<strong>Zijn de foto's op deze pagina bestelbaar?</strong>
	</p>
	{!! wp_nonce_field($nonce_field_name, $nonce_field_name) !!}

	@if(isset($description))
		<p>{{$description}}</p>
	@endif

	<label for="{{ prefix('enabled_true') }}">
		<input type="radio" id="{{ prefix('enabled_true') }}" name="{{ $oypo_enabled_field_name }}" {{ checked($enabled, true) }} value="1" />
		<span>{{ esc_attr( 'Ja', 'wp_admin_style' ) }}</span>
	</label>

	<br/>

	<label for="{{ prefix('enabled_false') }}">
		<input type="radio" id="{{ prefix('enabled_false') }}" name="{{ $oypo_enabled_field_name }}" {{ checked($enabled, false) }} value="0" />
		<span>{{ esc_attr( 'Nee', 'wp_admin_style' ) }}</span>
	</label>
</label>
