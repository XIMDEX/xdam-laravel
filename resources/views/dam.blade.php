<link href="{{ asset('vendor/xdam/styles.css') }}" rel="stylesheet">

<app-root></app-root>
<script>
    window.$xdam = {
        token: "{{ $dam_token }}",
        base_url: "{{ $dam_url }}",
        endpoints: {!! json_encode($dam_endpoints) !!},
        models: {!! json_encode($dam_models) !!},
        forms: {!! json_encode($dam_form) !!}
    }
</script>

<script type="text/javascript" src="{{ asset('vendor/xdam/runtime.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/xdam/polyfills.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/xdam/main.js') }}"></script>
