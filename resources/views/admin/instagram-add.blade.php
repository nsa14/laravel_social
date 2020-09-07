@extends('layouts.admin')

@section('content')
<h1 class="page-header">درج رکورد جدید اینستاگرام </h1>
            <!-- /.row -->
            <div class="row color-dark">
                <div class="col-lg-4">
                    <div class="well">
                        <h4>* رکورد تکراری قابل ثبت نمی باشد و بعد از ثبت اگر اکانت معتبر باشد اطلاعات آن در 
                            پنل سمت چپ نمایش داده خواهد شد.
                        </h4>
                        <p>
                            <form role="form" action="{{ route('instagram-insert') }}" method="POST">
                                @csrf
                                <div class="form-group input-group">
                                    <span class="input-group-addon">@</span>
                                    <input type="text" name="userName" class="form-control" placeholder="Username">
                                </div>
                                <p><button type="submit" class="btn btn-success">شروع فرایند</button></p>

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
                        <h4>مشخصات اکانت ثبتی</h4>
                         
                            @if(isset($arrayInstagramResult))

                            {{-- {{dd($arrayInstagramResult)}} --}}
                            
                                @forelse ($arrayInstagramResult as $arrayInstagramResultItem)
                                    <li><span  class="badge badge-primary"> نام کاربری: </span> {{ $arrayInstagramResultItem['_userName'] }}</li>
                                    <li class="text-left"><img src="{{ $arrayInstagramResultItem['_profile_pic_url'] }}" class="rounded"></li>
                                    <li><span class="badge badge-primary"> نام کامل: </span> {{ $arrayInstagramResultItem['_full_name'] }}</li>
                                    <li><span  class="badge badge-primary"> بیوگرافی: </span> {{ $arrayInstagramResultItem['_biography'] }}</li>
                                    <li><span  class="badge badge-primary"> نوع:</span> {{ $arrayInstagramResultItem['_business_category_name'] }}</li>
                                    <li><span  class="badge badge-primary"> وریفای شده:</span> {!! $arrayInstagramResultItem['_is_verified'] ==1 ? '<img src="https://img.icons8.com/ios/24/000000/checked-2.png"/>' : '' !!} </li>
                                    <li><span  class="badge badge-primary"> پرایویت:</span> {!! $arrayInstagramResultItem['_is_private'] ==1 ? '<img src="https://img.icons8.com/ios/24/000000/checked-2.png"/>' : '' !!} </li>
                                    <li><span  class="badge badge-primary"> اکانت بیزینسی:</span> {!! $arrayInstagramResultItem['_is_business_account'] ==1 ? '<img src="https://img.icons8.com/ios/24/000000/checked-2.png"/>' : '' !!} </li>
                                    <li><span  class="badge badge-primary"> تعداد فالوور:</span> {{ $arrayInstagramResultItem['_edge_followed'] }}</li>
                                    <li><span  class="badge badge-primary"> تعداد فالواینگ:</span> {{ $arrayInstagramResultItem['_edge_follow'] }}</li>
                                    <li><span  class="badge badge-primary"> تعداد پست ها:</span> {{ $arrayInstagramResultItem['_edge_owner_to_timeline_media'] }}</li>

                                    <p><div class="alert alert-success" role="alert">
                                       اکانت مد نظر در سامانه ثبت گردید. برای بررسی به لیست مراجعه کنید.
                                      </div>
                                    </p>
                                    
                                @empty
                                  
                                    <p><div class="alert alert-danger" role="alert">
                                        چنین اکانتی در اینستاگرام وجود ندارد.
                                      </div></p>
                                @endforelse
                         @endif
                        
                    </div>
                </div>
                <!-- /.col-lg-4 -->
            </div>
            <!-- /.row -->
@endsection

