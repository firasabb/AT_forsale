<div class="js-cookie-consent cookie-consent" style="text-align: center; padding: 20px 0; background-color: #2b2b2b; color: #fff">
    <div class="row no-gutters">
        <div class="col">
            <span class="cookie-consent__message">
                {!! trans('cookieConsent::texts.message') !!}
            </span>
        </div>
    </div>
    <div class="row mt-2 no-gutters">
        <div class="col">
            <button class="js-cookie-consent-agree cookie-consent__agree btn btn-sm btn-light mx-4">
                {{ trans('cookieConsent::texts.agree') }}
            </button>
        </div>
    </div>
</div>
