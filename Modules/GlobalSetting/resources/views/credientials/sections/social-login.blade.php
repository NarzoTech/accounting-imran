<div class="tab-pane fade" id="social_login_tab" role="tabpanel">
    <form action="{{ route('admin.update-social-login') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <x-admin.form-input id="gmail_client_id" name="gmail_client_id" label="{{ __('Google Client ID') }}"
                value="{{ $setting->gmail_client_id }}" />
        </div>
        <div class="form-group">
            <x-admin.form-input id="gmail_secret_id" name="gmail_secret_id" label="{{ __('Google Secret ID') }}"
                value="{{ $setting->gmail_secret_id }}" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="google_login_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$setting->google_login_status == 'active'" />
        </div>
        <x-admin.update-button :text="__('Update')" />

    </form>

    <div class="form-group mt-3  mb-0">
        <label>{{ __('Google Redirect Url') }} <span data-toggle="tooltip" data-placement="top"
                class="fa fa-info-circle text--primary"
                title="{{ __('Copy the Gmail login URL and paste it wherever you need to use it.') }}"></span></label>
        <div class="input-group">
            <input type="text"
                value="{{ route('auth.social.callback', \App\Enums\SocialiteDriverType::GOOGLE->value) }}"
                id="gmail_redirect_url" class="form-control" readonly>
            <x-admin.button id="copyButton" text="{{ __('Click to copy') }}" />
        </div>
    </div>
</div>
