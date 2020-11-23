@extends('layouts.master')


@section('content')
<section class="content">
    <div class="row">
      <div class="col-md-3">
        <a href="{{ route('mail.create') }}" class="btn btn-primary btn-block mb-3">انشاء رسالة</a>

        <div class="card text-right">
          <div class="card-body p-0">
            <ul class="nav nav-pills flex-column">
              <li class="nav-item active">
                <a href="{{ route('mail.index') }}" class="nav-link">
                  <i class="fas fa-inbox"></i> صندوق الرسائل
                  <span class="badge bg-primary float-left">12</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('mail.index') }}?type=send" class="nav-link">
                  <i class="far fa-envelope"></i> الرسائل المرسلة
                </a>
              </li>
            </ul>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
      </div>
      <!-- /.col -->
      <div class="col-md-9">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">صندوق الرسائل</h3>
            <div class="card-tools">
              <div class="input-group input-group-sm">
                {{-- <input type="text" class="form-control" placeholder="Search Mail"> --}}
                <div class="input-group-append">
                  <div class="btn btn-primary">
                    <i class="fas fa-search"></i>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <div class="mailbox-controls">
              <!-- Check all button -->
              <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
              </button>
              <div class="btn-group">
                <button type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
              </div>
              <!-- /.btn-group -->
              <button type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
              <div class="float-right">
                1-50/200
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>
                </div>
                <!-- /.btn-group -->
              </div>
              <!-- /.float-left -->
            </div>
            <div class="table-responsive mailbox-messages">
              <table class="table table-hover table-striped">
                <tbody>
                  @foreach ($letters as $letter)
                    <tr>
                      <td>
                        <div class="icheck-primary">
                          <input type="checkbox" value="" id="check1">
                          <label for="check1"></label>
                        </div>
                      </td>
                      <td class="mailbox-name"><a href="{{ route('mail.show', $letter->id) }}">{{ $letter->title }}</a></td>
                      <td class="mailbox-subject"><b>{!! substr(strip_tags($letter->content), 0, 100) !!}...</b>
                      </td>
                      <td class="mailbox-attachment"></td>
                      <td class="mailbox-date">5 mins ago</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <!-- /.table -->
            </div>
            <!-- /.mail-box-messages -->
          </div>
          <!-- /.card-body -->
          {{-- <div class="card-footer p-0">
            <div class="mailbox-controls">
              <!-- Check all button -->
              <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
              </button>
              <div class="btn-group">
                <button type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-reply"></i></button>
                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-share"></i></button>
              </div>
              <!-- /.btn-group -->
              <button type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
              <div class="float-right">
                1-50/200
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-right"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fas fa-chevron-left"></i></button>
                </div>
                <!-- /.btn-group -->
              </div>
              <!-- /.float-left -->
            </div>
          </div> --}}
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
@endsection


@push('foot')
<script>
    $(function () {
      //Enable check and uncheck all functionality
      $('.checkbox-toggle').click(function () {
        var clicks = $(this).data('clicks')
        if (clicks) {
          //Uncheck all checkboxes
          $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
          $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
        } else {
          //Check all checkboxes
          $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
          $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
        }
        $(this).data('clicks', !clicks)
      })
  
      //Handle starring for glyphicon and font awesome
      $('.mailbox-star').click(function (e) {
        e.preventDefault()
        //detect type
        var $this = $(this).find('a > i')
        var glyph = $this.hasClass('glyphicon')
        var fa    = $this.hasClass('fa')
  
        //Switch states
        if (glyph) {
          $this.toggleClass('glyphicon-star')
          $this.toggleClass('glyphicon-star-empty')
        }
  
        if (fa) {
          $this.toggleClass('fa-star')
          $this.toggleClass('fa-star-o')
        }
      })
    })
  </script>
@endpush
