@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
<div class="admin-topbar"><h1>Contact Messages</h1></div>

<div class="panel" style="padding:0">
    <table class="table">
        <thead>
            <tr><th>From</th><th>Subject</th><th>Message</th><th>Received</th><th></th></tr>
        </thead>
        <tbody>
            @forelse ($messages as $m)
                <tr style="{{ $m->is_read ? '' : 'background:#fffbe8' }}">
                    <td>
                        <b>{{ $m->name }}</b><br>
                        <span style="font-size:12.5px;color:var(--muted)">{{ $m->email }}</span>
                    </td>
                    <td>{{ $m->subject ?: '—' }}</td>
                    <td style="max-width:320px">{{ \Illuminate\Support\Str::limit($m->message, 160) }}</td>
                    <td style="white-space:nowrap">{{ $m->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        <div class="table-actions">
                            @unless ($m->is_read)
                                <form method="POST" action="{{ route('admin.messages.read', $m) }}" class="inline-form">
                                    @csrf
                                    <button class="btn btn-outline btn-sm" type="submit">Mark Read</button>
                                </form>
                            @endunless
                            <form method="POST" action="{{ route('admin.messages.destroy', $m) }}" class="inline-form" onsubmit="return confirm('Delete message?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:30px">No messages yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $messages->links() }}
@endsection
