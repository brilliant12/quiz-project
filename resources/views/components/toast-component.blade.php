@if($message = session()->get('success'))
<script type="text/javascript">
      success("{{ $message}}");
</script>
@endif

@if($message = session()->get('error'))
<script type="text/javascript">
      error("{{ $message }}");
</script>
@endif

@if($message = session()->get('warning'))
<script type="text/javascript">
      warning("{{ $message }}");
</script>
@endif
2021-08-26-08-2021
19.05.2017
@if($message = session()->get('info'))
<script type="text/javascript">
      info("{{ $message }}");
</script>
@endif

@if($message = $errors->first())
<script  type="text/javascript">
      error("{{ $message }}");
</script>
@endif