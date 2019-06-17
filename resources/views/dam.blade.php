<link href="{{ asset('vendor/xdam/styles.css') }}" rel="stylesheet">

<app-root></app-root>
<script>
    window.$xdam = @json($settings);
</script>

<script type="text/javascript" src="{{ asset('vendor/xdam/runtime.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/xdam/polyfills.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/xdam/main.js') }}"></script>