# Exozet/Oauth2LoginBundle

### Installation

```console
    composer require silasjoisten/oauth2login-bundle
```

Register Bundle in **app/AppKernel.php**:
```php
 class AppKernel extends Kernel
 {
     public function registerBundles()
     {
        //...
        new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
        new Exozet\Oauth2LoginBundle\ExozetOauth2LoginBundle(),
//...
```

### Configuration

Include the Routing:
```yml
exozet_oauth:
    resource: "@ExozetOauth2LoginBundle/Resources/config/routing.xml"
```

Configure the HWIOauthBundle:

in security.yml:
```yml
security:
   providers:
      hwi:
         id: exozet.oauth2.user.provider

         #...

   firewalls:
      YOUR_FIREWALL:
         #pattern: /admin(.*) REMOVE THIS LINE IF YOU ARE USING SONATA ADMIN
         oauth:
            resource_owners:
               google:             "/login/check-google"
            login_path:        /admin/login                 # For Sonata Admin
            use_forward:       false
            default_target_path: /admin/dashboard           # For Sonata Admin
            failure_path:      /admin/login                 # For Sonata Admin
            oauth_user_provider:
               service:  exozet.oauth2.user.provider
```

in config.yml:
```yml
hwi_oauth:
    firewall_names: [YOUR_FIREWALL]
    resource_owners:
        google:
            type:                "google"
            client_id:           "YOUR_CLIENT_ID"
            client_secret:       "YOUR_CLIENT_SECRET"
            scope:               "email profile"
            options:
                csrf: true
                access_type:     offline
```

### Usage

To use The Exozet Login you just need to call the Twig function to render the button in your login template:

```twig
{{ render_exozet_login_button() }}
```

Optional: You can pass an *array* inside to to set custom *class* and *value*

This can look like this if you are using SonataAdminBundle:

```twig
{% extends base_template %}

{% block sonata_nav %}
{% endblock sonata_nav %}

{% block logo %}
{% endblock logo %}

{% block sonata_left_side %}
{% endblock sonata_left_side %}

{% block body_attributes %}class="sonata-bc login-page"{% endblock %}

{% block sonata_wrapper %}

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ path('sonata_admin_dashboard') }}">
                {% if 'single_image' == sonata_admin.adminPool.getOption('title_mode') or 'both' == sonata_admin.adminPool.getOption('title_mode') %}
                    <div>
                        <img style="width:64px;" src="{{ asset(sonata_admin.adminPool.titlelogo) }}" alt="{{ sonata_admin.adminPool.title }}">
                    </div>
                {% endif %}
                {% if 'single_text' == sonata_admin.adminPool.getOption('title_mode') or 'both' == sonata_admin.adminPool.getOption('title_mode') %}
                    <span>{{ sonata_admin.adminPool.title }}</span>
                {% endif %}
            </a>
        </div>
        <div class="login-box-body">
            {% block sonata_user_login_form %}
                {% block sonata_user_login_error %}
                    {% if error %}
                        <div class="alert alert-danger alert-error">
                            {{ error.messageKey|trans(error.messageData, 'security') }}
                        </div>
                    {% endif %}
                {% endblock %}
                <p class="login-box-msg">{{ 'title_user_authentication'|trans({}, 'SonataUserBundle') }}</p>
                <form action="{{ path("sonata_user_admin_security_check") }}" method="post" role="form">
                    <input type="hidden" name="_csrf_token" value="{{ csrf_token }}"/>

                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" id="username"  name="_username" value="{{ last_username }}" required="required" placeholder="{{ 'security.login.username'|trans({}, 'SonataUserBundle') }}"/>
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>

                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" id="password" name="_password" required="required" placeholder="{{ 'security.login.password'|trans({}, 'SonataUserBundle') }}"/>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>

                    <div class="row">
                        <div class="col-xs-8">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="remember_me" name="_remember_me" value="on"/>
                                    {{ 'security.login.remember_me'|trans({}, 'FOSUserBundle') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">{{ 'security.login.submit'|trans({}, 'FOSUserBundle') }}</button>
                        </div>
                    </div>
                </form>

                {#<a href="{{ path('sonata_user_admin_resetting_request') }}">{{ 'forgotten_password'|trans({}, 'SonataUserBundle') }}</a>#}
                <a href="{{ reset_route }}">{{ 'forgotten_password'|trans({}, 'SonataUserBundle') }}</a>
            {% endblock %}
            <hr>
            <div class="row">
                <div class="col-md-12">
                    {{ render_exozet_login_button() }}
                </div>
            </div>
        </div>
    </div>

{% endblock sonata_wrapper %}
```