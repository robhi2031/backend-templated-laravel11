<!--begin::Javascript-->
<script>
    var hostUrl = "{{ asset('/dist/') }}";
</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('dist/plugins/global/plugins.backend.bundle.js') }}"></script>
<script src="{{ asset('dist/js/scripts.backend.bundle.js') }}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
@if (isset($data['js']))
    @foreach ($data['js'] as $dt)
        @php $uri = asset($dt); @endphp
        @if(str_contains($dt, 'https://'))
            @php $uri = $dt; @endphp
        @endif
            <script src="{{ $uri }}"></script>
    @endforeach
@endif
<!--end::Vendors Javascript-->
<!--end::Javascript-->