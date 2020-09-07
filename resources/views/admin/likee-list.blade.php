@extends('layouts.admin')

@section('content')
<h1 class="page-header">لیست لایکی </h1>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12 color-dark">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            لیست کلیه اکانت های لایکی ثبت شده در سامانه
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            {{-- <th>ردیف</th> --}}
                                            <th>nickName</th>
                                            <th> userName </th>
                                            <th>birthday</th>
                                            {{-- <th>countryCode</th> --}}
                                            <th>bio</th>
                                            <th> gender </th>
                                            <th> age </th>
                                            <th> fansCount </th>
                                            <th> followCount </th>
                                            <th> likeCount </th>
                                            <th> videoNums </th>
                                            <th> اخرین بروزرسانی </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($likeeLists as $instagramListItem)

                                        <tr >
                                            {{-- <td>{{ $loop->iteration }}</td> --}}
                                        <td> <a href="{{ 'https://likee.com/user/@'.$instagramListItem->userName.'/' }}" target="_blank"> <img class="img-thumbnail rounded-circle" src="{{ $instagramListItem->image }}" width="50" alt=""> {{ $instagramListItem->nickName }} </a></td>
                                            <td> {{ $instagramListItem->userName }} </td>
                                            <td class="center"> {{ $instagramListItem->birthday }}</td>
                                            {{-- <td class="center">{{ $instagramListItem->countryCode }}</td> --}}
                                            <td style="word-wrap: break-word;font-size: 10px;">{{ $instagramListItem->bio }}</td>
                                            <td class="center">{{ $instagramListItem->gender }}</td>
                                            <td class="center">{{ $instagramListItem->age }}</td>
                                            <td class="center">{{ $instagramListItem->fansCount }}</td>
                                            <td class="center">{{ $instagramListItem->followCount }}</td>
                                            <td class="center">{{ $instagramListItem->likeCount }}</td>
                                            <td class="center">{{ $instagramListItem->videoNums }}</td>

                                            @php
                                                $faUpdatedAt = new Verta($instagramListItem->updated_at);
                                            @endphp
                                            <td class="center" style="font-size: 10px;">{{ $faUpdatedAt }}</td>
                                        {{-- <td class="center"> <a href="{{ route('instagram-profile', $instagramListItem->id) }}"><button type="button" class="btn btn-success btn-circle"><i class="fa fa-link"></i>
                                        </button></a></td> --}}
                                        </tr>
                                            
                                        @endforeach
 
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
@endsection

@section('js')

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
        $(document).ready(function() {
        //   $('#dataTables-example').dataTable();
          $('#dataTables-example').DataTable( {
            dom: 'Bfrtip',
                buttons: [
                    'copy', 'excel', 'pdf'
                ]
            } );
        });
    </script>
    
@endsection