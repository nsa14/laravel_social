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
                                            <th>ردیف</th>
                                            <th> url </th>
                                            <th> dot </th>
                                            <th> glabal rank </th>
                                            <th> local rank </th>
                                            <th> title </th>
                                            <th> howis </th>
                                            <th> expertion date </th>
                                            <th> redirect </th>
                                            <th> code </th>
                                            <th> اخرین بروزرسانی </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allDomains as $domain)

                                        <tr >
                                            <td>{{ $loop->iteration }}</td>
                                            <td> <a href="{{ 'https://'.$domain->url.'.'.$domain->dot.'/' }}" target="_blank"> {{ $domain->url }} </a></td>
                                            <td> {{ $domain->dot }} </td>
                                            <td class="center"> {{ $domain->globalrank }}</td>
                                            <td class="center">{{ $domain->localrank }}</td>
                                            <td style="font-size: 10px; word-wrap: break-word;min-width: 160px;max-width: 160px;">{{ $domain->title }}</td>
                                            <td class="center">{{ $domain->howis }}</td>
                                            <td class="center">{{ $domain->expertion_date }}</td>
                                            <td class="center text-sm">{{ $domain->redirect_to }}</td>
                                            <td class="center">{{ $domain->status_code }}</td>

                                            @php
                                                $faUpdatedAt = new Verta($domain->updated_at);
                                            @endphp
                                            <td class="center" style="font-size: 10px;">{{ $faUpdatedAt }}</td>
                                        {{-- <td class="center"> <a href="{{ route('instagram-profile', $domain->id) }}"><button type="button" class="btn btn-success btn-circle"><i class="fa fa-link"></i>
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