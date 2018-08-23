<link href="{{ asset('vendor/xdam/styles.css') }}" rel="stylesheet"></link>

<app-root>
    <script>
        window.$xdam = {
            token: "{{  $dam_token }}",
            base_url: "{{ $dam_url  }}",
            endpoints: {
                resources: {
                    get: 'resources',
                    post: 'resources',
                    delete: 'resources'
                }
            }
        }
    </script>
</app-root>

<script type="text/javascript" src="{{ asset('vendor/xdam/runtime.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/xdam/polyfills.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/xdam/main.js') }}"></script>
