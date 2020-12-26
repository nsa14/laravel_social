@extends('layouts.admin')

@section('content')
<h1 class="page-header">درج رکورد جدید سروش sapp </h1>
            <!-- /.row -->
            <div class="row color-dark">
                <div class="col-lg-4">
                    <div class="well">
                        <h4>* رکورد تکراری قابل ثبت نمی باشد.
                            <p>* این ماژول تستی می باشد و در حال پیاده سازی بوده ولی اطلاعات استخراج شده در آن معتبر می باشد
                            </p>
                        </h4>
                        <p>
                            <form role="form" action="{{ route('sapp-get-insert') }}" id="form" method="POST">
                                @csrf
                                <div class="form-group input-group">
                                    <span class="input-group-addon">@</span>
                                    <textarea name="userName" class="form-control" placeholder="Username" rows="6" cols="10">
                                    </textarea>
                                </div>
                                <p><button type="submit" id="btn-submit" class="btn btn-success">شروع فرایند</button>
                                <img id="img" src="{{ asset('images/30.gif') }}" alt="">
                                </p>

                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                            </form>
                        </p>
                    </div>
                </div>
                <!-- /.col-lg-4 -->
                <div class="col-lg-4">
                    <div class="well fa-font">
                        <p> پیغام ها </p>
                        @if(Session::has('message'))
                            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{!! Session::get('message') !!}</p>
                        @endif

                    </div>
                </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->
@endsection


@section('js')

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
        $(document).ready(function() {
            // $('#img').hide();
            // $('#img').add(this).toggleClass('hidden');
            $("#img").hide();
            $("#form").submit(function (e) {
                $('#img').show().slow();
            $("#btn-submit").attr("disabled", true);
            // $('#img').add(this).toggleClass('show');
            // $('#img').toggle('slow');
            

            return true;

            });
        });
    </script>
    
@endsection