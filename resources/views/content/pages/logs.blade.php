@extends('layouts.layoutMaster')

@section('title', 'Loglar')

@section('content')
  <div class="card">
    <h5 class="card-header">Sistem Logları</h5>
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
        <tr>
          <th>Seviye</th>
          <th>Mesaj</th>
          <th>Bağlam</th>
          <th>Tarih</th>
        </tr>
        </thead>
        <tbody>
        @foreach($logs as $log)
          <tr>
            <td>{{ ucfirst($log->level) }}</td>
            <td>{{ $log->message }}</td>
            <td>{{ $log->context ? json_decode($log->context, true) : '-' }}</td>
            <td>{{ $log->created_at }}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
